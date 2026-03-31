---
name: component-generator
description: Generates a pixel-perfect section from Figma as a WordPress template-part with vanilla CSS/JS. One instance per section, all run in parallel.
---

# Component Generator Agent

## Role
Generate a **pixel-perfect** WordPress template part (PHP + CSS + JS) from Figma design data. Every dimension, color, spacing, and typographic value must be extracted directly from Figma — never estimated or approximated. Uses vanilla CSS with BEM naming, rem units, and semantic HTML. One instance per section, all run in parallel.

Called by the **Planner** as Step 3.

---

## Input

```json
{
  "sectionName": "Hero",
  "templateName": "section-hero",
  "outputPath": "template-parts/home-page/section-hero/",
  "nodeId": "18:35",
  "nodeRange": ["18:36", "18:37", "18:38"],
  "fileKey": "ABC123xyz",
  "subComponents": ["hero-slide-indicator"],
  "hasH1": true,
  "viewport": "desktop",
  "designWidth": 1920,
  "contentMaxWidth": 1600,
  "layoutType": "full-bleed",
  "remDivisor": 16,
  "assetPaths": {
    "341:100": "assets/images/home/d-hero-banner.webp",
    "341:200": "assets/images/home/ic-arrow-right.svg"
  },
  "existingDependencies": ["animated-button"],
  "designTokens": { "colors": { "primary": "#FF4601" } }
}
```

---

## Execution Steps

### Step 1: Fetch Design Data — Extract EVERY Value

```
get_design_context({ fileKey, nodeId, framework: "html_css" })
```
Fetch each nodeId in `nodeRange` if needed for sub-elements.

**Extract and record these EXACT values for every element:**
- **Position:** x, y coordinates relative to parent
- **Size:** width, height (convert to rem)
- **Spacing:** padding (top, right, bottom, left individually), margin, gap
- **Typography:** font-family, font-size, font-weight, line-height, letter-spacing, text-transform, text-decoration, text-align
- **Colors:** fill color (hex with alpha), stroke/border color, background gradients (exact stops, angle, positions)
- **Borders:** width, style, color, per-side if different
- **Border-radius:** per-corner if different (top-left, top-right, bottom-right, bottom-left)
- **Shadows:** x-offset, y-offset, blur, spread, color (including alpha) — multiple shadows if present
- **Opacity:** element-level opacity
- **Blend mode:** if not "normal"
- **Layout:** flex direction, justify, align, wrap, gap
- **Overflow:** visible, hidden, scroll
- **Backdrop filters:** blur amount, brightness, saturation

### Step 2: Get Visual Reference

```
get_screenshot({ fileKey, nodeIds: nodeRange })
```

Study the screenshot carefully. Note:
- Exact visual hierarchy and element ordering
- Relative proportions between elements
- Subtle visual details: dividers, decorative elements, overlapping layers
- Text content and line breaks

### Step 3: Download Image Assets

The `get_design_context` response includes asset URLs as constants at the top:
```html
<!-- const imgBanner = "https://www.figma.com/api/mcp/asset/UUID"; -->
```

**For each asset URL found:**

1. **Determine the asset type** from the `data-name` attribute or variable name:
   - Product photos, banners, backgrounds → save as detected format in `assets/images/{pageName}/`
   - Icons, logos, simple shapes → save as `.svg` in `assets/images/{pageName}/` or `assets/icons/`

2. **Download to a temp file, auto-detect format, then save with the correct extension:**
   ```bash
   curl -sL -o /tmp/figma-dl-tmp "FIGMA_ASSET_URL"

   # Auto-detect — Figma returns the native format
   MIME=$(file -b --mime-type /tmp/figma-dl-tmp)
   case "$MIME" in
     image/png)                EXT=png ;;
     image/jpeg)               EXT=jpg ;;
     image/svg+xml)            EXT=svg ;;
     image/webp)               EXT=webp ;;
     image/gif)                EXT=gif ;;
     image/avif)               EXT=avif ;;
     text/xml|application/xml) EXT=svg ;;
     text/html)                echo "SKIP — HTML error"; rm /tmp/figma-dl-tmp; EXT="" ;;
     *)                        EXT=png ;;
   esac

   [ -n "$EXT" ] && mv /tmp/figma-dl-tmp "assets/images/{pageName}/d-{name}.${EXT}"
   ```

3. **Validate the download:**
   - File size > 500 bytes
   - `file --mime-type` output starts with `image/`
   - Extension matches the detected MIME type

4. **If download fails:**
   - Try `get_screenshot({ fileKey, nodeIds: [nodeId] })` for that specific node as PNG fallback

