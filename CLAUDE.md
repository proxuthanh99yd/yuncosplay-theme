# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**OKHUB Theme** — WordPress theme thuần túy (PHP templates, vanilla CSS/JS). Sử dụng WooCommerce cho sản phẩm, custom REST API, Alpine.js cho reactivity, GSAP + AOS cho animation, Swiper cho carousel, Lenis cho smooth scroll.

## Commands

```bash
# WordPress chạy qua Local Sites (LocalWP)
# Không có CLI build — chỉ cần reload browser khi sửa file

# Xem log PHP
tail -f ~/Library/Logs/Local/local-lightning.log

# WP-CLI (nếu cần)
wp theme list
wp cache flush
```

Không có test runner, bundler, hay build step. Mọi thay đổi CSS/JS có hiệu lực ngay khi reload.

## Architecture

### Core Structure

```
okhub-theme/
├── style.css                    # Theme metadata (tên, version, author)
├── functions.php                # Bootstrap — require tất cả module
├── header.php                   # Global header template
├── footer.php                   # Global footer template
├── front-page.php               # Homepage template
├── index.php                    # Fallback template
├── inc/                         # PHP logic modules
│   ├── functions.php            # Helper functions (mobile detect, content)
│   ├── helpers.php              # Pagination, utilities
│   ├── ajax.php                 # AJAX handlers
│   ├── api.php                  # REST API endpoints (/search, /get-all)
│   ├── product-api.php          # Product REST API
│   ├── blog-api.php             # Blog REST API
│   └── shortcodes.php           # Custom shortcodes
├── import-assets/               # Asset enqueue system
│   ├── import-css-js.php        # wp_enqueue_style/script (main)
│   └── reset-css-js.php         # Remove WP default bloat
├── import-order.php             # Additional enqueue config
├── assets/                      # Global static assets
│   ├── css/                     # Global CSS files
│   │   ├── _reset.css           # CSS reset
│   │   ├── _variables.css       # CSS custom properties
│   │   └── global.css           # Global styles
│   ├── js/                      # Global JS files
│   │   ├── app.js               # Main app logic
│   │   ├── utils.js             # Utility functions
│   │   ├── custom-drawer.js     # Drawer component
│   │   └── custom-option.js     # Custom option component
│   └── fonts/                   # Local font files
├── template-parts/              # Modular PHP templates
│   ├── layouts/                 # Global layout parts
│   │   ├── header/              # Header (desktop + mobile variants)
│   │   ├── footer/              # Footer
│   │   └── cta/                 # Call-to-action section
│   ├── components/              # Reusable UI components
│   │   ├── animated-button/     # Button component
│   │   ├── blog-item/           # Blog card
│   │   ├── product/             # Product card
│   │   └── assets/              # Shared component styles
│   ├── home-page/               # Homepage sections
│   │   ├── index.php            # Section orchestrator
│   │   ├── section-banner/      # Hero section
│   │   ├── section-about/       # About section
│   │   ├── section-products/    # Products section
│   │   └── ...                  # Other sections
│   └── single-product/          # Single product page
└── woocommerce/                 # WooCommerce template overrides
    └── single-product.php       # Product detail override
```

### Template Parts Pattern

Mỗi section/component follow cấu trúc này:

```
template-parts/
└── [page-name]/
    └── section-[name]/
        ├── index.php              # PHP template (HTML + PHP logic)
        └── assets/
            ├── styles.css         # Component-scoped CSS
            └── scripts.js         # Component-scoped JS (ES module)
```

**Reusable components:**
```
template-parts/
└── components/
    └── [component-name]/
        ├── index.php              # PHP template
        └── assets/
            ├── styles.css
            └── scripts.js
```

### Page Template Pattern

Mỗi page template load các sections theo thứ tự:

```php
<?php
// template-parts/home-page/index.php
get_template_part('template-parts/home-page/section-banner/index');
get_template_part('template-parts/home-page/section-about/index');
get_template_part('template-parts/home-page/section-products/index');
// ...
?>
```

### File Organization Rules

#### CRITICAL: Directory-Based Pattern

**LUÔN dùng directory cho mỗi section/component, kể cả chỉ có 1 file:**

```
section-hero/
├── index.php
└── assets/
    ├── styles.css
    └── scripts.js
```

**KHÔNG bao giờ:**
```
section-hero.php          # ❌ Không dùng flat file
section-hero.css          # ❌ Không dùng flat CSS
```

### Asset Enqueue System

Assets được enqueue qua `import-assets/import-css-js.php`:

```php
// Conditional load — chỉ load CSS/JS khi template được dùng
[
    'type' => 'style',
    'handle' => 'home-page',
    'src' => get_theme_file_uri('/template-parts/home-page/assets/styles.css'),
    'condition' => is_page_template('front-page.php')
],
```

- `THEME_VERSION = time()` trong dev (luôn fresh)
- `THEME_VERSION = wp_get_theme()->get('Version')` trong production
- JS modules được thêm `type="module"` qua `script_loader_tag` filter

