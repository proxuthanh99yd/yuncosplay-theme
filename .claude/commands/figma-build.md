# Figma Build

## Overview

Read the implementation plan from `PLAN.md` and execute it fully autonomously. Downloads assets, sets up fonts, generates all section templates in parallel via subagents, assembles the page, and runs quality checks.

## Usage

```
/figma-build
/figma-build [page-name]
```

If no page-name given, find the most recent `PLAN.md` in `template-parts/*/`.

## Instructions

### Step 0 — Load Plan

Read `template-parts/{pageName}/PLAN.md`.

Extract:
- Page name, design dimensions, rem divisor
- Section list with nodeIds, components, heading assignments
- Common components list
- Typography reference (CSS per text style)
- Asset list with filenames and Figma node IDs
- Font requirements
- Asset enqueue plan

If PLAN.md doesn't exist, tell the user to run `/figma-plan` first.

---

### Step 1 — Project Setup (sequential, do before any generation)

#### 1a. Download ALL assets from Figma

The Figma MCP does NOT have a `get_figma_images` tool. Assets are extracted from `get_design_context` responses.

**How to get asset URLs:**
1. Call `get_design_context({ fileKey, nodeId })` for each section
2. The response contains asset URLs as constants at the top
3. Extract ALL `https://www.figma.com/api/mcp/asset/...` URLs from the response
4. Map each URL to its planned local filename

**Downloading images:**
```bash
# Download to a temp file first, then detect and rename
curl -sL -o "/tmp/figma-asset-tmp" "FIGMA_ASSET_URL"
```

**IMPORTANT: Asset URLs expire after 7 days.** Download them immediately.

**CRITICAL: Detect the actual file format and use the correct extension.**

**Use this helper function for ALL asset downloads:**
```bash
download_figma_asset() {
  local url="$1"
  local base="$2"  # NO extension — e.g. assets/images/home/d-hero

  curl -sL -o /tmp/figma-dl-tmp "$url"

  local mime
  mime=$(file -b --mime-type /tmp/figma-dl-tmp)
  local ext
  case "$mime" in
    image/png)                  ext="png" ;;
    image/jpeg)                 ext="jpg" ;;
    image/svg+xml)              ext="svg" ;;
    image/webp)                 ext="webp" ;;
    image/gif)                  ext="gif" ;;
    image/avif)                 ext="avif" ;;
    text/xml|application/xml)   ext="svg" ;;
    text/html)
      echo "  SKIP: ${base} — received HTML (likely error page)"
      rm -f /tmp/figma-dl-tmp
      return 1
      ;;
    *)
      echo "  WARN: ${base} — unknown type '${mime}', defaulting to .png"
      ext="png"
      ;;
  esac

  mv /tmp/figma-dl-tmp "${base}.${ext}"
  echo "  OK: ${base}.${ext}"
}
```

**Usage:**
```bash
download_figma_asset "FIGMA_URL_1" "assets/images/{pageName}/d-hero-banner"
download_figma_asset "FIGMA_URL_2" "assets/images/{pageName}/d-product-1"
download_figma_asset "FIGMA_URL_3" "assets/icons/ic-arrow-right"
```

**After downloading, record the real filenames** so templates reference the correct extension.

**Batch validation after all downloads:**
```bash
for f in assets/images/{pageName}/*; do
  mime=$(file -b --mime-type "$f")
  actual_ext="${f##*.}"
  expected_ext=$(case "$mime" in
    image/png) echo png;; image/jpeg) echo jpg;; image/svg+xml) echo svg;;
    image/webp) echo webp;; image/gif) echo gif;; *) echo "$actual_ext";;
  esac)
  if [ "$actual_ext" != "$expected_ext" ]; then
    mv "$f" "${f%.*}.${expected_ext}"
    echo "Renamed: $(basename $f) → $(basename ${f%.*}).${expected_ext}"
  fi
done
```

**Fallback for missing assets:**
- If an asset URL fails, use `get_screenshot({ fileKey, nodeIds: [nodeId] })` as PNG fallback