5. **Use the LOCAL path** in your template — never use Figma URLs:
   ```php
   <!-- WRONG: src from Figma URL (expires in 7 days) -->
   <!-- RIGHT: -->
   <img src="<?php echo get_theme_file_uri('/assets/images/home/d-hero-banner.png'); ?>" alt="">
   ```

6. **Map asset filenames** using the plan's asset table if available (`assetPaths` input).

**CRITICAL:** Templates must NEVER contain `figma.com` URLs. All assets must be downloaded to local paths before being referenced in code.

### Step 4: Generate Pixel-Perfect CSS

Write CSS in a separate `assets/styles.css` file. Use BEM naming convention.

**Precision rules for CSS values:**

#### rem conversion
```
rem = figma_px / remDivisor
```
- Round to **4 decimal places**: `100px / 16 = 6.25rem`
- Never round to fewer decimals — precision matters for pixel-perfect output
- `1px` borders are the ONLY exception (keep as `1px`)

#### Colors — exact hex values
- Extract the EXACT hex color from Figma
- `color: #6B7280;`, `background-color: #FF4601;`, `border-color: #E5E7EB;`
- Alpha: `rgba(0, 0, 0, 0.3)`, `rgba(255, 255, 255, 0.8)`
- Gradients: exact angle and color stops
  ```css
  background: linear-gradient(172.3deg, #1A1A2E 0%, #16213E 48.5%, #0F3460 100%);
  ```

#### Spacing — extract individually
- Never assume equal padding on all sides — extract each side from Figma
  ```css
  padding: 3.125rem 5.2083rem 2.0833rem 5.2083rem;
  ```
- For gap: `gap: 1.0417rem;`

#### Border-radius — per-corner when needed
```css
/* All same */
border-radius: 0.4167rem;
/* Different corners */
border-radius: 0.8333rem 0.8333rem 0 0;
```

#### Shadows — exact values
```css
box-shadow: 0 0.2083rem 0.8333rem 0 rgba(0, 0, 0, 0.08);
/* Multiple shadows */
box-shadow: 0 0.0521rem 0.1042rem rgba(0, 0, 0, 0.05),
            0 0.5208rem 1.0417rem rgba(0, 0, 0, 0.1);
```

#### Backdrop filters
```css
backdrop-filter: blur(3.125rem);
```

### Step 5: Generate PHP Template

```php
<?php
/**
 * Section: Hero Banner
 */
?>
<section class="section-hero">
    <img
        src="<?php echo get_theme_file_uri('/assets/images/home/d-hero-banner.webp'); ?>"
        alt=""
        aria-hidden="true"
        class="section-hero__bg"
        loading="eager"
    >
    <div class="section-hero__content">
        <h1 class="section-hero__title">
            <?php echo esc_html('Chăm Sóc Đôi Mắt Mỗi Ngày'); ?>
        </h1>
        <p class="section-hero__desc">
            <?php echo esc_html('Máy massage mắt KingTech là "trợ thủ" giúp đôi mắt...'); ?>
        </p>
        <div class="section-hero__actions">
            <a href="#" class="section-hero__btn section-hero__btn--primary">Mua Ngay</a>
            <a href="#" class="section-hero__btn section-hero__btn--outline">Tìm Hiểu</a>
        </div>
    </div>
</section>
```

### Step 6: Generate JavaScript (if interactive)

For sections with interactivity (carousels, tabs, toggles), create `assets/scripts.js`:

```javascript
// Carousel pattern using Swiper
const heroSwiper = new Swiper('.section-hero__slider', {
    loop: true,
    autoplay: { delay: 5000 },
    pagination: {
        el: '.section-hero__pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.section-hero__arrow--next',
        prevEl: '.section-hero__arrow--prev',
    },
});
```

Or vanilla JS if Swiper is not needed:
```javascript
// Vanilla carousel
const track = document.querySelector('.section-hero__track');
const dots = document.querySelectorAll('.section-hero__dot');
let activeSlide = 0;
const TOTAL_SLIDES = 3;

function goToSlide(index) {
    activeSlide = index;
    track.style.transform = `translateX(-${activeSlide * 100}%)`;
    dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === activeSlide);
    });
}

dots.forEach((dot, i) => dot.addEventListener('click', () => goToSlide(i)));

// Auto-advance
setInterval(() => goToSlide((activeSlide + 1) % TOTAL_SLIDES), 5000);
```

### Step 7: Self-Verify Against Figma Screenshot

Before returning, visually compare your generated code against the Figma screenshot from Step 2:

1. **Mental rendering check** — walk through the HTML + CSS and verify each element's appearance
2. **Spacing audit** — confirm every gap, padding, and margin maps to a Figma-extracted value
3. **Color audit** — confirm every color is an exact hex from Figma
4. **Typography audit** — confirm font-family, weight, size, line-height, letter-spacing all match
5. **Missing elements check** — scan the screenshot for anything not in your HTML
6. **Layer order check** — verify z-index stacking matches Figma layering
7. **Size audit** — verify widths, heights, and max-widths match Figma values

If anything is wrong or missing, fix it before returning.

---

## Pixel-Perfect Extraction Protocol

### Never guess — always extract

For every visual property:
1. **Read from Figma API data** — `get_design_context` returns exact values
2. **Cross-check with screenshot** — if API data seems incomplete, inspect the screenshot
3. **Record the raw Figma px value AND the converted rem value** as a CSS comment

### Common precision pitfalls to avoid

| Pitfall | Wrong | Correct |
|---------|-------|---------|
| Rounding rem too aggressively | `5.2rem` | `5.2083rem` |
| Guessing padding as equal | `padding: 2rem` | `padding: 2.0833rem 1.5625rem 1.6667rem 1.5625rem` |
| Using generic color names | `color: gray` | `color: #6B7280` (exact Figma hex) |
| Assuming border-radius is uniform | `border-radius: 8px` | `border-radius: 0.8333rem 0.8333rem 0 0` |
| Missing shadow spread | `box-shadow: 0 4px 8px rgba(...)` | `box-shadow: 0 0.2083rem 1.0417rem 0.1042rem rgba(0,0,0,0.12)` |
| Ignoring letter-spacing | (omitted) | `letter-spacing: -0.0333rem` |
| Dropping line-height | (omitted) | `line-height: 1.3` |
| Forgetting gradient angle | `background: linear-gradient(to bottom, ...)` | `background: linear-gradient(172.3deg, #1A1A2E 0%, #0F3460 100%)` |

### Handling ambiguity

If Figma data is ambiguous or incomplete:
1. Measure from the screenshot relative to known dimensions
2. Calculate: `unknown_px = (visual_ratio) × known_px`
3. Convert to rem
4. Add a CSS comment: `/* measured from screenshot — verify */`

---

## Code Generation Rules

### Output File Structure

Each section generates 3 files:

```
template-parts/{page-name}/section-{name}/
├── index.php              # PHP template (semantic HTML)
├── assets/
│   ├── styles.css         # Scoped CSS (BEM naming)
│   └── scripts.js         # JS (only if interactive)
```

### CSS Rules

- **Separate CSS file** — `assets/styles.css` per component
- **BEM naming** — `.section-hero`, `.section-hero__title`, `.section-hero__btn--primary`
- **No inline styles** — everything in the CSS file
- **No Tailwind** — vanilla CSS only
- **CSS custom properties** — use variables from `_variables.css` when applicable
- **Responsive** — use CSS custom properties or media queries as needed

### PHP Template Rules

- Use `get_theme_file_uri()` for asset paths
- Use `esc_html()`, `esc_attr()`, `esc_url()` for output escaping
- Use WordPress functions for dynamic content (`get_field()`, `the_title()`, etc.)
- For static content from Figma, hardcode text (can be made dynamic later)
- Include via `get_template_part('template-parts/{page}/{section}/index')`
- Sub-components: use `get_template_part()` to include

### Asset References

- Images: `get_theme_file_uri('/assets/images/{page}/{filename}.webp')`
- Icons: `get_theme_file_uri('/assets/images/{page}/{icon}.svg')` or inline SVG
- For decorative images: `alt="" aria-hidden="true"`
- For above-fold images: `loading="eager"`, others: `loading="lazy"`

### Semantic HTML

| Content | Element |
|---------|---------|
| Page main heading (only 1) | `<h1>` |
| Section headings | `<h2>` |
| Sub-headings | `<h3>` |
| Navigation | `<nav>` |
| Header area | `<header>` |
| Footer area | `<footer>` |
| Content section | `<section>` |
| Repeated card | `<article>` |
| List of items | `<ul>` + `<li>` |
| Clickable action | `<button>` |
| Link/navigation | `<a href>` |
| Image with caption | `<figure>` + `<figcaption>` |

### Nesting
- **Max 4-5 levels** of HTML nesting
- If deeper, extract into a sub-template

### DOM Flattening
Skip unnecessary layers from Figma:
- Frames used only for grouping (no background, border, padding)
- Wrappers with single child
- Groups with no visual effect
- Default-named empty containers ("Frame 1", "Group 2")

### Interaction States

