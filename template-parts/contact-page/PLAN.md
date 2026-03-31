# Plan: Contact Page (Lien he)

**Figma PC:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=565-7692&m=dev
**Figma Mobile:** https://www.figma.com/design/eZ0tqK40clMlGyu9ey0TMO/YUN-COSPLAY?node-id=646-8227&m=dev
**Scope:** Full Page
**Design PC:** 1600x1043px | Viewport: `desktop`
**Design Mobile:** 375x1859px | Viewport: `mobile`
**REM system:** `1vw` (desktop) / `4.267vw` (mobile < 640px) / `17px` (>= 1920px)

---

## Screenshots

**PC (Desktop):**
![PC Contact](/tmp/figma_pc_contact.png)

**Mobile:**
![Mobile Contact](/tmp/figma_mb_contact.png)

---

## Sections (4)

| # | Section | Node ID (PC / MB) | Layout | Complexity | Heading | Notes |
|---|---------|-------------------|--------|------------|---------|-------|
| 1 | Breadcrumb | `565:7686` / `646:7892` | contained | simple | -- | Reuses existing breadcrumb component |
| 2 | Contact Main | `565:7680` / `646:7987` + `646:8125` | contained | medium | **h1** | 2-column PC (info left + form right), stacked mobile |
| 3 | Social Media Cards | `565:7643` / `1777:9925` | contained | medium | **h2** | 3 cards with image + overlay, slider with arrows on PC |
| 4 | Contact Form | `559:8438` / `646:8125` | contained | medium | -- | 2 input fields + textarea + submit button |

> **Note:** Header (Menu) `559:7302` / `646:7767` and Footer `565:7361` / `646:8126` already exist in `template-parts/layouts/header/` and `template-parts/layouts/footer/`. They will NOT be rebuilt.

> **Note:** Mobile sticky bottom bar `646:8301` ("Ghim lai") already exists in `template-parts/layouts/cta/`. Will NOT be rebuilt.

---

## Detailed Section Breakdown

### Section 1: Breadcrumb (`section-breadcrumb`)

**PC:** 1600x85px full-width, content padded 68px left
**Mobile:** 375x62px

- Simple breadcrumb: "Trang chu > Lien he"
- Already an INSTANCE of component `528:7387` (Breadcrumb)
- **Reuse** existing breadcrumb pattern from other pages

**Sub-components:** None

---

### Section 2: Contact Info (`section-contact-info`)

**PC:** 672x423px left column inside 1600x523px container
**Mobile:** 375x333px stacked

**Sub-components:**

| # | Component | PC Node | MB Node | Description |
|---|-----------|---------|---------|-------------|
| 1 | Main Title (h1) | `559:8448` | `646:7900` | "Lien he voi chung toi" — Phudu 58px(PC) / 28px(MB) bold |
| 2 | Contact Details | `565:7298` | `646:7936` | Working hours + Address with icons |
| 3 | Divider Line | `565:7333` | `646:7988` | Horizontal separator |
| 4 | "Theo doi chung toi" heading (h2) | `565:7507` | `646:7939` | Phudu 24px(PC) / 18px(MB) bold |
| 5 | Social Media Cards | `565:7643` | `1777:9925` | 3 cards with slider arrows (PC) or horizontal scroll (MB) |
| 6 | Slider Arrows | `565:7693` | -- | PC only: left/right circular buttons |

**Social Media Card Structure (x3):**
- Background image (215x189px PC / 114x105px MB)
- Gradient overlay (dark bottom)
- Icon circle (26px PC / 15px MB) + Platform name
- Platforms: Facebook, Tiktok, Instagram

---

### Section 3: Contact Form (`section-contact-form`)

**PC:** 752x422px right column, white background with rounded corners
**Mobile:** 375x487px stacked below contact info

**Sub-components:**

| # | Component | PC Node | MB Node | Description |
|---|-----------|---------|---------|-------------|
| 1 | Name Input | `559:8111` | `646:7992` | Label "Ten cua ban" + required asterisk + text input (343px PC / 323px MB) |
| 2 | Phone Input | `559:8210` | `646:7993` | Label "So dien thoai" + required asterisk + text input (343px PC / 323px MB) |
| 3 | Textarea | `559:8405` | `646:7994` | Label "Ghi chu" + required asterisk + textarea (704px PC / 323px MB, 160px/107px height) |
| 4 | Submit Button | `559:8439` | `646:7995` | "Gui thong tin" — animated-button component (154x50 PC / 121x38 MB) |