#### 1b. Acquire & Register Fonts (must match Figma exactly)

For each font in the plan, try these sources IN ORDER:

**Priority 1 — Already loaded in theme:**
- Check `assets/fonts/stylesheet.css` for existing `@font-face`
- Check `import-css-js.php` for existing Google Fonts enqueue
- If found, just note the font-family name and available weights

**Priority 2 — Google Fonts (via wp_enqueue_style):**
```php
// In import-css-js.php
wp_enqueue_style('font-{name}', 'https://fonts.googleapis.com/css2?family={Name}:wght@400;600;800&display=swap', [], THEME_VERSION);
```
- Include Vietnamese subset if needed: `&subset=vietnamese`
- Add preconnect in `my_add_preconnects()` if not already present

**Priority 3 — Local font files:**
- Download `.woff2` files to `assets/fonts/{font-name}/`
- Add `@font-face` declarations in `assets/fonts/stylesheet.css`
  ```css
  @font-face {
      font-family: 'FontName';
      src: url('./font-name/font-name-regular.woff2') format('woff2');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
  }
  ```

**Priority 4 — Closest Google Font alternative:**
- Find closest match, add comment: `/* Using {alternative} — get exact font from design team */`

#### 1c. Create scaffold files

**Page orchestrator:** `template-parts/{pageName}/index.php`
```php
<?php
/**
 * Page: {Page Name}
 * Loads all sections in order
 */
get_template_part('template-parts/{pageName}/section-hero/index');
get_template_part('template-parts/{pageName}/section-about/index');
// ... all sections in layoutOrder
?>
```

**Page template (if new page):** `page-{pageName}.php` or use existing template
```php
<?php
/**
 * Template Name: {Page Name}
 */
get_header();
get_template_part('template-parts/{pageName}/index');
get_footer();
?>
```

**Page-level assets:** `template-parts/{pageName}/assets/styles.css` and `scripts.js`

---

### Step 2 — Generate Common Components First (parallel)

Spawn one subagent per common component from the plan. Each subagent:

1. Gets the Figma design data for the component's node (`get_design_context`)
2. Gets a screenshot for reference (`get_screenshot`)
3. Generates the component following ALL rules from the component-generator agent:
   - Separate CSS file with BEM naming
   - All dimensions in rem
   - Typography in CSS
   - Local asset paths via `get_theme_file_uri()`
   - Semantic HTML, max 4-5 nesting levels
   - PHP escaping for output
4. Returns: file paths, CSS classes used, any warnings

**Run ALL common component subagents in parallel.**
Wait for all to complete before Step 3.

---

### Step 3 — Generate Section Templates (parallel)

Spawn one subagent per section from the plan. Each subagent:

1. Gets the Figma design data for the section's node (`get_design_context`)
2. Gets a screenshot for visual reference (`get_screenshot`)
3. Gets image fills if the section has images (`get_image_fills`)
4. Generates the section template + any section-specific sub-templates:
   - PHP template: `index.php` with semantic HTML
   - CSS file: `assets/styles.css` with BEM naming
   - JS file: `assets/scripts.js` (only if interactive)
   - Use local asset paths via `get_theme_file_uri()`
   - Include common components from Step 2 via `get_template_part()`
   - `<h1>` ONLY if this section is marked as the H1 section in the plan
5. Returns: file paths created, CSS classes used, warnings, TODOs

**Run ALL section subagents in parallel.**
Wait for all to complete before Step 4.

---

### Step 4 — Assemble Page

After all templates are generated:

1. **Update the page orchestrator** (`{pageName}/index.php`):
   - Include all section templates via `get_template_part()` in layoutOrder

2. **Register assets in `import-css-js.php`:**
   - Add page-level CSS/JS entries with proper conditions
   - Add section-level CSS/JS if loaded separately
   ```php
   [
       'type' => 'style',
       'handle' => '{pageName}',
       'src' => get_theme_file_uri('/template-parts/{pageName}/assets/styles.css'),
       'deps' => [],
       'ver' => THEME_VERSION,
       'condition' => is_page_template('page-{pageName}.php')
   ],
   ```
   - Add `type="module"` for JS if using ES modules (update `$module_handles` array)