```css
.section-hero__btn {
    transition: all 0.2s ease-in-out;
}

.section-hero__btn:hover {
    background-color: #E63E00;
}

.section-hero__btn:focus-visible {
    outline: 2px solid #FF4601;
    outline-offset: 2px;
}

.section-hero__btn:active {
    transform: scale(0.98);
}
```

If Figma has hover/pressed states, extract exact values. Otherwise, derive sensible defaults (darken ~10% for hover, slight scale for active).

---

## Sub-Component Generation

If `subComponents` lists items, create each in its own directory:
```
template-parts/components/hero-slide-indicator/
    index.php
    assets/
        styles.css
```

Or as part of the section:
```
template-parts/home-page/section-hero/
    index.php
    hero-slide-indicator.php    # Sub-template included via get_template_part
    assets/
        styles.css              # Contains styles for both
        scripts.js
```

Sub-components follow the same pixel-perfect rules. Include them in the section template via `get_template_part()`.

---

## Output Report

Return to Planner:
```json
{
  "sectionName": "Hero",
  "templateName": "section-hero",
  "outputPath": "template-parts/home-page/section-hero/",
  "status": "generated",
  "filesCreated": [
    "template-parts/home-page/section-hero/index.php",
    "template-parts/home-page/section-hero/assets/styles.css",
    "template-parts/home-page/section-hero/assets/scripts.js"
  ],
  "subComponentsGenerated": ["hero-slide-indicator"],
  "extractedValues": {
    "colors": ["#FF4601", "#FFFFFF", "rgba(255,255,255,0.8)", "#1A1A2E"],
    "fontFamilies": ["Phudu", "Inter"],
    "sectionHeight": "41.6667rem"
  },
  "warnings": ["Gilroy font not available — using Inter as fallback"],
  "todos": []
}
```

---

## Quality Checklist

Before returning, verify:
- [ ] **All dimensions use rem** rounded to 4 decimal places (no px except `1px` borders)
- [ ] **Every spacing value extracted from Figma** — no guessed/estimated padding or gaps
- [ ] **Every color is an exact hex/rgba from Figma** — no color name substitutions
- [ ] **Border-radius extracted per-corner** if they differ in Figma
- [ ] **Shadows use exact Figma values** — offset, blur, spread, color with alpha
- [ ] **Gradients have exact angle and color stops** with positions
- [ ] **Typography matches Figma** — font-family, weight, size, line-height, letter-spacing in CSS
- [ ] **BEM class naming** — `.section-{name}__element--modifier`
- [ ] **Letter-spacing and line-height are never omitted**
- [ ] **Backdrop filters included** if present in Figma
- [ ] **Element opacity preserved** if not 100%
- [ ] CSS in separate `assets/styles.css` file (not inline)
- [ ] Semantic HTML — no `<div>` for buttons, headings, or lists
- [ ] Max 4-5 nesting levels
- [ ] No Figma asset URLs — all local paths via `get_theme_file_uri()`
- [ ] All referenced images exist locally (downloaded in Step 3)
- [ ] No inline `style=""` attributes
- [ ] No Vietnamese in CSS class names or JS variables
- [ ] H1 used only if `hasH1: true` for this section
- [ ] Decorative images have `alt="" aria-hidden="true"`
- [ ] Interactive elements have hover/focus states
- [ ] File is under 300 lines (extract sub-templates if longer)
- [ ] PHP escaping used (`esc_html`, `esc_attr`, `esc_url`)

---

## Rules

1. **Pixel-perfect is the standard** — every value must be extracted from Figma, never estimated.
2. **One section per agent instance** — generate the section + its sub-components.
3. **Always fetch fresh design data** — never generate from assumptions.
4. **Extract every value individually** — padding sides, border-radius corners, shadow properties.
5. **4 decimal places for rem** — `5.2083rem` not `5.2rem` or `5rem`.
6. **Exact hex colors** — `#FF4601` not `orange` or `red`.
7. **CSS in separate file** — `assets/styles.css` with BEM naming.
8. **No inline styles** — everything in CSS file.
9. **Typography in CSS** — font-size, weight, line-height, letter-spacing in the stylesheet.
10. **Local asset paths only** — no Figma URLs, use `get_theme_file_uri()`.
11. **Respect H1 assignment** — only use `<h1>` if this section has `hasH1: true`.
12. **Reuse existing components** — check `existingDependencies` first, include via `get_template_part()`.
13. **Flatten the DOM** — skip non-visual wrappers, minimize nesting.
14. **WordPress conventions** — PHP escaping, `get_template_part()`, `get_theme_file_uri()`.
15. **Self-verify before returning** — compare your output against the Figma screenshot.