**Input Field Pattern (reusable):**
- Label row: label text (SF Pro 16/400) + red asterisk (SF Pro 16/600 `#ef2020`)
- Input container: bordered, with placeholder text (SF Pro 14/400)
- PC: 2 inputs side-by-side (343px each), then full-width textarea (704px)
- Mobile: all fields full-width stacked
- Each has optional "Helper Message" below (SF Pro 12/400)

---

## Common Components

| Component | Used in | Source |
|-----------|---------|--------|
| `animated-button` | Contact Form (submit) | `template-parts/components/animated-button/` (existing) |
| Breadcrumb | Breadcrumb section | Instance `528:7387` — check if already implemented |
| Social Media Card | Contact Info section | **New** — inline in section (not complex enough for standalone component) |
| Input Field | Contact Form | Instance `212:3576` — **New** pattern, inline in form |

---

## Typography (9 unique styles)

| # | Element | Figma (PC) | Figma (MB) | CSS (using 1vw rem base) | Color |
|---|---------|-----------|-----------|--------------------------|-------|
| 1 | Page Title (h1) | Phudu 58/700 lh=1.1 | Phudu 28/700 lh=1.1 | `font-family: 'Phudu'; font-size: 3.625rem; font-weight: 700; line-height: 1.1;` (PC) / `font-size: 7.467rem;` (MB at 4.267vw) | `#680103` |
| 2 | Section Subtitle (h2) | Phudu 24/700 lh=1.1 | Phudu 18/700 lh=1.22 | `font-family: 'Phudu'; font-size: 1.5rem; font-weight: 700; line-height: 1.1;` | `#680103` |
| 3 | Form Label | SF Pro 16/400 lh=1.5 | SF Pro 16/400 lh=1.5 | `font-family: 'SF Pro Display'; font-size: 1rem; font-weight: 400; line-height: 1.5;` | `#1c1c1c` |
| 4 | Form Asterisk | SF Pro 16/600 lh=1.5 | SF Pro 16/600 lh=1.5 | `font-weight: 600; color: #ef2020;` | `#ef2020` |
| 5 | Input Text / Placeholder | SF Pro 14/400 lh=1.5 | SF Pro 14/400 lh=1.5 | `font-size: 0.875rem; font-weight: 400; line-height: 1.5;` | `#1c1c1c` |
| 6 | Contact Details | SF Pro 14/500 lh=1.5 | SF Pro 14/500 lh=1.5 | `font-size: 0.875rem; font-weight: 500; line-height: 1.5;` | `#1c1c1c` |
| 7 | Helper Text | SF Pro 12/400 lh=1.5 | SF Pro 12/400 lh=1.5 | `font-size: 0.75rem; font-weight: 400; line-height: 1.5;` | `#1c1c1c` |
| 8 | Social Card Label | SF Pro 14/500 lh=1.5 (PC) | SF Pro 10/500 lh=1.5 (MB) | `font-size: 0.875rem; font-weight: 500;` (PC) | `#f7f4ec` |
| 9 | Breadcrumb | SF Pro 14/400 lh=1.5 (PC) | SF Pro 12/400 lh=1.5 (MB) | `font-size: 0.875rem; font-weight: 400;` | `#1c1c1c` |

---

## Colors Used

| Token | Hex | Usage |
|-------|-----|-------|
| Brand Primary (Dark Red) | `#680103` | Headings, button outlines, accents |
| Text Primary | `#1c1c1c` | Body text, form text |
| Text Light | `#f7f4ec` | Text on dark backgrounds (top bar, social cards) |
| Error / Required | `#ef2020` | Required asterisks |
| Card Accent | `#cb5140` | Input counter buttons |
| Background Light | `#f7f4ec` | Page background |
| White | `#ffffff` | Form background, card backgrounds |

---

## Assets to Download

| # | Type | Filename | Section | Figma Node | Notes |
|---|------|----------|---------|------------|-------|
| 1 | image | `d-social-facebook.webp` | Social Cards | `565:7645` | PC Facebook card image |
| 2 | image | `d-social-tiktok.webp` | Social Cards | `565:7655` | PC Tiktok card image |
| 3 | image | `d-social-instagram.webp` | Social Cards | `565:7665` | PC Instagram card image |
| 4 | image | `m-social-facebook.webp` | Social Cards | `1777:9927` | MB Facebook card image |
| 5 | image | `m-social-tiktok.webp` | Social Cards | `1777:9937` | MB Tiktok card image |
| 6 | image | `m-social-instagram.webp` | Social Cards | `1777:9947` | MB Instagram card image |
| 7 | icon | `ic-clock.svg` | Contact Info | from contact details | Working hours icon |
| 8 | icon | `ic-location.svg` | Contact Info | from contact details | Address icon |
| 9 | icon | `ic-facebook.svg` | Social Cards | `565:7650` | Facebook circle icon |
| 10 | icon | `ic-tiktok.svg` | Social Cards | `565:7662` | Tiktok circle icon |
| 11 | icon | `ic-instagram.svg` | Social Cards | `565:7672` | Instagram circle icon |
| 12 | icon | `ic-arrow-left.svg` | Slider Arrows | `565:7694` | Circular arrow button |
| 13 | icon | `ic-arrow-right.svg` | Slider Arrows | `565:7695` | Circular arrow button |

