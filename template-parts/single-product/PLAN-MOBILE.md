## Plan: Single Product — Mobile Responsive

**Figma:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=2362-10781&m=dev
**Scope:** Single Section (mobile responsive for existing product-detail)
**Design:** 375×1659px | rem divisor: 16 (mobile: `--root-font-size: 4.267vw` → 1rem = 16px at 375px) | Viewport: mobile
**Layout type:** full-width, single column
**Screenshot:** `/tmp/figma_2362_10781_screenshot.png`
**Note:** Responsive cho mobile — gallery ảnh tự động chạy như Story FB

> **rem conversion:** `rem = figma_px / 16` (same divisor as desktop since `4.267vw × 375 = 16px`)

> **Breakpoint:** `@media screen and (max-width: 639.98px)` (matches global.css pattern)

---

### Mobile Layout Overview

Mobile converts the desktop 2-column layout into a single-column flow:

1. **Story Gallery** (fullscreen-like image viewer with auto-play)
2. **Header** (category + title + prices — no CTA inline)
3. **Description Section** (separator + description)
4. **Sticky Bottom Bar** (fixed at bottom with prices + CTA)
5. **Floating Side Icons** (Zalo, Messenger, Phone — fixed right side)

---

### Component Breakdown

| # | Component | Node ID | Description | Notes |
|---|-----------|---------|-------------|-------|
| 1 | Story Gallery (root) | 2362:10511 | Vertical: large image + thumbnail strip. 377×656px. Gap: 4px. | NEW mobile-only section |
| 2 | Image Container | 2362:10512 | Full-width image display. 377×581px. Clips content. | Swipeable/auto-play |
| 3 | Product Image | 2362:10513 | The current active image. Object-fit: cover. | Dynamic from WooCommerce |
| 4 | Gradient Overlay (top) | 2362:10514 | 377×76px gradient at top. From transparent to dark. | For progress bars readability |
| 5 | Story Progress Bars | 2362:10515 | Horizontal row of bars (1 per image). 375×30px. Gap: 3px. Padding: 16px 12px 12px 12px. | White bars, active = filled, animating progress |
| 6 | Progress Bar | 2362:10516-19 | Individual bar: 85.5×2px. White fill. Flex: 1. | ×N (one per image) |
| 7 | Thumbnail Strip | 2362:10520 | Horizontal scroll of square thumbs. 377×71px. Gap: 4px. Padding: 0 4px. | Scrollable |
| 8 | Thumbnail | 2362:10521-25 | Square thumbnail: 71×71px. Active = red border. | ×N (one per image) |
| 9 | Header | 2362:10526 | Product info. 375px. Padding: 32px 12px 0 12px. Gap: 10px. | Reuses desktop data but new layout |
| 10 | Category Header | 2362:10527 | Horizontal: category name + tag. Gap: 9px. | Same as desktop but smaller font |
| 11 | Title | 2362:10530 | Phudu 28px/700. Color: #680103. | Smaller than desktop (46→28) |
| 12 | Price Section | 2362:10531 | Two price cards. Gap: 12px. | Same structure as desktop, smaller fonts |
| 13 | Price Card | 2362:10532 | Label + price + divider + description. Gap: 12px. Padding: 12px. | |
| 14 | Description Section | 2362:10544 | Separator + heading + paragraphs. Padding: 32px 12px 0 12px. Gap: 24px. | |
| 15 | Description Content | 2362:10548 | Text paragraphs. Gap: 10px. | |
| 16 | Sticky Bottom Bar | 2362:10567 | Fixed at bottom. 375×64px. White bg. 3 columns: rent price / sale price / CTA button. | `position: fixed; bottom: 0` |
| 17 | Rent Price Column | 2362:10568 | 113×64px. Center aligned. White bg. Label 12px/400 + price 16px/700. | |
| 18 | Sale Price Column | 2362:10572 | 113×64px. Same layout as rent. | |
| 19 | CTA Column | 2362:10576 | 149×64px. Gradient bg (radial: #CB5140 → #D8A061). Text: white. | "Thuê đồ ngay" + phone number |
| 20 | Floating Side Icons | 2362:10580 | Fixed right side. Vertical stack. Gap: 16px. Padding: 23px 12px 80px 12px. | Zalo + Messenger + Phone |
| 21 | Side Icon | 2362:10581 | 36×36px circle. White bg (10% opacity). | ×3 |

---

### Color Tokens (Mobile-specific)

| Token | Value | Usage |
|-------|-------|-------|
| Primary (dark red) | `#680103` | Headings, prices, labels |
| Body text | `rgba(28, 28, 28, 0.8)` | Description text, sticky bar labels |
| Sticky bar CTA bg | `radial-gradient(circle at center, #CB5140 47%, #D8A061 91%)` | CTA button in sticky bar |
| Sticky bar CTA text | `#F7F4EC` | CTA button text |
| Sticky bar bg | `#FFFFFF` | Bottom bar background |
| Story bar | `#FFFFFF` | Progress bars (full opacity = active, ~30% = inactive) |
| Gradient overlay | `linear-gradient(to bottom, transparent 21%, rgba(44,0,1,0.51) 48%, rgba(44,0,1,0.80) 72%, rgba(44,0,1,1) 100%)` | Top gradient on story image |
| Thumbnail active border | `#680103` | Active thumbnail border |
| Side icon bg | `rgba(246, 243, 234, 0.1)` | Floating icon circle background |

---

### Typography (Mobile — 8 unique styles)

| # | Figma | CSS (rem = px/16) | Used in |
|---|-------|-------------------|---------|
| 1 | Phudu 28px / w700 / lh~1.1 | `font-family: 'Phudu'; font-size: 1.75rem; font-weight: 700; line-height: 1.1;` | Product title (mobile) |
| 2 | Phudu 18px / w700 / lh~1.2 | `font-family: 'Phudu'; font-size: 1.125rem; font-weight: 700; line-height: 1.2;` | Price values, description heading |
| 3 | Phudu 13px / w600 / lh~1.1 | `font-family: 'Phudu'; font-size: 0.8125rem; font-weight: 600; line-height: 1.1;` | Category name |
| 4 | SF Pro Display 16px / w700 / lh1.5 | `font-family: 'SF Pro Display'; font-size: 1rem; font-weight: 700; line-height: 1.5;` | Price labels, sticky bar prices |
| 5 | SF Pro Display 14px / w700 / lh1.5 | `font-family: 'SF Pro Display'; font-size: 0.875rem; font-weight: 700; line-height: 1.5;` | Sticky CTA text |
| 6 | SF Pro Display 14px / w400 / lh1.5 | `font-family: 'SF Pro Display'; font-size: 0.875rem; font-weight: 400; line-height: 1.5;` | Description text, included items |
| 7 | SF Pro Display 12px / w500 / lh1.5 | `font-family: 'SF Pro Display'; font-size: 0.75rem; font-weight: 500; line-height: 1.5;` | Tag badge |
| 8 | SF Pro Display 12px / w400 / lh1.5 | `font-family: 'SF Pro Display'; font-size: 0.75rem; font-weight: 400; line-height: 1.5;` | Sticky bar labels, CTA phone |

---

### Assets to Download

| Type | Filename | Figma Node | Notes |
|------|----------|------------|-------|
| icon | ic-phone.svg | 2362:10592 | Phone/call icon for floating side icon (can't export — create manually) |

> All other icons reuse desktop icons: ic-zalo.svg, ic-messenger.svg, ic-arrow-up-right.svg
> Product images are dynamic (from WooCommerce)

---

### Fonts

No new fonts needed — same as desktop (Phudu + SF Pro Display already loaded).

---

### Implementation Approach

This is a **responsive update** to the existing `product-detail` section. Changes are:

1. **CSS only** — add `@media screen and (max-width: 639.98px)` block to `product-detail/assets/styles.css`
2. **PHP template update** — add mobile-only HTML (story gallery, sticky bar, side icons) with conditional rendering
3. **JS update** — add story gallery auto-play logic (story timer + swipe + thumbnail click)

---

### Story Gallery Behavior (JS)

The gallery should work like FB/IG Stories:
- **Auto-play**: advance to next image every 5 seconds
- **Progress bars**: N bars at top, one per image. Active bar fills left-to-right over 5s.
- **Tap to navigate**: tap left half → previous image, tap right half → next image
- **Thumbnail click**: jump to that image
- **Active thumbnail**: highlighted with red border, auto-scrolls into view
- **Swipe**: swipe left → next, swipe right → previous
- **Pause on touch/hold**: touching pauses the timer, releasing resumes

---

### Layout Specs — Mobile (all values in rem, 1rem = 16px at 375px)

```
Mobile Single Product (23.4375rem × full height)
│
├── Story Gallery Section (mobile-only, replaces desktop gallery)
│   ├── display: flex (vertical)
│   ├── gap: 0.25rem
│   │
│   ├── Image Container (23.5625rem × 36.3125rem)
│   │   ├── position: relative
│   │   ├── overflow: hidden
│   │   ├── Gradient Overlay (top, 23.5625rem × 4.75rem)
│   │   │   └── gradient: transparent → rgba(44,0,1,1)
│   │   ├── Progress Bars (23.4375rem × 1.875rem)
│   │   │   ├── position: absolute; top: 0; z-index: 2
│   │   │   ├── display: flex; gap: 0.1875rem
│   │   │   ├── padding: 1rem 0.75rem 0.75rem
│   │   │   └── Each bar: flex: 1; height: 0.125rem; bg: rgba(255,255,255,0.3)
│   │   │       └── Active fill: bg: white (animated width 0→100% over 5s)
│   │   └── Product Image (fills container, object-fit: cover)
│   │
│   └── Thumbnail Strip (23.5625rem × 4.4375rem)
│       ├── display: flex; gap: 0.25rem
│       ├── padding: 0 0.25rem
│       ├── overflow-x: auto; scroll-snap-type: x mandatory
│       └── Each thumb: 4.4375rem × 4.4375rem; flex-shrink: 0
│           └── Active: border: 2px solid #680103
│
├── Header (23.4375rem)
│   ├── padding: 2rem 0.75rem 0
│   ├── display: flex (vertical); gap: 0.625rem
│   │
│   ├── Category Row (horizontal, gap: 0.5625rem)
│   │   ├── Category (Phudu 0.8125rem/600, #680103)
│   │   └── Tag Badge (SF Pro 0.75rem/500, #680103)
│   │
│   ├── Title (Phudu 1.75rem/700, #680103)
│   │
│   └── Price Section (vertical, gap: 0.75rem)
│       ├── Rent Price Card (padding: 0.75rem, bg: rgba(104,1,3,0.04))
│       │   ├── Row: label (SF Pro 1rem/700) + price (Phudu 1.125rem/700)
│       │   ├── Divider (1px, rgba(29,29,29,0.2))
│       │   └── Description (SF Pro 0.875rem/400, rgba(28,28,28,0.8))
│       └── Sale Price Card (same)
│
├── Description Section (23.4375rem)
│   ├── padding: 2rem 0.75rem 0
│   ├── gap: 1.5rem
│   ├── Separator Line (1px, rgba(29,29,29,0.2))
│   ├── Heading (Phudu 1.125rem/700, #680103)
│   └── Content (gap: 0.625rem)
│       └── Paragraphs (SF Pro 0.875rem/400, rgba(28,28,28,0.8))
│
├── Bottom Spacer (4rem — space for sticky bar)
│
├── Sticky Bottom Bar (position: fixed; bottom: 0; width: 100%)
│   ├── height: 4rem; bg: #FFF; z-index: 100
│   ├── display: flex (horizontal, 3 equal columns)
│   │
│   ├── Rent Column (flex: 1, center, bg: #FFF)
│   │   ├── Label (SF Pro 0.75rem/400, #1c1c1c)
│   │   └── Price (SF Pro 1rem/700, #680103)
│   │
│   ├── Sale Column (flex: 1, center, bg: #FFF)
│   │   ├── Label (SF Pro 0.75rem/400, #1c1c1c)
│   │   └── Price (SF Pro 1rem/700, #680103)
│   │
│   └── CTA Column (flex: ~1.3, center)
│       ├── bg: radial-gradient(circle, #CB5140 47%, #D8A061 91%)
│       ├── "Thuê đồ ngay" (SF Pro 0.875rem/700, #F7F4EC)
│       └── "Gọi: (+84) 79 409 888" (SF Pro 0.75rem/400, #F7F4EC)
│
└── Floating Side Icons (position: fixed; right: 0.75rem; top: 50%)
    ├── display: flex (vertical); gap: 1rem
    ├── z-index: 99
    ├── Zalo (2.25rem circle, bg: rgba(246,243,234,0.1))
    ├── Messenger (2.25rem circle)
    └── Phone (2.25rem circle)
```

---

### Files to Create/Edit

```
template-parts/single-product/
├── product-detail/
│   ├── index.php                  # EDIT: add mobile story gallery HTML + sticky bar + side icons
│   └── assets/
│       ├── styles.css             # EDIT: add @media mobile block
│       └── scripts.js             # EDIT: add story gallery auto-play JS
└── assets/
    └── styles.css                 # (no change — already imports product-detail)
```

**Also create:**
- `assets/images/single-product/ic-phone.svg` — phone icon SVG for side floating button

**Total: 3 files to edit, 1 new SVG**

---

### Implementation Notes

1. **Story Gallery is mobile-only** — hidden on desktop. The desktop gallery (2-column grid) is hidden on mobile.
2. **Sticky Bottom Bar** — replaces the inline CTA on desktop. Uses `position: fixed; bottom: 0`.
3. **Floating Side Icons** — replace the inline contact icons. Uses `position: fixed; right: 0.75rem`.
4. **Gallery auto-play** — 5s per image, pause on touch, progress bar animation via CSS transition.
5. **Thumbnail scroll** — horizontal scroll with snap, active thumbnail auto-scrolls into view.
6. **Bottom spacer** — add `padding-bottom: 4rem` to body/section on mobile to prevent sticky bar overlap.
7. **Desktop CTA and contact section** — hide on mobile (replaced by sticky bar + side icons).

---

Ready to build? Run: `/figma-build`