### Header/Footer

- `header.php` và `footer.php` ở root
- Desktop/mobile header variants — detect qua `IS_MOBILE` constant
- Load via `get_header()` / `get_footer()` trong template files

## Styling

- **Vanilla CSS** — không dùng preprocessor hay Tailwind
- **CSS Custom Properties** — định nghĩa trong `assets/css/_variables.css`
- **BEM-like naming** — `.section-hero__title`, `.product-card__price`
- **Scoped CSS per component** — mỗi section/component có file `assets/styles.css` riêng
- **rem units** — dùng rem cho spacing/typography, `1px` cho borders
- **CSS Reset** — custom reset trong `assets/css/_reset.css`
- **Google Fonts** — load qua `wp_enqueue_style` (hiện dùng font Phudu)
- **Local fonts** — `@font-face` trong `assets/fonts/stylesheet.css`

### CSS Convention

```css
/* Section container */
.section-[name] { }

/* Section elements */
.section-[name]__title { }
.section-[name]__content { }
.section-[name]__item { }

/* Component */
.[component-name] { }
.[component-name]__element { }
.[component-name]--modifier { }
```

### rem Conversion

Khi convert từ Figma (design width = 1920px):
```
rem = figma_px / 10    (nếu html font-size: 10px)
hoặc
rem = figma_px / 16    (nếu html font-size: 16px — default)
```
Kiểm tra `_variables.css` hoặc `_reset.css` để xác định base font-size.

## JavaScript

- **Vanilla JS + Alpine.js** — Alpine cho reactivity (x-data, x-show, x-on, etc.)
- **ES Modules** — `type="module"` cho component scripts
- **Thư viện bên thứ 3:**
  - Swiper (carousel/slider)
  - GSAP + ScrollTrigger + ScrollSmoother (animations)
  - AOS (Animate on Scroll)
  - Lenis (smooth scroll)
  - Plyr (video player)
  - Fancybox (lightbox)
  - noUiSlider (range slider)
- **Không dùng jQuery** (trừ khi WooCommerce require)
- **Script pattern per section:**

```javascript
// template-parts/home-page/section-banner/assets/scripts.js
import Swiper from '/wp-content/themes/okhub-theme/assets/js/swiper-bundle.min.js';

const bannerSwiper = new Swiper('.section-banner__slider', {
  // config
});
```

## PHP Conventions

- **WordPress coding standards** — `snake_case` cho functions, `$snake_case` cho variables
- **Template hierarchy** — dùng WordPress template system (front-page.php, single-product.php, etc.)
- **get_template_part()** — dùng để include template parts, KHÔNG dùng `include` hay `require`
- **WP functions** — dùng `get_theme_file_uri()`, `get_theme_file_path()` thay vì hardcode paths
- **Escaping** — `esc_html()`, `esc_attr()`, `esc_url()` cho output
- **Nonce** — `wp_create_nonce()` / `wp_verify_nonce()` cho AJAX/REST
- **Prefix** — dùng prefix `okhub_` hoặc `cosplay_` cho custom functions

## Conventions

- **kebab-case** cho files/folders
- **BEM naming** cho CSS classes
- **snake_case** cho PHP functions/variables
- **camelCase** cho JS variables/functions
- **SCREAMING_SNAKE_CASE** cho PHP constants
- **500-line max** per file — split thành sub-templates nếu quá dài
- **Vietnamese comments OK** — code đang dùng Vietnamese comments, giữ nguyên style
- **Không dùng TypeScript** — thuần JavaScript
- **Không dùng React/JSX** — thuần PHP templates + HTML

### Import / Include Order (functions.php)

1. Core functions (`inc/functions.php`)
2. Helpers (`inc/helpers.php`)
3. Asset reset (`import-assets/reset-css-js.php`)
4. Asset import (`import-assets/import-css-js.php`)
5. AJAX handlers (`inc/ajax.php`)
6. REST API (`inc/api.php`)
7. Shortcodes (`inc/shortcodes.php`)
8. Import order config
9. Feature-specific APIs

## WooCommerce Integration

- Custom product fields: `_sale_price_custom` (giá bán)
- Template overrides trong `woocommerce/` directory
- Custom product card component: `template-parts/components/product/`
- REST API cho products: `inc/product-api.php`

## REST API

- Base: `/wp-json/api/v1/`
- Endpoints:
  - `GET /search?s={query}` — Search posts + taxonomies
  - `GET /get-all/{post_type}` — Get all items by post type
  - Product & blog specific endpoints trong `product-api.php` và `blog-api.php`

## Key Integrations

- **LocalWP**: Development server, tự động có MySQL + PHP + Nginx
- **WooCommerce**: Product management, custom fields, template overrides
- **Permalink Manager**: Custom URL patterns (`collections/{term-slug}`)
- **Contact Form 7**: Form handling với custom phone validation
- **WP All Import**: Data import với base64 image handling
