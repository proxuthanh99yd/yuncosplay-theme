## Plan: Product Listing Page (Danh sach san pham)

**Figma:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=2406-11272&m=dev
**Scope:** Single Section
**Design:** 1600x1910px | rem divisor: 16 (1vw at 1600px) | Viewport: desktop
**Layout type:** contained (max-width: 75rem / 1200px equivalent at 1920px)
**Screenshot:**

![Product Listing](https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/892a1c08-8d41-4b0d-9b01-4b69b63558b5)

---

### Component Breakdown

| # | Component | Node ID | Description | Repeated |
|---|-----------|---------|-------------|----------|
| 1 | Breadcrumb bar (Subtitle) | 2406:10688 | Top breadcrumb: "Trang chu > Danh sach san pham". Full-width 1600px, padding 68px L/R, 24px T/B. | - |
| 2 | Main Container (root) | 2406:10690 | Horizontal layout: sidebar (left) + main content (right), gap 40px (2.5rem). 1600px wide. | - |
| 3 | Sidebar Filter (Loc PC) | 2406:10694 | Sticky sidebar filter panel, 336px wide. White bg 80% opacity. Contains: price range slider, category grid with circular thumbnails, country/origin filter chips with checkboxes. | - |
| 4 | Content Header | 2406:10696 | Title "Danh sach san pham" + category name "Nhat ban" + long description text + expand/collapse button. White bg 80% opacity, padding 32/40/32/32. | - |
| 5 | Product Grid | 2406:10707, 2406:10712, 2406:10717 | 4-column grid of product cards, gap 20px (1.25rem). 3 rows visible = 12 cards total. Each row is 1156px wide. | x3 rows |
| 6 | Product Card (Card san pham) | 2406:10708 (etc.) | Reuse existing `template-parts/components/product/` with `--small` variant. 257px wide, 462px tall. | x12 |

---

### Sub-component Details

