## Plan: Related Products Section — Mobile Responsive

**Figma:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=662-8930&m=dev
**Scope:** Single Section (responsive addition to existing desktop section)
**Design:** 375×541px | rem divisor: 16 | Viewport: mobile
**Layout type:** contained
**Approach:** No Swiper on mobile — native horizontal scroll

**Screenshot:**

![Related Products Mobile](https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/1bff53e5-be7d-4715-ad85-28b2a452b6e8)

---

### Figma Layout (662:8930)

**Root:**
- Width: 375px
- Padding: 68px top/bottom, 0 left/right
- Gap: 24px between children
- Direction: vertical

**Header (661:8924):**
- Padding: 0 top/bottom, 12px left/right
- Title: "sản phẩm liên quan" — Phudu 700 28px, lh 110%, color #680103, uppercase

**Product Row (661:8881):**
- Direction: horizontal
- Padding: 0 top/bottom, 12px left/right
- Gap: 6px between cards
- 2 product cards visible (172×324px each), scroll for more
- Native horizontal scroll (NO Swiper)

**Divider (661:8925):**
- Width: 375px, padding 120px left/right
- 4 dashes: 32×2px each, color #1c1c1c, gap 3px
- CSS approach: dashed border or background pattern

### Changes Required

This is **responsive CSS only** — add `@media screen and (max-width: 639.98px)` rules to existing `styles.css`. No new files, no HTML changes needed.

| Change | Description |
|--------|-------------|
| Section padding | 68px top/bottom → `4.25rem` |
| Header padding | 12px L/R → `0.75rem` |
| Title font-size | 28px → `1.75rem` |
| Slider wrap | Remove side padding, use 12px. Hide nav arrows |
| Product list | Disable Swiper, native `overflow-x: auto` scroll, gap 6px (0.375rem) |
| Slide width | 172px → `calc((100% - 0.375rem) / 2)` (2 cards per view) |
| Divider | Add decorative dashed line below section |
| Nav arrows | `display: none` on mobile |

### Typography (mobile)

| # | Figma | CSS | Element |
|---|-------|-----|---------|
| 1 | Phudu 700 28px / lh 110% | `font-size: 1.75rem; font-weight: 700; line-height: 1.1;` | Section title |
| 2 | — | (inherits from product card mobile styles) | Product card text |

### JS Changes

Update `scripts.js` to only init Swiper on desktop (> 640px). On mobile (≤ 639.98px), Swiper should not initialize — native scroll handles it.

### Assets

No new assets needed.

### Files to Update

```
template-parts/single-product/
  related-product/
    index.php                       # UPDATE: add divider markup
    assets/
        styles.css                  # UPDATE: add mobile responsive styles
        scripts.js                  # UPDATE: wrap Swiper init in desktop check
```

**Total: 3 files to update, 0 files to create**

---

Ready to build? Run: `/figma-build`