3. **Verify single H1:**
   - Grep all generated templates for `<h1`
   - If more than one found → fix: change extras to `<h2>`
   - If none found → add to the section marked in plan

4. **Create page template** if needed:
   - `page-{pageName}.php` in theme root with `Template Name:` comment

---

### Step 5 — Quality Checks (fix until clean)

```bash
# 1. PHP syntax check — all generated files
find template-parts/{pageName} -name "*.php" -exec php -l {} \;

# 2. Verify all image assets exist
find template-parts/{pageName} -name "*.php" -exec grep -oh "get_theme_file_uri('[^']*')" {} \; | sort -u | while read -r uri; do
  path=$(echo "$uri" | grep -o "'[^']*'" | tr -d "'")
  [ ! -f ".${path}" ] && echo "MISSING: $path"
done

# 3. Verify CSS syntax (basic check)
find template-parts/{pageName} -name "*.css" -exec echo "Checking: {}" \;

# 4. Check for remaining Figma URLs
grep -r "figma.com/api/mcp/asset" template-parts/{pageName}/ && echo "ERROR: Figma URLs found!" || echo "OK: No Figma URLs"
```

Do NOT proceed to Step 6 until all checks pass.

---

### Step 6 — Visual Verification & Self-Improvement Loop

This is the critical step. Open the WordPress site in a browser, screenshot sections, compare against Figma, and fix differences iteratively.

#### 6a. Setup

```bash
# Verify WordPress is running (LocalWP)
curl -s -o /dev/null -w "%{http_code}" "http://yuncosplay.local/{pageName}/"
# Must return 200
```

#### 6b. Section-by-Section Comparison Loop

For EACH section in layoutOrder:

1. **Get Figma reference** for this section:
   ```
   get_screenshot({ fileKey, nodeIds: [sectionNodeId] })
   ```

2. **Take browser screenshot** — open WordPress site and screenshot the section

3. **Compare both images** — analyze side by side and list ALL differences:
   - **Typography:** font family, size, weight, color, line-height
   - **Spacing:** padding, margin, gap between elements
   - **Colors:** background, text, borders, gradients, opacity
   - **Layout:** element positions, alignment, flex direction, dimensions
   - **Images/Icons:** present, correct size, correct position, not broken
   - **Interactive elements:** carousel arrows, indicators, etc.
   - **Missing/extra elements**

4. **Fix differences:**
   - Edit CSS in `assets/styles.css`
   - Edit HTML in `index.php`
   - Edit JS in `assets/scripts.js`

5. **Re-check** after fixes. **Repeat until this section matches.**

6. **Move to next section** only when current section is verified

#### 6c. Full-Page Final Check

After all sections pass individually:
1. Check section spacing/gaps between sections
2. Overall page flow and visual rhythm
3. All interactive elements functional
4. Colors consistent across sections

#### 6d. Verification Checklist (must ALL pass)

- [ ] Every section visually matches Figma
- [ ] Fonts match Figma (correct family, weight, size)
- [ ] Colors match Figma (exact hex values)
- [ ] Spacing matches Figma (converted to rem correctly)
- [ ] All images/icons visible and correctly positioned
- [ ] No broken images or missing assets
- [ ] No Figma asset URLs remaining in code
- [ ] No inline `style=""` attributes
- [ ] No Vietnamese in CSS class names or JS variables
- [ ] Only one `<h1>` per page
- [ ] Assets properly enqueued in `import-css-js.php`
- [ ] PHP syntax valid for all files
- [ ] All CSS in separate files (BEM naming)
- [ ] WordPress conventions followed (`get_theme_file_uri()`, `esc_html()`, etc.)

---

### Step 7 — Report