#### 3a. Sidebar — Price Range Filter
- Label: "KHOANG GIA" with filter icon (18x18), SF Pro Display 14px w500 opacity 40%
- Dual-handle range slider (noUiSlider already in theme)
  - Track: 280px, bg #f4f4f4, active fill #680103
  - Handles: 16px circle, outer ring rgba(#680103, 0.04), inner 12px solid #680103
  - Labels: "100.000d" — "10.000.000d", SF Pro Display 12px w400
- Separator line: 1px, #1c1c1c opacity 12%

#### 3b. Sidebar — Category Section
- Header row: shopping-cart icon + "CHON DANH MUC" (14px w500 op40%) + "Xoa lua chon" button (pill, bg #680103, text #f7f4ec 14px w400, border-radius 96px)
- Category Cards Grid: 3 columns, gap 14px
  - Each card: 95px wide, vertical layout
  - Circular thumbnail: 64x64px (ellipse with image fill)
  - Title: SF Pro Display 10px w600 lh150%, color #1c1c1c op80%, text-align center, uppercase
  - Categories: "TAT CA SAN PHAM", "GIAI TRI - HAI HUOC", "NHAN VAT FANTASY", "THAN THOAI, CO TICH", etc.
  - Triangle indicator (28x14) below active row, fill rgba(#680103, 0.04) — points down to expanded filter
- Expanded Filter Panel: bg rgba(#680103, 0.04), padding 18px, border-radius (implied), vertical gap 12px
  - Filter rows (276px): pairs of pill chips, gap 8px
  - Active chip: white bg, checkbox checked (fill #cb5140 + checkmark), text #680103 14px w400
  - Inactive chip: white bg, checkbox unchecked (fill #1c1c1c), text #1c1c1c 14px w400
  - Chip structure: checkbox (40x40 hit area, 18x18 visual) + text label, pill radius 96px

#### 4. Content Header
- Title: "Danh sach san pham" — Phudu 46px w700 lh120%, color #680103
- Divider: 1px line, full-width (1024px), stroke #1c1c1c
- Category subtitle: "Nhat ban" — Phudu 30px w700 lh120%, color #680103
- Description: SF Pro Display 16px w400 lh150%, color #1c1c1c op80%, max ~5 lines with overflow hidden
- Expand button: 46x46px, centered icon 18x18 (arrow-down/chevron), fill #cb5140

#### 5. Product Grid
- Layout: 4 columns, horizontal gap 20px (1.25rem)
- Row spacing: 32px (2rem) vertical gap between rows
- Uses existing product card component (small variant: 257x462px at this design)

---

### Common Components (shared/existing)

| Component | Used in | Source |
|-----------|---------|--------|
| product card | Product Grid | `template-parts/components/product/` (existing, use `--small` variant) |
| animated-button | (not used here) | `template-parts/components/animated-button/` (existing) |
| noUiSlider | Price Range Filter | Already enqueued in theme (external lib) |

### New Sub-components to Create

| Component | Description |
|-----------|-------------|
| sidebar-filter | Sidebar filter panel with price range + category grid + origin chips |
| category-card | Circular thumbnail + title card (used 9+ times in sidebar) |
| filter-chip | Pill-shaped checkbox + label (used 8+ times in filter panel) |

---

### Typography (8 unique styles)

| # | Figma | CSS | Element |
|---|-------|-----|---------|
| 1 | Phudu 46px / w700 / lh120% | `font-family: 'Phudu'; font-size: 2.875rem; font-weight: 700; line-height: 120%;` | Main title "Danh sach san pham" |
| 2 | Phudu 30px / w700 / lh120% | `font-family: 'Phudu'; font-size: 1.875rem; font-weight: 700; line-height: 120%;` | Category subtitle "Nhat ban" |
| 3 | Phudu 18px / w600 / lh120% | `font-family: 'Phudu'; font-size: 1.125rem; font-weight: 600; line-height: 120%;` | Product card title (existing) |
| 4 | Phudu 18px / w700 / lh130% | `font-family: 'Phudu'; font-size: 1.125rem; font-weight: 700; line-height: 130%;` | Rental price (existing) |
| 5 | SF Pro Display 16px / w400 / lh150% | `font-family: 'SF Pro Display'; font-size: 1rem; font-weight: 400; line-height: 150%;` | Description text |
| 6 | SF Pro Display 14px / w500 / lh150% | `font-family: 'SF Pro Display'; font-size: 0.875rem; font-weight: 500; line-height: 150%;` | Filter labels ("KHOANG GIA", "CHON DANH MUC") |
| 7 | SF Pro Display 14px / w400 / lh150% | `font-family: 'SF Pro Display'; font-size: 0.875rem; font-weight: 400; line-height: 150%;` | Breadcrumb, filter chip text, button text |
| 8 | SF Pro Display 12px / w400 / lh150% | `font-family: 'SF Pro Display'; font-size: 0.75rem; font-weight: 400; line-height: 150%;` | Price range labels, price per day |
| 9 | SF Pro Display 10px / w600 / lh150% | `font-family: 'SF Pro Display'; font-size: 0.625rem; font-weight: 600; line-height: 150%;` | Category card title |
| 10 | SF Pro Display 10px / w400 / lh150% | `font-family: 'SF Pro Display'; font-size: 0.625rem; font-weight: 400; line-height: 150%;` | Rent price label |
| 11 | SF Pro Display 12px / w500 / lh150% | `font-family: 'SF Pro Display'; font-size: 0.75rem; font-weight: 500; line-height: 150%;` | Sale price label + value |

---

### Color Palette

| Color | Hex / Value | Usage |
|-------|-------------|-------|
| Primary | `#680103` | Title, prices, active chip text, clear button bg, price slider fill |
| Primary Light | `rgba(104, 1, 3, 0.04)` | Category card bg, filter panel bg, slider handle outer |
| Accent | `#cb5140` | Checkbox checked fill, expand icon |
| Text Dark | `#1c1c1c` | Body text, inactive chip text |
| Text Muted | `rgba(28, 28, 28, 0.4)` | Filter labels, breadcrumb inactive |
| Text Semi | `rgba(29, 29, 29, 0.8)` | Description text, category titles |
| Cream | `#f7f4ec` / `#f6f3ea` | Button text on primary, product card title |
| White | `#ffffff (op 80%)` | Sidebar bg, content header bg |
| Border | `rgba(28, 28, 28, 0.12)` | Divider line |
| Track BG | `#f4f4f4` | Price slider track background |
| Yellow | `#fff589` | Product card price decoration (existing) |

---

### Assets to Download (0)

No custom images/icons needed — all content is dynamic (product images from WooCommerce, category thumbnails from WordPress). Icons (filter, shopping-cart, checkbox, expand arrow) are inline SVG vectors from the Figma component system.

### Icons to Create as Inline SVG

| Icon | Description | Source Node |
|------|-------------|-------------|
| ic-filter | Price filter icon (18x18) | I2406:10694;634:11282 |
| ic-shopping-cart | Category section icon (18x18) | I2406:10694;599:9747 |
| ic-checkbox-on | Checked checkbox (18x18, fill #cb5140) | I2406:10694;599:9760;599:8896;375:7222 |
| ic-checkbox-off | Unchecked checkbox (18x18, fill #1c1c1c) | I2406:10694;599:9763;599:8890;375:7219 |
| ic-chevron-down | Expand/collapse arrow (18x18, fill #cb5140) | I2406:10706 |
| ic-triangle-down | Category indicator triangle (28x14) | I2406:10694;599:9757 |

---

### Fonts

| Family | Source | Weights | Load Method |
|--------|--------|---------|-------------|
| Phudu | Google Fonts (existing) | 600, 700 | wp_enqueue_style (already loaded) |
| SF Pro Display | Local fonts (existing) | 400, 500, 600, 700 | @font-face (already loaded) |

---

### Asset Enqueue

| Handle | Type | File | Condition |
|--------|------|------|-----------|
| product-listing | style | template-parts/product-listing/assets/styles.css | is_page('product-listing') OR is_tax('product_cat') |
| product-listing | script | template-parts/product-listing/assets/scripts.js | is_page('product-listing') OR is_tax('product_cat') |

*Note: Condition depends on how this page is served — likely a WooCommerce product category archive or custom page template.*

---

### Files to Create

```
template-parts/product-listing/
  index.php                          # Section orchestrator (breadcrumb + main container)
  assets/
      styles.css                     # Page-level styles (@import sub-sections)
      scripts.js                     # Page-level scripts (vanilla JS for filtering, noUiSlider init)
  section-breadcrumb/
      index.php                      # Breadcrumb bar template
      assets/
          styles.css                 # Breadcrumb styles
  section-sidebar/
      index.php                      # Sidebar filter template (price range + categories + chips)
      assets/
          styles.css                 # Sidebar filter styles
          scripts.js                 # noUiSlider init, vanilla JS filter logic
  section-content/
      index.php                      # Main content area (header + product grid)
      assets/
          styles.css                 # Content header + grid layout styles
          scripts.js                 # Pagination, AJAX load more, expand/collapse description
```

**Total: ~10 files**

---

### Layout Specs (Key Measurements)

```
Root: 1600px wide

Breadcrumb (full width):
  padding: 24px 68px (1.5rem 4.25rem)

Main Container (horizontal):
  gap: 40px (2.5rem)

  Sidebar Container: 404px (25.25rem) — left-padding 68px (4.25rem)
    Sidebar Filter: 336px (21rem)
      padding: 24px 12px 20px 12px (1.5rem 0.75rem 1.25rem 0.75rem)
      section gap: 24px (1.5rem)
      bg: rgba(255,255,255,0.8)

  Main Content: 1156px (72.25rem)
    vertical gap: 32px (2rem)

    Content Header: 1088px inner (padded)
      padding: 32px 40px 32px 32px (2rem 2.5rem 2rem 2rem)
      bg: rgba(255,255,255,0.8)
      title-to-divider gap: 24px (1.5rem)

    Product Grid Rows: 1156px
      4 columns, gap 20px (1.25rem)
      Each card: 257px (16.0625rem) wide
      Row gap: 32px (2rem) — implied from vertical gap
```

---

### Interactive Behaviors

1. **Price Range Slider**: noUiSlider dual-handle, range 100,000 — 10,000,000, step TBD
2. **Category Cards**: Click to select category, shows triangle indicator + expanded sub-filter panel
3. **Filter Chips**: Toggle checkbox on click, selected = filled #cb5140, deselected = outline
4. **"Xoa lua chon" Button**: Clear all selected filters in current category
5. **Description Expand/Collapse**: Click arrow button to toggle description overflow (default collapsed ~5 lines)
6. **Product Grid**: Dynamic via vanilla JS + REST API, filtered by sidebar selections
7. **Sidebar Sticky**: Sidebar content uses `position: sticky` (Figma: STICKY_SCROLLS)
8. **Infinite Scroll**: IntersectionObserver theo doi sentinel element cuoi grid. Khi user scroll gan cuoi -> fetch trang tiep theo qua REST API (`/wp-json/wc/v3/products?page=N&per_page=12` hoac custom endpoint), append product cards vao grid. Hien thi loading spinner khi dang fetch. Dung fetch khi het san pham (response tra ve empty hoac page >= total_pages).

---

---

## Plan: Mobile Responsive

**Figma (Mobile Page):** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=662-9454&m=dev
**Figma (Mobile Filter Drawer):** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=662-9971&m=dev
**Scope:** Mobile Responsive (adding `@media` + new mobile-only components)
**Design:** 375×3480px | rem divisor: 16 | Viewport: mobile (≤ 639.98px)

**Screenshots:**

![Mobile Page](https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/e8e1923a-a903-45c8-ba28-059a2bdcc9bb)
![Mobile Filter Drawer](https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/d86fa4b2-5ffe-459c-a462-f9d744a560a4)

---

### Overview: Desktop vs Mobile Differences

| Area | Desktop | Mobile |
|------|---------|--------|
| **Sidebar** | Sticky sidebar (25.25rem, always visible) | Hidden — replaced by "Lọc sản phẩm" button → fullscreen drawer overlay |
| **Breadcrumb** | padding: 1.5rem 4.25rem | padding: 1rem 0.75rem, font-size: 0.75rem |
| **Title** | Inside `pl-content__header` (Phudu 2.875rem) | Standalone row (Phudu 1.75rem), padding 0 0.75rem |
| **Category Selector** | N/A (sidebar always visible) | Radial gradient pill button "Lọc sản phẩm" + filter icon |
| **Description box** | Inside `pl-content__header` | Separate container, bg: rgba(255,255,255,0.8), padding: 0.75rem 0.75rem 1.5rem, gradient fade on text |
| **Selected Filters** | N/A (sidebar visible) | Horizontal chip row with active filters + "Xoá tất cả lựa chọn" button |
| **Product Grid** | 4 columns, column-gap: 1.25rem, row-gap: 2rem | 2 columns, column-gap: 0.375rem, row-gap: 0.875rem |
| **Product Cards** | ~28.875rem height | ~20.25rem height |
| **Sticky Bar** | N/A | Fixed bottom bar with gradient pill "Lọc sản phẩm" — shown when original button scrolls out |
| **Filter Drawer** | N/A | Full-screen overlay (375×788px) with header + close (X), price slider, category grid (3 cols, 54×54px thumbs) |
| **Container spacing** | gap: 2.5rem between sidebar & content | vertical, gap: 1.5rem, padding-bottom: 4.25rem |

---

### Component Breakdown — Mobile-Only

#### 1. Mobile Filter Button (Category Selector)
**Figma Node:** Category Selector (351×41px)
- **Background:** Radial gradient from `#CB5140` (46.5%) → `#D8A061` (91%)
- **Border-radius:** 100px (pill)
- **Padding:** 0.625rem 1rem
- **Gap:** 0.5rem
- **Text:** "Lọc sản phẩm" — SF Pro Display 500 0.875rem, color: #F7F4EC
- **Icon:** `document-filter` (20×20) — white SVG
- **Parent wrapper:** padding: 0 0.75rem

#### 2. Mobile Filter Drawer (Overlay)
**Figma Node:** 662:9971 — "Lọc sản phẩm MB" (375×788px)
- **Container:** position: fixed, inset: 0, z-index: 1000, bg: white
- **Header (60px):**
  - White bg, border-bottom: 1px solid rgba(28,28,28,0.06)
  - Left: "Bộ lọc sản phẩm" — SF Pro Display 500 1rem, padding-left: 0.75rem
  - Right: Close (X) button — 60×60 tap area, 24×24 rotated + icon
- **Body:** padding: 1.5rem 0.75rem 1.25rem, gap: 1.5rem
  - **Price section** (same as desktop sidebar):
    - Icon + "KHOẢNG GIÁ" label (14px/500, opacity 40%)
    - noUiSlider (351px wide, same config)
    - Price labels: "100.000đ" — "10.000.000đ" (14px/400)
  - **Separator:** 1px line, color rgba(28,28,28,0.12)
  - **Category section:**
    - Header: cart icon + "CHỌN DANH MỤC" (14px/500, opacity 40%) + "Xoá lựa chọn" pill button (bg: #680103, text: #F7F4EC)
    - Category grid: 3 columns, horizontal gap: 0.875rem, vertical gap: 1.5rem
    - Category card: 107.67×73px, vertical layout, gap: 0.25rem
      - Thumbnail: **54×54px** (vs 64×64 desktop), border-radius: 50%
      - Title: SF Pro Display **700** 0.625rem, uppercase, center

#### 3. Selected Filters Chip Row ("Đã chọn")
**Figma Node:** Đã chọn (375×34px)
- **Layout:** horizontal flex, gap: 0.3125rem, padding: 0 0.75rem, overflow-x: auto
- **Filter chip (removable):**
  - bg: white, border-radius: 96px (6rem)
  - padding: 0 0.625rem left / 0.375rem right
  - gap: 0.375rem
  - Text: SF Pro Display 400 0.875rem, color: #1C1C1C
  - Close icon: `close-circle` 20×20 SVG
- **Clear all chip:**
  - bg: #680103, border-radius: 96px
  - padding: 0 0.625rem
  - Text: SF Pro Display 400 0.875rem, color: #F7F4EC

#### 4. Sticky Bottom Bar ("Ghim")
**Figma Node:** Ghim > Category Container (375×41px visible + padding)
- **Position:** fixed, bottom: 0, left: 0, right: 0, z-index: 999
- **Container:** padding: 0 0.75rem 0.75rem
- **Content:** Same gradient pill button "Lọc sản phẩm" as section 1
- **Behavior:** Hidden by default. Show via IntersectionObserver when original Category Selector scrolls out of viewport

---

### Typography — Mobile-Specific

| # | Element | Figma | CSS |
|---|---------|-------|-----|
| 1 | Page title | 28px / 700 / 1.1 | `font: 700 1.75rem/1.1 'Phudu'; color: #680103;` |
| 2 | Breadcrumb | 12px / 400 / 1.5 | `font: 400 0.75rem/1.5 'SF Pro Display'; color: rgba(28,28,28,0.4);` |
| 3 | Filter btn text | 14px / 500 / 1.5 | `font: 500 0.875rem/1.5 'SF Pro Display'; color: #F7F4EC;` |
| 4 | Drawer title | 16px / 500 / 1.3 | `font: 500 1rem/1.3 'SF Pro Display';` |
| 5 | Section label | 14px / 500 / 1.5 | `font: 500 0.875rem/1.5 'SF Pro Display'; color: rgba(28,28,28,0.4); text-transform: uppercase;` |
| 6 | Category name | 10px / 700 / 1.5 | `font: 700 0.625rem/1.5 'SF Pro Display'; text-transform: uppercase;` |
| 7 | Chip text | 14px / 400 / 1.5 | `font: 400 0.875rem/1.5 'SF Pro Display';` |
| 8 | Description title | 18px / 700 / 1.2 | `font: 700 1.125rem/1.2 'Phudu'; color: #680103;` |
| 9 | Description text | 14px / 400 / 1.5 | `font: 400 0.875rem/1.5 'SF Pro Display'; color: rgba(28,28,28,0.8);` |
| 10 | Load text | 16px / 600 / 1.2 | `font: 600 1rem/1.2 'Phudu'; color: rgba(28,28,28,0.8);` |
| 11 | Price labels | 14px / 400 / 1.5 | `font: 400 0.875rem/1.5 'SF Pro Display';` |

---

### Mobile Layout Specs

```
Root: 375px (100vw)

Breadcrumb:
  padding: 1rem 0.75rem 2rem 0.75rem

Main Container (vertical):
  gap: 1.5rem
  padding-bottom: 4.25rem

  Title Row:
    padding: 0 0.75rem
    text: Phudu 700 1.75rem

  Category Selector:
    padding: 0 0.75rem
    pill: 351px wide, radial gradient, radius 100px

  Content Container:
    padding: 0 0.75rem (outer) → inner 0.75rem 0.75rem 1.5rem
    bg: rgba(255,255,255,0.8)

  Selected Filters:
    padding: 0 0.75rem, horizontal scroll
    chip gap: 0.3125rem

  Product Grid:
    padding: 0 0.75rem
    2 columns, col-gap: 0.375rem, row-gap: 0.875rem
    card: ~172.5px wide × 324px tall

  Load More:
    flex row, gap: 1rem
    spinner: 30×30, text: Phudu 600 1rem

Filter Drawer (overlay):
  position: fixed, inset: 0
  header: 60px
  body: padding 1.5rem 0.75rem 1.25rem
    price slider: 351px
    category grid: 3 cols, gap 0.875rem / 1.5rem
    card thumb: 54×54px
```

---

### Implementation Plan

#### Phase 1: PHP Templates

**1A. `section-content/index.php`** — Add mobile-only elements:
- Insert "Category Selector" gradient pill button (mobile-only, hidden on desktop)
- Insert "Đã chọn" selected filters chip row (mobile-only)

**1B. `section-sidebar/index.php`** — Wrap in drawer for mobile:
- Add `<div class="pl-filter-drawer">` overlay wrapper around existing sidebar content
- Drawer header: "Bộ lọc sản phẩm" title + close (X) button
- On desktop: drawer wrapper is transparent, sidebar renders normally
- On mobile: sidebar hidden, drawer is shown on button click

**1C. `index.php`** — Add sticky bar:
- Add `<div class="pl-sticky-bar">` after `.pl-main`
- Contains same gradient pill button

#### Phase 2: CSS (Mobile Media Queries)

**2A. `assets/styles.css` (main):**
```css
@media (max-width: 639.98px) {
  .pl-main { flex-direction: column; gap: 1rem; padding-right: 0; }
  .pl-sticky-bar { position: fixed; bottom: 0; ... }
  .pl-filter-btn { gradient pill styles ... }
  .pl-selected-filters { horizontal chip row ... }
}
```

**2B. `section-sidebar/assets/styles.css`:**
```css
@media (max-width: 639.98px) {
  .pl-sidebar { display: none; }
  .pl-filter-drawer { position: fixed; inset: 0; ... }
  .pl-sidebar__category-thumb { width: 3.375rem; height: 3.375rem; }
}
```

**2C. `section-content/assets/styles.css`:**
Already has mobile breakpoint — extend with:
- Title: 1.75rem
- Grid: 2 columns, 0.375rem / 0.875rem gap
- Product card height adjustments

**2D. `section-breadcrumb/assets/styles.css`:**
```css
@media (max-width: 639.98px) {
  .pl-breadcrumb { padding: 1rem 0.75rem; }
}
```

#### Phase 3: JavaScript

**3A. `section-sidebar/assets/scripts.js`:**
- Drawer open/close toggle
- `data-lenis-prevent` on drawer body for native scroll
- Body scroll lock via Lenis `.stop()` / `.start()`

**3B. `assets/scripts.js` (main):**
- IntersectionObserver on `.pl-filter-btn` → toggle `.pl-sticky-bar`
- Selected filter chips render/remove logic

---

### Assets to Download

| Type | Filename | Description |
|------|----------|-------------|
| icon | ic-document-filter.svg | Filter icon for gradient pill button (inline SVG, white) |
| icon | ic-close-x.svg | Close X icon for drawer header (inline SVG, 24×24) |
| icon | ic-close-circle.svg | Circle X for chip remove button (inline SVG, 20×20) |

*All icons are inline SVG — no files to download.*

---

### Fonts

| Family | Source | Weights | Status |
|--------|--------|---------|--------|
| Phudu | Google Fonts | 600, 700 | Already loaded |
| SF Pro Display | Local | 400, 500, 700 | Already loaded |

---

### Files to Modify (no new files)

```
template-parts/product-listing/
├── index.php                        ← Add sticky bar HTML
├── assets/
│   ├── styles.css                   ← Mobile overrides + sticky bar + filter button
│   └── scripts.js                   ← Sticky bar observer + drawer toggle
├── section-breadcrumb/
│   └── assets/styles.css            ← Mobile padding/font
├── section-sidebar/
│   ├── index.php                    ← Wrap in drawer overlay
│   └── assets/
│       ├── styles.css               ← Drawer styles + hide sidebar on mobile
│       └── scripts.js               ← Drawer open/close + scroll lock
└── section-content/
    ├── index.php                    ← Add filter button + selected chips
    └── assets/styles.css            ← Grid 2-col + card sizes
```

**Total: 0 new files, 8 files modified**

---

Ready to build? Run: `/figma-build`
