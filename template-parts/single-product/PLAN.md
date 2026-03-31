## Plan: Single Product — Main Content

**Figma:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=514-7407&m=dev
**Scope:** Single Section
**Design:** 1600×1785px | rem divisor: 16 (html font-size: 1vw → 1vw = 16px tại 1600px) | Viewport: desktop
**Layout type:** contained (inside page container)
**Screenshot:** `/tmp/figma_514_7407_screenshot.png`

> **rem conversion:** `rem = figma_px / 16` (vì html font-size: 1vw, tại design width 1600px → 1rem = 16px)

---

### Component Breakdown

| # | Component | Node ID | Description | Repeated |
|---|-----------|---------|-------------|----------|
| 1 | Main Content (root) | 514:7407 | Two-column horizontal layout: Left Column (images) + Right Column (product info). Padding: 3rem top, 4.25rem left/right. Gap: 3.5rem. | — |
| 2 | Left Column | 375:9784 | Vertical stack of image galleries & large image. Gap: 0.25rem. Width: 49.625rem (fixed). | — |
| 3 | Image Gallery (top) | 375:9769 | Horizontal row of 2 product images. Gap: 0.25rem. Height: 38.1875rem. | x2 (top + bottom) |
| 4 | Image Container | 375:9770 | Single product image wrapper. ~24.6875rem × 38.1875rem. | x4 (across galleries) |
| 5 | Expand Icon | 406:6022 | Fullscreen/expand icon overlay on second image of top gallery. 3.25rem dark circle with arrow icon. Positioned bottom-right of image. | x1 |
| 6 | Large Image Container | 375:9782 | Full-width product image. 49.625rem × 31.6875rem. Has text overlay at bottom. | x1 |
| 7 | Large Image Description | 514:7409 | Text overlay on large image. Light text on dark image. | x1 |
| 8 | Image Gallery (bottom) | 375:9785 | Same layout as top gallery — 2 images side by side. | (see #3) |
| 9 | Right Column | 514:7406 | Product info column. Width: fill (~38.375rem). Contains sticky Container. | — |
| 10 | Container (sticky) | 394:5962 | Sticky scroll container for product info. Vertical layout, gap: 1.625rem. | — |
| 11 | Header | 389:5908 | Category tag + product title. Vertical, gap: 1.5rem. | — |
| 12 | Category Header | 394:6004 | Horizontal: category name + tag badge. Gap: 1.25rem. | — |
| 13 | Tag Badge | 544:8507 | Instance component showing stock count. Small label. | x1 |
| 14 | Price Section | 514:7318 | Vertical stack of Rental Price + Sale Price. Gap: 0.625rem. | — |
| 15 | Rental Price Section | 514:7319 | Card with label + price + divider + description. Padding: 1rem. BG: rgba(104,1,3, 0.04). | x2 (rental + sale) |
| 16 | CTA | 754:8283 | Instance: Button + Contact section (Zalo + Messenger icons). Horizontal, space-between. | x1 |
| 17 | Separator Line | 514:7404 | 1px divider, color: rgba(29,29,29, 0.2). Full width. | x1 |
| 18 | Description Section | 394:5995 | Heading + description content. Vertical, gap: 1rem. | — |
| 19 | Description Content | 406:6011 | Multiple text paragraphs. Vertical, gap: 0.625rem. | — |

---

### Color Tokens

| Token | Value | Usage |
|-------|-------|-------|
| Primary (dark red) | `#680103` / `rgb(104, 1, 3)` | Headings, prices, labels, buttons, links |
| Body text | `rgba(28, 28, 28, 0.8)` | Description text, included items |
| Light text (on dark) | `#F7F4EC` / `rgb(247, 244, 236)` | Image overlay text |
| CTA / Icon BG | `#F6F3EA` / `rgb(246, 243, 234)` | Button background, icon circles |
| Price card BG | `rgba(104, 1, 3, 0.04)` | Rental/sale price section background |
| Divider | `rgba(29, 29, 29, 0.2)` | Separator lines |
| Expand icon BG | `#1D1D1D` / `rgb(29, 29, 29)` | Dark circle for expand icon |

---

### Typography (9 unique styles)

| # | Figma | CSS (rem = px/16) | Used in |
|---|-------|-------------------|---------|
| 1 | Phudu 46px / w700 / lh1.2 | `font-family: 'Phudu', sans-serif; font-size: 2.875rem; font-weight: 700; line-height: 1.2;` | Product title |
| 2 | Phudu 24px / w700 / lh1.1 | `font-family: 'Phudu', sans-serif; font-size: 1.5rem; font-weight: 700; line-height: 1.1;` | Description heading |
| 3 | Phudu 24px / w600 / lh1.1 | `font-family: 'Phudu', sans-serif; font-size: 1.5rem; font-weight: 600; line-height: 1.1;` | Category name, image overlay text |
| 4 | Phudu 22px / w700 / lh1.3 | `font-family: 'Phudu', sans-serif; font-size: 1.375rem; font-weight: 700; line-height: 1.3;` | Price values |
| 5 | SF Pro Display 18px / w600 / lh1.5 | `font-family: 'SF Pro Display', sans-serif; font-size: 1.125rem; font-weight: 600; line-height: 1.5;` | Price labels, CTA button text |
| 6 | SF Pro Display 16px / w500 / lh1.5 | `font-family: 'SF Pro Display', sans-serif; font-size: 1rem; font-weight: 500; line-height: 1.5;` | Contact label |
| 7 | SF Pro Display 16px / w400 / lh1.5 | `font-family: 'SF Pro Display', sans-serif; font-size: 1rem; font-weight: 400; line-height: 1.5;` | Description paragraphs, included items |
| 8 | SF Pro Display 14px / w500 / lh1.5 | `font-family: 'SF Pro Display', sans-serif; font-size: 0.875rem; font-weight: 500; line-height: 1.5;` | Tag badge text |
| 9 | SF Pro Display 14px / w400 / lh1.5 | `font-family: 'SF Pro Display', sans-serif; font-size: 0.875rem; font-weight: 400; line-height: 1.5;` | Phone number |

---

### Assets to Download

| Type | Filename | Figma Node | Notes |
|------|----------|------------|-------|
| icon | ic-expand.svg | 406:6019 | Expand/fullscreen arrow icon (inside dark circle) |
| icon | ic-arrow-up-right.svg | I754:8283;514:7382 | Arrow icon in CTA button |
| icon | ic-zalo.svg | I754:8283;514:7390 + I754:8283;514:7391 | Zalo logo + status dot |
| icon | ic-messenger.svg | I754:8283;514:7396 | Messenger icon |

> **Note:** Product images are dynamic (from WooCommerce), no need to export them. Only icons need to be downloaded.

---

### Fonts

| Family | Source | Weights | Load Method |
|--------|--------|---------|-------------|
| Phudu | Google Fonts (existing) | 600, 700 | `wp_enqueue_style` — already loaded |
| SF Pro Display | Local fonts (existing) | 400, 500, 600, 700 | `@font-face` in `assets/fonts/stylesheet.css` — already loaded |

No new fonts needed.

---

### Asset Enqueue

| Handle | Type | File | Condition |
|--------|------|------|-----------|
| single-product | style | `template-parts/single-product/assets/styles.css` | `is_singular('product')` |
| single-product | script | `template-parts/single-product/assets/scripts.js` | `is_singular('product')` |

> **Note:** The page-level `styles.css` already imports `product-detail/assets/styles.css`. Need to add enqueue entries in `import-css-js.php` (currently missing).

---

### Layout Specs (all values in rem, 1rem = 16px = 1vw at 1600px)

```
Main Content (100rem × 111.5625rem)
├── padding: 3rem 4.25rem 0 4.25rem
├── display: flex (horizontal)
├── gap: 3.5rem
│
├── Left Column (49.625rem fixed width)
│   ├── display: flex (vertical)
│   ├── gap: 0.25rem
│   │
│   ├── Image Gallery (49.625rem × 38.1875rem)
│   │   ├── display: flex (horizontal)
│   │   ├── gap: 0.25rem
│   │   ├── Image Container (~24.6875rem × 38.1875rem) — flex: 1
│   │   └── Image Container (~24.6875rem × 38.1875rem) — flex: 1 + expand icon overlay
│   │
│   ├── Large Image Container (49.625rem × 31.6875rem)
│   │   ├── position: relative
│   │   └── Text overlay (bottom-left, light text, Phudu 1.5rem/600)
│   │
│   └── Image Gallery (same as first)
│
└── Right Column (flex: 1, ~38.375rem)
    └── Container (sticky)
        ├── display: flex (vertical)
        ├── gap: 1.625rem
        ├── position: sticky; top: ...
        │
        ├── Header (gap: 1.5rem vertical)
        │   ├── Category Header (horizontal, gap: 1.25rem)
        │   │   ├── Category name (Phudu 1.5rem/600, #680103)
        │   │   └── Tag badge (SF Pro 0.875rem/500, #680103)
        │   └── Title (Phudu 2.875rem/700, #680103)
        │
        ├── Price Section (vertical, gap: 0.625rem)
        │   ├── Rental Price Card (padding: 1rem, bg: rgba(104,1,3,0.04))
        │   │   ├── Row: label (SF Pro 1.125rem/600) + price (Phudu 1.375rem/700)
        │   │   ├── Divider (1px, rgba(29,29,29,0.2))
        │   │   └── Description (SF Pro 1rem/400, rgba(28,28,28,0.8))
        │   └── Sale Price Card (same layout)
        │
        ├── CTA (horizontal, space-between, align-center)
        │   ├── Button (bg: #F6F3EA, radius: 0.375rem, padding: 0.5rem 1.9375rem)
        │   │   ├── Text (SF Pro 1.125rem/600) + arrow icon (2.5rem)
        │   │   └── Phone (SF Pro 0.875rem/400)
        │   └── Contact (horizontal, gap: 0.8125rem)
        │       ├── Label (SF Pro 1rem/500, #F7F4EC)
        │       └── Icons (horizontal)
        │           ├── Zalo (3.5rem circle, bg: #F6F3EA)
        │           └── Messenger (3.5rem circle, bg: #F6F3EA)
        │
        ├── Separator Line (1px, rgba(29,29,29,0.2))
        │
        └── Description Section (vertical, gap: 1rem)
            ├── Heading (Phudu 1.5rem/700, #680103)
            └── Content (vertical, gap: 0.625rem)
                └── Paragraphs (SF Pro 1rem/400, rgba(28,28,28,0.8))
```

---

### Files to Create/Edit

```
template-parts/single-product/
├── index.php                              # (exists) Section orchestrator
├── assets/
│   ├── styles.css                         # (exists) Page-level styles — update imports
│   └── scripts.js                         # (exists) Page-level scripts
├── product-detail/
│   ├── index.php                          # (exists, placeholder) → REWRITE: Full product template
│   └── assets/
│       ├── styles.css                     # (exists, empty) → WRITE: All product detail styles
│       └── scripts.js                     # (exists, empty) → WRITE: Image gallery interaction (Fancybox/lightbox)
└── PLAN.md                                # This file
```

**Also edit:**
- `import-assets/import-css-js.php` — add single-product style/script enqueue
- `import-assets/import-css-js.php` — add `single-product` to `$module_handles` array for `type="module"`

**Total: ~3 files to write, 2 files to edit**

---

### Implementation Notes

1. **Product images are dynamic** — from WooCommerce product gallery (`$product->get_gallery_image_ids()`). The layout shows 2+1+2 images but should handle variable image counts.
2. **Sticky right column** — the Container has `STICKY_SCROLLS` behavior, implement with `position: sticky`.
3. **CTA is a Figma instance** (component 754:8282) — but simple enough to implement inline rather than as a separate template part.
4. **Tag badge** shows remaining stock — use `$product->get_stock_quantity()`.
5. **Price fields** — uses custom fields: rental price (`_sale_price_custom` or custom meta) and sale price.
6. **Image expand icon** — triggers Fancybox/lightbox for full-screen image viewing.
7. **Description** — from `$product->get_description()` or custom field, rendered as WP content.

---

Ready to build? Run: `/figma-build`