```markdown
## Build Complete: {pageName}

### Files Created ({count})
  page-{pageName}.php                              # Page template (if new)
  template-parts/{pageName}/index.php               # Section orchestrator
  template-parts/{pageName}/assets/styles.css       # Page-level styles
  template-parts/{pageName}/assets/scripts.js       # Page-level scripts
  template-parts/{pageName}/section-hero/
    index.php
    assets/styles.css
    assets/scripts.js
  template-parts/{pageName}/section-about/
    index.php
    assets/styles.css
  ...

### Assets Downloaded
  assets/images/{pageName}/ ({count} images)
  assets/icons/ ({count} SVGs)

### Asset Enqueue
  Updated: import-assets/import-css-js.php
  New handles: {list}

### Quality
  PHP syntax: valid
  No Figma URLs remaining
  Single H1 verified
  Visual: validated ({iterations} iterations)

### Fonts
  {font}: {source} ({weights})
  ...

### Warnings
  - {any warnings from subagents}

### TODOs (manual action needed)
  - {any TODOs like missing font files, dynamic content to wire up}
```

---

## Subagent Instructions

When spawning subagents for template generation, pass this context:

```
You are generating a WordPress template part (PHP + CSS + JS) from a Figma design.

CRITICAL RULES:
1. OUTPUT STRUCTURE — each section generates:
   - index.php (semantic HTML with PHP)
   - assets/styles.css (BEM-named CSS)
   - assets/scripts.js (only if interactive — vanilla JS or Swiper/GSAP)

2. CSS PRECISION:
   - All dimensions in rem (figma_px / remDivisor, 4 decimal places)
   - Exact hex colors from Figma (no generic names)
   - BEM naming: .section-{name}__element--modifier
   - No inline styles — everything in CSS file
   - No Tailwind — vanilla CSS only
   - Extract each padding side individually
   - Include letter-spacing and line-height always

3. PHP TEMPLATE:
   - Use get_theme_file_uri() for asset paths
   - Use esc_html(), esc_attr(), esc_url() for output
   - Include sub-templates via get_template_part()
   - Semantic HTML elements (section, article, nav, etc.)

4. ASSETS:
   - Download all images from Figma to local paths
   - Images → assets/images/{pageName}/d-{name}.{ext}
   - Use get_theme_file_uri('/assets/images/...') in templates
   - NEVER use figma.com URLs in template code
   - Validate downloads with `file --mime-type`

5. JAVASCRIPT (only if interactive):
   - Use Swiper for carousels (already loaded globally)
   - Use GSAP for scroll animations (already loaded globally)
   - Use Alpine.js for simple reactivity (already loaded globally)
   - Vanilla JS for everything else
   - ES module format (type="module")

6. SEMANTIC HTML: <section>, <article>, <nav>, <header>, <footer>, <ul>+<li>, <button>, <a>
7. Max 4-5 nesting levels
8. No Vietnamese in CSS class names or JS variables
9. H1 only if assigned to this section
10. Decorative images: alt="" aria-hidden="true"
11. Interactive elements: hover/focus CSS states

Asset paths: {mapping}
Design: {designWidth}×{designHeight}px, rem divisor: {remDivisor}

Create the template at: {outputPath}
```

---

## Rules

1. **Read PLAN.md first** — the plan is the contract, follow it exactly.
2. **Download ALL assets before generating code** — no Figma URLs in templates.
3. **CSS in separate files** — BEM naming, no inline styles, no Tailwind.
4. **Common components before sections** — sections may depend on common components.
5. **Maximum parallelism** — all common components in parallel, then all sections in parallel.
6. **Every subagent follows component-generator rules** — pass the rules in the subagent prompt.
7. **Fonts must match Figma** — check existing → Google Fonts → local files → closest alternative.
8. **Register assets** — update `import-css-js.php` with new CSS/JS enqueue entries.
9. **Visual verification is section-by-section** — screenshot each, compare against Figma, fix, repeat.
10. **Self-improve until done** — do NOT stop at 3 iterations. Keep fixing until matched.
11. **Fix issues autonomously** — don't ask the user, just fix and re-check.
12. **Report everything** — files, assets, enqueue updates, quality, warnings, TODOs.
