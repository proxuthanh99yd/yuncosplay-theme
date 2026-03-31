# Fix to Figma — Verify & Fix Code Against Design

## Overview

Compare existing WordPress template implementation against the Figma design, identify discrepancies, and surgically fix them. Does NOT regenerate from scratch — reads existing PHP/CSS/JS and corrects only what differs. Supports an optional issue description to focus the fix on a specific problem.

## Usage

```
/fix-to-figma [figma-url]
/fix-to-figma [figma-url] [issue-description]
/fix-to-figma [section-hint] [figma-url]
/fix-to-figma [section-hint] [figma-url] [issue-description]
```

**Examples:**
```
/fix-to-figma https://www.figma.com/design/ABC123/YunCosplay?node-id=1-2
/fix-to-figma https://www.figma.com/design/ABC123/YunCosplay?node-id=18-35 font is wrong, spacing between cards is too large
/fix-to-figma section-hero https://www.figma.com/design/ABC123/YunCosplay?node-id=18-35
/fix-to-figma section-hero https://www.figma.com/design/ABC123/YunCosplay?node-id=18-35 background image is not full width, CTA button color is off
```

---

## Instructions

### Step 1 — Parse Input

Extract from the arguments:
- `sectionHint` (optional) — kebab-case section/component name before the URL
- `fileKey` and `nodeId` — from the Figma URL (convert `-` to `:`)
- `issueDescription` (optional) — free-text after the URL describing the problem

If `issueDescription` is provided, it guides **where to look first** but the full diff is still performed.

### Step 2 — Fetch Figma Design

1. `get_design_context({ fileKey, nodeId, framework: "html_css" })` — reference structure + code
2. `get_variable_defs({ fileKey, nodeId })` — design tokens for color/spacing accuracy
3. `get_screenshot({ fileKey, nodeIds: [nodeId] })` — visual reference

Record: reference HTML/CSS, screenshot image, root dimensions, asset URLs.

### Step 3 — Detect Viewport & rem Divisor

| Frame width | Viewport | rem divisor |
|-------------|----------|-------------|
| ≥1200px | Desktop | 16 (or check _variables.css / _reset.css) |
| 640–1199px | Tablet | 16 |
| <640px | Mobile | 16 |

Check the theme's `_variables.css` or `_reset.css` for custom base font-size.

### Step 4 — Find Existing Implementation

1. If `sectionHint` given → look in `template-parts/*/section-{sectionHint}/` or `template-parts/*/{sectionHint}/`
2. Search `template-parts/` for matching directory names or PHP files
3. Read each found template file:
   - `index.php` — HTML structure
   - `assets/styles.css` — CSS styles
   - `assets/scripts.js` — JavaScript (if exists)
4. Also check parent page CSS if sections share a page-level stylesheet

### Step 5 — Diff Against Figma

If `issueDescription` was provided, **prioritize checking those areas first**, then do the full diff.

Compare node by node. Check for:

| Category | What to check |
|----------|---------------|
| **rem values** | All dimensions must use rem, not px. Flag any hardcoded px (except 1px borders). |
| **CSS structure** | Styles should be in separate CSS file with BEM naming |
| **Typography** | font-family, font-size (rem), font-weight, line-height, letter-spacing |
| **Font matching** | Font family must match Figma — check if correct font is loaded |
| **Layout** | Flex direction, gap, alignment, padding, margin |
| **Colors** | Background, text, border — exact hex from Figma |
| **Spacing** | Padding, margin, gap — recalculate rem from Figma px |
| **Assets** | Flag any `figma.com/api/mcp/asset/` URLs (expired!) |
| **Inline styles** | Flag any `style=""` in PHP that should be in CSS |
| **Naming** | Flag Vietnamese in CSS class names or JS variables |
| **Semantic HTML** | Check div-soup, missing semantic elements |
| **H1 count** | Verify only one H1 on the page |
| **Missing elements** | Anything in Figma but not in the code |
| **Extra elements** | Anything in code but not in Figma |
| **WordPress** | Proper escaping, get_theme_file_uri(), get_template_part() |