> **Note:** Social card images are CMS-managed (WordPress), so placeholder images are used for development. Final images come from ACF/WP admin.

---

## Fonts

| Family | Source | Weights | Load Method | Status |
|--------|--------|---------|-------------|--------|
| Phudu | Google Fonts | 300-900 | `wp_enqueue_style` | **Already loaded** in `import-css-js.php` |
| SF Pro Display | Local `.woff2` | 100-900 | `@font-face` in `stylesheet.css` | **Already loaded** |

---

## Asset Enqueue Plan

Add to `import-assets/import-css-js.php`:

| Handle | Type | File | Condition | Module |
|--------|------|------|-----------|--------|
| `contact-page` | style | `template-parts/contact-page/assets/styles.css` | `is_page_template('page-contact.php')` | -- |
| `contact-page` | script | `template-parts/contact-page/assets/scripts.js` | `is_page_template('page-contact.php')` | `type="module"` |

Add `'contact-page'` to the `$module_handles` array in `add_type_attribute()`.

---

## Layout Notes

### PC Layout (1600px)
```
|------ 1600px ------|
| [Menu - existing]  |
| [Breadcrumb]       |  padding: 0 68px
|                    |
| [Contact Info]  [Contact Form]  |  2-column: 672px | 752px, gap ~40px
| [title, hours,  [name] [phone] |  Content max-width: ~1440px
|  address]       [textarea]     |  padding: 0 68px
| [social cards]  [submit btn]   |
|                    |
| [Footer - existing]|
```

### Mobile Layout (375px)
```
|--- 375px ---|
| [Menu - existing] |
| [Breadcrumb]      |  padding: 0 12px
| [Title]           |
| [Hours, Address]  |  Stacked, full-width
| [Social Cards]    |  3 cards horizontal scroll
| [Contact Form]    |  Full-width, white bg with rounded corners
| [Name input]      |
| [Phone input]     |
| [Textarea]        |
| [Submit]          |
| [Footer - existing]|
| [Sticky bar - existing] |
```

---

## Implementation Notes

1. **Social Media Slider (PC):** Use Swiper.js (already loaded) for the 3-card slider with left/right arrow controls. On mobile, use CSS horizontal scroll or Swiper with freeMode.

2. **Contact Form Submission via Contact Form 7 REST API:**
   - CF7 plugin already installed and active (CSS dequeued, JS kept, custom phone validation in `inc/functions.php`)
   - Create a CF7 form in WP Admin with fields: `your-name` (text*), `your-phone` (tel*), `your-message` (textarea*)
   - **Do NOT** render CF7 shortcode — build custom HTML form matching Figma design
   - Submit via CF7 REST API: `POST /wp-json/contact-form-7/v1/contact-forms/{form_id}/feedback`
   - Send as `FormData` with fields: `your-name`, `your-phone`, `your-message`
   - CF7 form ID stored in ACF field `contact_cf7_form_id` and passed to JS via `wp_localize_script`
   - CF7 handles email sending, validation, spam filtering (reCAPTCHA if configured)

3. **Toast Notification (success/fail):**
   - Custom toast component — no external library
   - Auto-dismiss after 4 seconds, with close button
   - **Success toast:** green accent, message "Gui thong tin thanh cong! Chung toi se lien he voi ban som nhat."
   - **Fail toast:** red accent, message "Co loi xay ra. Vui long thu lai sau."
   - Validation errors from CF7 response (`response.invalid_fields[]`) shown inline under each field
   - Toast slides in from top-right (PC) / top-center (MB), smooth CSS animation
   - Toast HTML injected via JS (not in PHP template), styles in `section-contact-form/assets/styles.css`

4. **Input Field Counter:** The Figma design shows a `3` counter with +/- buttons on input fields. This appears to be a character count indicator pattern from the component library. **Clarify with design team** whether this is needed for the contact form or is just a component artifact. Likely NOT needed for contact form.

4. **Page Template:** Create `page-contact.php` at theme root with `Template Name: Contact`. Assign it to the "Lien he" page via WP Admin > Pages.

