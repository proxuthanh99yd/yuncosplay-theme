## Plan: Related Products Section

**Figma:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=473-5761&m=dev
**Scope:** Single Section
**Design:** 1600×956px | rem divisor: 16 | Viewport: desktop
**Layout type:** contained (padding 68px left/right)

**Screenshot:**

![Related Products Section](https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/8cdf43c6-51fb-4f1a-be67-9646f9280f0b)

---

### Component Breakdown

| # | Component | Node ID | Description | Repeated |
|---|-----------|---------|-------------|----------|
| 1 | related-products (root) | 473:5761 | Section wrapper, vertical layout, padding 120px top/bottom | — |
| 2 | Header | 473:5502 | Section title "sản phẩm liên quan", padding L/R 68px | — |
| 3 | Related Products List | 473:5760 | Horizontal layout, 4 product cards + pagination, padding L/R 68px, gap 40px | — |
| 4 | Product card | 473:5676 | Existing component `template-parts/components/product/` — reuse | ×4 |
| 5 | Pagination Controls | 473:5770 | Left/right arrow buttons, space-between layout, centered vertically | — |
| 6 | Arrow button (prev) | 473:5771 | 40×40px white circle, left arrow icon #680103, box-shadow | — |
| 7 | Arrow button (next) | 473:5772 | 40×40px white circle, right arrow icon #680103, box-shadow | — |

### Layout Details

**Root (473:5761):**
- Direction: vertical
- Padding: 120px top/bottom, 0 left/right
- Gap: 48px between header and product list
- Background: transparent

**Header (473:5502):**
- Direction: vertical
- Padding: 0 top/bottom, 68px left/right
- Width: fill (1600px)
- Height: hug

**Products List (473:5760):**
- Direction: horizontal
- Padding: 0 top/bottom, 68px left/right
- Gap: 40px between cards
- Width: fill (1600px)
- Contains 4 product card instances (336×604px each) + pagination overlay

**Pagination Controls (473:5770):**
- Direction: horizontal
- Justify: space-between
- Align: center
- Width: 1523px (spans across cards area)
- Positioned vertically centered within the products list (absolute positioning on top of cards)

**Arrow Buttons:**
- Size: 40×40px
- Background: #ffffff
- Border-radius: 100% (circle)
- Box-shadow: 0 0 30px rgba(0,0,0,0.12)
- Icon color: #680103
- Left arrow: points left ←
- Right arrow: points right →

### Implementation Notes

- Product cards use existing `template-parts/components/product/` component — query related products via WooCommerce
- Pagination arrows should be Swiper navigation (prev/next)
- Wrap product cards in a Swiper slider for carousel behavior
- Arrow SVGs can be inline (simple arrow paths)
- The section already has skeleton files at `template-parts/single-product/related-product/` (empty index.php, empty styles.css, scripts.js)

### Typography (2 unique styles)

| # | Figma | CSS | Element |
|---|-------|-----|---------|
| 1 | Phudu 700 58px / lh 110% | `font-family: 'Phudu', sans-serif; font-size: 3.625rem; font-weight: 700; line-height: 1.1; text-transform: uppercase;` | Section title |
| 2 | — | (inherits from product card component) | Product card text |

### Colors

| Token | Value | Usage |
|-------|-------|-------|
| Primary | #680103 | Title color, arrow icon color |
| White | #ffffff | Arrow button background |
| Shadow | rgba(0,0,0,0.12) | Arrow button box-shadow |

### Assets to Download

| Type | Filename | Figma Node | Notes |
|------|----------|------------|-------|
| — | — | — | No image assets needed. Arrow icons are inline SVG. Product card images come from WooCommerce. |

### Fonts

| Family | Source | Weights | Load Method |
|--------|--------|---------|-------------|
| Phudu | Google Fonts (existing) | 700 | Already loaded |
| SF Pro Display | System/existing | 400, 500 | Already loaded |

### Asset Enqueue

| Handle | Type | File | Condition |
|--------|------|------|-----------|
| single-product | style | template-parts/single-product/assets/styles.css | Already registered — add `@import` for related-product |
| single-product | script | template-parts/single-product/assets/scripts.js | Already registered |

Need to add to `template-parts/single-product/assets/styles.css`:
```css
@import url('../related-product/assets/styles.css');
```

Swiper is already available globally (used in other sections).

### Files to Create/Update

```
template-parts/single-product/
  related-product/
    index.php                       # Section template (WC related products query + Swiper carousel)
    assets/
        styles.css                  # Section styles
        scripts.js                  # Swiper init for related products carousel
  assets/
    styles.css                      # UPDATE: add @import for related-product
```

**Total: ~3 files to write, 1 file to update**

---

Ready to build? Run: `/figma-build`