Produce a prioritized diff list:
```
[CRITICAL] Section uses width: 1600px → must use rem-based or percentage width
[ASSET] hero-banner.webp uses expired Figma URL → download to assets/images/
[FONT] Gilroy not loaded → add to Google Fonts or local @font-face
[STYLE] .section-hero__title has inline style → move to styles.css
[NAMING] CSS class "tieuDe" → rename to "title"
[FIX] .section-hero__desc color: #666 → should be #4A4A4A per Figma
[MISSING] CTA secondary button exists in Figma but not in template
```

If `issueDescription` was provided, tag related items with `[REPORTED]`:
```
[REPORTED][FONT] Font family doesn't match — Gilroy not loaded
[REPORTED][FIX] Card gap is 24px → should be 1.25rem per Figma
[FIX] .section-hero__desc color: #666 → should be #4A4A4A per Figma
```

### Step 6 — Apply Fixes

- Fix `[REPORTED]` items first (user's stated issues), then by priority: CRITICAL → ASSET → FONT → STYLE → NAMING → FIX → MISSING
- Fix one item at a time using `Edit` (targeted replacement)
- For font issues: check existing fonts → try Google Fonts → add local files → closest alternative
- Download expired assets before updating references
- Update CSS in `assets/styles.css`
- Update PHP in `index.php`
- Re-read each file after editing to verify the fix applied

### Step 7 — Visual Verification Loop

1. Open the WordPress site in browser (LocalWP URL)
2. Take browser screenshot of the fixed section
3. Compare browser screenshot against Figma screenshot
4. If differences remain → fix them → re-screenshot → compare again
5. **Repeat until the section matches Figma** — no fixed iteration limit
6. Only stop when differences are truly negligible (subpixel rendering only)

### Step 8 — Final Quality Check

```bash
# PHP syntax check
find template-parts/{pageName} -name "*.php" -exec php -l {} \;

# Check for Figma URLs
grep -r "figma.com/api/mcp/asset" template-parts/{pageName}/ && echo "ERROR" || echo "OK"

# Check for inline styles
grep -r 'style="' template-parts/{pageName}/ --include="*.php" && echo "WARN: inline styles found" || echo "OK"
```

### Step 9 — Report

```markdown
## Fix Report — {Section Name}

### Issue
{issueDescription or "Full diff against Figma"}

### Viewport
Figma frame: {width} × {height}px → {platform} (rem divisor: {divisor})

### Fixes applied
| Priority | File | Change |
|----------|------|--------|
| REPORTED | styles.css | Font → added Google Fonts enqueue for Gilroy |
| REPORTED | styles.css | Card gap → 24px to 1.25rem |
| CRITICAL | index.php | width: 1600px → width: 100% |
| ASSET | index.php | Figma URL → get_theme_file_uri('/assets/images/home/d-hero-banner.webp') |
| FIX | styles.css | .section-hero__desc color → #4A4A4A |

### Verified
- Screenshot matches Figma ({N} fix iterations)

### No changes needed
- Header (matches Figma)

### Remaining TODOs
- Mobile variant not in Figma — add responsive styles when designs available
```

---

## Rules

1. **Never regenerate from scratch** — only fix what differs.
2. **User's reported issue is top priority** — fix it first, then handle other diffs.
3. **Always fetch fresh Figma data** — never use cached values.
4. **All fixes use rem** — convert any remaining px to rem (except 1px borders).
5. **CSS in separate file** — no inline `style=""` attributes.
6. **BEM naming** — `.section-{name}__element--modifier`.
7. **Fonts must match Figma** — check existing → Google Fonts → local files → alternative.
8. **Minimal edits** — change only what differs from Figma.
9. **Re-read after edit** — verify the fix applied correctly.
10. **Visual verification until done** — screenshot, compare, fix, repeat. No iteration limit.
11. **WordPress conventions** — `get_theme_file_uri()`, `esc_html()`, `get_template_part()`.