5. **Responsive Breakpoint:** Primary breakpoint at `640px` (matching `global.css` media query). Desktop layout applies above 640px, mobile below.

---

## ACF Fields (Code-registered)

**File:** `template-parts/contact-page/acf.php`
**Method:** `acf_add_local_field_group()` — registered via `acf/init` hook
**Location rule:** Page Template == `page-contact.php`

This file is included from `functions.php` (or a central ACF loader). It registers all custom fields for the Contact page programmatically — no JSON import or WP Admin manual setup needed.

### Field Group: `group_contact_page`

| Field Key | Field Name | Type | Label | Sub-fields | Notes |
|-----------|-----------|------|-------|------------|-------|
| `field_contact_page_title` | `contact_title` | text | Tieu de trang | -- | Default: "Lien he voi chung toi" |
| `field_contact_working_hours` | `contact_working_hours` | text | Gio lam viec | -- | Default: "6h → 20h (Thu 2 → Thu 7)" |
| `field_contact_address` | `contact_address` | textarea | Dia chi | -- | Default: "31 Tran Kim Xuyen..." |
| `field_contact_social_media` | `contact_social_media` | repeater | Mang xa hoi | (below) | Min: 1, Max: 6 |
| → `field_social_platform` | `platform` | select | Nen tang | -- | Choices: facebook, tiktok, instagram, youtube |
| → `field_social_name` | `name` | text | Ten hien thi | -- | e.g. "Facebook" |
| → `field_social_url` | `url` | url | Duong dan | -- | e.g. "https://facebook.com/yuncosplay" |
| → `field_social_image` | `image` | image | Hinh anh | -- | Return format: ID |
| `field_contact_form_cf7_id` | `contact_cf7_form_id` | number | CF7 Form ID | -- | ID of Contact Form 7 form (from WP Admin > Contact > Forms) |

### ACF Usage in Templates

```php
// section-contact-info/index.php
$contact_title     = get_field('contact_title') ?: 'Liên hệ với chúng tôi';
$working_hours     = get_field('contact_working_hours') ?: '6h → 20h (Thứ 2 → Thứ 7)';
$address           = get_field('contact_address') ?: '31 Trần Kim Xuyến, Yên Hoà, Cầu Giấy, Hà Nội';
$social_media      = get_field('contact_social_media') ?: [];

// section-contact-form/index.php
$cf7_form_id       = get_field('contact_cf7_form_id') ?: 0;
// Pass to JS via wp_localize_script:
// wp_localize_script('contact-page', 'contactPageData', [
//   'cf7FormId' => $cf7_form_id,
//   'cf7Endpoint' => rest_url("contact-form-7/v1/contact-forms/{$cf7_form_id}/feedback"),
//   'nonce' => wp_create_nonce('wp_rest'),
// ]);
```

### `acf.php` Loading

Add to `functions.php`:
```php
// Load ACF field groups registered by code
require_once get_template_directory() . '/template-parts/contact-page/acf.php';
```

---

## Files to Create (updated)

```
template-parts/contact-page/
├── index.php                          # Section orchestrator
├── acf.php                            # ACF field group registration (code-based)
├── PLAN.md                            # This plan
├── assets/
│   ├── styles.css                     # Page-level styles (imports)
│   ├── scripts.js                     # Page-level scripts (imports)
│   └── images/                        # Downloaded assets
│       ├── d-social-facebook.webp
│       ├── d-social-tiktok.webp
│       ├── d-social-instagram.webp
│       ├── m-social-facebook.webp
│       ├── m-social-tiktok.webp
│       ├── m-social-instagram.webp
│       ├── ic-clock.svg
│       ├── ic-location.svg
│       ├── ic-facebook.svg
│       ├── ic-tiktok.svg
│       ├── ic-instagram.svg
│       ├── ic-arrow-left.svg
│       └── ic-arrow-right.svg
├── section-breadcrumb/
│   └── index.php                      # Breadcrumb markup
├── section-contact-info/
│   ├── index.php                      # Left column: title, details, social cards
│   └── assets/
│       ├── styles.css                 # Contact info + social card styles
│       └── scripts.js                 # Social card slider (Swiper)
└── section-contact-form/
    ├── index.php                      # Right column: form fields + submit
    └── assets/
        ├── styles.css                 # Form styles + toast notification styles
        └── scripts.js                 # CF7 REST API submit + validation + toast

page-contact.php                       # WordPress page template (theme root), Template Name: Contact
```

**Total: ~13 files**

---

Ready to build? Run: `/figma-build`
