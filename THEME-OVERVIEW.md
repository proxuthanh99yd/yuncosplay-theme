# OkHub Theme - Comprehensive Overview

**Theme Location:** `/Users/taamlee/Local Sites/yuncosplay/app/public/wp-content/themes/okhub-theme/`  
**Current State:** Active, Git-tracked project with Claude integration  
**Last Modified:** April 11, 2026 (14:18)

---

## 1. TOP-LEVEL DIRECTORY STRUCTURE

```
okhub-theme/
├── .claude/                    # Claude Code configuration
│   ├── agents/
│   │   └── component-generator.md
│   └── commands/
│       ├── figma-plan.md
│       ├── figma-build.md
│       └── fix-to-figma.md
├── .git/                       # Git repository
├── .mcp.json                   # MCP configuration
├── .gitignore
├── .DS_Store
├── assets/                     # Root-level styles & scripts
├── e2e/                        # End-to-end test directory
├── inc/                        # PHP includes (7 files, 1,631 lines)
├── import-assets/              # CSS/JS asset enqueuing
├── template-parts/             # Component-based templates
├── woocommerce/                # WooCommerce custom templates
├── functions.php               # Main theme hooks (732 lines)
├── header.php                  # Header template
├── footer.php                  # Footer template
├── front-page.php              # Homepage template
├── page-contact.php            # Contact page template
├── search.php                  # Search results template
├── index.php                   # Fallback template
├── style.css                   # Theme style header
├── README.md                   # Theme documentation
├── CLAUDE.md                   # Claude instructions
├── compact.md                  # Project workflow documentation
├── playwright.config.js        # E2E test configuration
├── package.json                # NPM dependencies
├── package-lock.json
├── rank-math.php              # SEO plugin integration
├── import-order.php           # Order import functionality
├── order-response.json        # Order response data
└── screenshot.png             # Theme screenshot
```

---

## 2. TEMPLATE-PARTS DIRECTORY (2 LEVELS DEEP)

### Directory Structure
```
template-parts/
├── __MACOSX/                           # macOS archive remnant
├── layouts/                            # Layout templates
│   ├── header/
│   │   ├── index.php
│   │   └── assets/
│   │       ├── styles.css
│   │       └── scripts.js
│   ├── footer/
│   │   ├── index.php
│   │   └── assets/
│   │       ├── styles.css
│   │       └── scripts.js
│   └── cta/                           # Call-to-action section
│       ├── index.php
│       └── assets/
│           ├── styles.css
│           └── scripts.js
├── components/                        # Reusable components
│   ├── animated-button/
│   ├── blog-item/
│   ├── blog-item-v2/
│   ├── icon-location/
│   ├── product/
│   └── assets/
│       ├── styles.css
│       └── scripts.js
├── home-page/                         # Homepage sections (13 sections)
│   ├── section-banner/
│   ├── section-hero/ (implied)
│   ├── section-category/
│   ├── section-products/
│   ├── section-events/
│   ├── section-services/
│   ├── section-about/
│   ├── section-gallery/
│   ├── section-highlights/
│   ├── section-blog/
│   ├── section-contact/ (implied)
│   ├── index.php
│   └── assets/
│       ├── styles.css
│       └── scripts.js
├── single-product/                   # Product detail page
│   ├── section-gallery/
│   ├── section-content/
│   ├── section-related/
│   ├── index.php
│   ├── assets/
│   │   ├── styles.css
│   │   └── scripts.js
│   ├── PLAN.md
│   ├── PLAN-MOBILE.md
│   ├── PLAN-RELATED.md
│   └── PLAN-RELATED-MOBILE.md
├── product-listing/                  # Product archive/category
│   ├── section-breadcrumb/
│   ├── section-sidebar/
│   ├── section-content/
│   ├── index.php
│   ├── assets/
│   │   ├── styles.css
│   │   └── scripts.js
│   ├── PLAN.md
│   └── product-listing.zip
├── contact-page/                     # Contact page
│   ├── section-contact-form/
│   ├── section-contact-info/
│   ├── index.php
│   ├── acf.php                       # ACF configuration
│   ├── assets/
│   │   ├── styles.css
│   │   └── scripts.js
│   └── PLAN.md
└── search-page/                      # Search results
    ├── index.php
    └── assets/
        ├── styles.css
        └── scripts.js
```

---

## 3. PAGE TEMPLATES

| File | Type | Purpose |
|------|------|---------|
| `front-page.php` | Template | Homepage (122 bytes - simple include) |
| `page-contact.php` | Template | Contact page (122 bytes - simple include) |
| `search.php` | Template | Search results (297 bytes) |
| `header.php` | Root | Site header |
| `footer.php` | Root | Site footer |
| `index.php` | Root | Fallback template (59 bytes) |

**Note:** Templates are minimal - most rendering is in template-parts/

---

## 4. FUNCTIONS.PHP ANALYSIS (732 lines)

### Key Hooks & Functionality

**A. WooCommerce Custom Fields**
- `woocommerce_product_options_pricing` - Add "Giá bán (₫)" field for simple products
- `woocommerce_variation_options_pricing` - Add pricing field to product variations
- `woocommerce_admin_process_product_object` - Save custom pricing fields
- `woocommerce_save_product_variation` - Save variation rental fields

**B. Base64 Image Handler**
- Hook: `pmxi_saved_post` (10, 3)
- Function: `ks_handle_base64_images()`
- Converts base64 images to WordPress attachments during post import
- Supports: post, page, product post types
- Generates proper attachment metadata and SEO alt text

**C. Permalink Manager Integration**
- Hooks: `created_term`, `edited_term` (priority 99)
- Forces product category URIs to `/collections/{slug}` format
- Flushes rewrite rules on category changes

**D. REST API Endpoints**
- `GET /wp-json/api/v1/post/strip-styles` - Remove inline styles from post content
  - Paginated (100 posts per page)
  - Returns success/updated count

**E. Blog/Featured Content**
- Enqueues Swiper & featured section scripts
- AJAX post filtering by category

**F. AJAX Actions**
- `wp_ajax_filter_posts` - Filter blog posts by category with AJAX
- `wp_ajax_nopriv_filter_posts` - Allow non-authenticated requests

### Commented-Out Code
- **Lines 254-483:** Complex rental field & order recalculation logic
  - Indicates potential feature that may have been disabled
  - Includes rental day tracking, price recalculation on order edit

---

## 5. ASSET ENQUEUING - `import-assets/import-css-js.php` (430 lines)

### Architecture
- **Dynamic versioning:** Uses `time()` in development, theme version in production
- **Conditional loading:** Assets load only when needed (via template conditionals)
- **Module scripts:** Modern ES modules for frontend code

### External Libraries Enqueued

| Library | Type | URL | Purpose |
|---------|------|-----|---------|
| Phudu | Font | Google Fonts | Display typography |
| Swiper | Library | Local | Carousel/slider |
| Lenis | Library | CDN | Smooth scrolling |
| GSAP | Library | CDN (v3.14.1) | Animation engine |
| - ScrollTrigger | Plugin | CDN | Scroll-triggered animations |
| - ScrollSmoother | Plugin | CDN | Smooth scroll effects |
| - CustomEase | Plugin | CDN | Custom easing |
| AOS | Library | CDN | Animate On Scroll |

### Local Assets

**Styles (CSS)**
```
- stylesheet.css              (Custom fonts)
- _reset.css                  (CSS reset)
- _variables.css              (CSS variables)
- global.css                  (Global styles)
- header/styles.css           (Header)
- footer/styles.css           (Footer)
- cta/styles.css              (CTA section)
- components/assets/styles.css (Shared components)
- home-page/assets/styles.css (Homepage only)
- single-product/assets/styles.css (Product pages only)
- fancybox.css               (Lightbox - product pages only)
- nouislider.min.css         (Price filter - shop/category only)
- product-listing/assets/styles.css (Product archive - shop/category only)
- contact-page/assets/styles.css (Contact page only)
- search-page/assets/styles.css (Search results only)
```

**Scripts (JavaScript)**
```
- app.js                       (Main app logic)
- utils.js                     (Utilities)
- custom-option.js            (Select/option customization)
- custom-drawer.js            (Drawer/sidebar menu)
- header/scripts.js           (Header interactions)
- footer/scripts.js           (Footer interactions)
- cta/scripts.js              (CTA interactions)
- components/assets/scripts.js (Component interactions)
- home-page/assets/scripts.js (Homepage - ES module)
- single-product/assets/scripts.js (Product pages - ES module)
- fancybox.umd.js            (Lightbox - product pages only)
- nouislider.min.js          (Price filter - shop/category only)
- product-listing/assets/scripts.js (Product archive - ES module)
- contact-page/assets/scripts.js (Contact page - ES module)
- search-page/assets/scripts.js (Search results - ES module)
```

### ES Module Scripts
```javascript
// add_type_attribute() converts these to <script type="module">
['home-page', 'components', 'header', 'single-product', 'product-listing', 'contact-page', 'search-page']
```

---

## 6. MARKDOWN FILES FOUND

| File | Purpose |
|------|---------|
| `README.md` | Basic theme documentation |
| `CLAUDE.md` | Claude Code instructions (planning?) |
| `compact.md` | MCP workflow documentation |
| `.claude/agents/component-generator.md` | Component generation agent |
| `.claude/commands/figma-plan.md` | Figma planning command |
| `.claude/commands/figma-build.md` | Figma build command |
| `.claude/commands/fix-to-figma.md` | Figma verification command |
| `template-parts/product-listing/PLAN.md` | Product listing planning |
| `template-parts/contact-page/PLAN.md` | Contact page planning |
| `template-parts/single-product/PLAN*.md` | 4 planning docs (mobile, related items) |

---

## 7. ASSETS DIRECTORY

### CSS Files (`/assets/css/`)
```
_reset.css              14.5 KB  (Comprehensive CSS reset)
_variables.css          10 bytes (CSS custom properties)
global.css              4.8 KB   (Global styles)
404.css                 4.8 KB   (Error page)
datepicker.min.css      4.9 KB   (Date picker styling)
datepicker-bulma.min.css 4.2 KB  (Bulma variant)
fancybox.css            25 KB    (Lightbox gallery)
nouislider.min.css      4.2 KB   (Range slider)
plyr.css                33 KB    (Video player)
swiper-bundle.min.css   14 KB    (Carousel)
```

### JavaScript Files (`/assets/js/`)
```
alpinejs@3.x.x.min.js   45.8 KB  (Alpine.js framework)
app.js                  3.2 KB   (Main app logic)
contact-form.js         327 B    (Form handling)
custom-drawer.js        20 KB    (Drawer/modal menu)
custom-option.js        17 KB    (Custom select/dropdown)
datepicker-full.min.js  35 KB    (Date picker)
datepicker-i18n-fr.js   734 B    (French i18n)
fancybox.umd.js         142 KB   (Lightbox)
ffmpeg.min.js           4.7 KB   (Video processing)
nouislider.min.js       28 KB    (Range slider)
plyr.js                 113 KB   (Video player)
swiper-bundle.min.js    154 KB   (Carousel)
utils.js                6.8 KB   (Utility functions)
validate.min.js         14.5 KB  (Form validation)
blocks/                 (admin block scripts)
admin/                  (admin scripts)
```

---

## 8. INC DIRECTORY - PHP MODULES (7 files, ~1,631 lines total)

| File | Lines | Purpose |
|------|-------|---------|
| `ajax.php` | 0 | Empty - AJAX handlers placeholder |
| `functions.php` | 109 | Core WordPress setup functions |
| `helpers.php` | 184 | Utility/helper functions |
| `shortcodes.php` | 554 | Custom shortcode handlers |
| `api.php` | 384 | REST API endpoints |
| `product-api.php` | 287 | WooCommerce product API |
| `blog-api.php` | 113 | Blog/post API endpoints |

---

## 9. IMPORT-ASSETS DIRECTORY

| File | Lines | Purpose |
|------|-------|---------|
| `import-css-js.php` | 430 | Main asset enqueuing system |
| `reset-css-js.php` | 91 | CSS/JS cleanup/reset |

---

## 10. ADDITIONAL DIRECTORIES

### `/e2e/` - End-to-End Testing
- Playwright configuration (`playwright.config.js`)
- Likely Playwright test files for UI testing

### `/woocommerce/` - WooCommerce Customization
- Custom WooCommerce templates override defaults
- Plugin integration templates

### `/.claude/` - Claude Code Integration
- Agent configurations for component generation
- Command definitions for Figma integration
- Integration with Figma → WordPress workflow

---

## 11. THEME STATE SUMMARY

### ✅ What's Implemented
- ✅ Multi-page structure (homepage, contact, product detail, product listing, search)
- ✅ WooCommerce integration with custom fields
- ✅ Component-based architecture (template-parts)
- ✅ Asset management system (conditional enqueuing)
- ✅ REST API endpoints for products, blog, filtering
- ✅ AJAX functionality (blog filtering)
- ✅ Smooth scrolling & animation libraries (Lenis, GSAP, AOS)
- ✅ Image gallery (Fancybox)
- ✅ Modern form UI (custom selects, drawers)
- ✅ SEO optimization hooks (Rank Math, base64 image handler)
- ✅ Git version control
- ✅ E2E testing setup

### 📝 Currently In Progress
- Product listing improvements (PLAN.md mentions ongoing work)
- Contact page refinement
- Single product related items section
- Mobile optimization (PLAN-MOBILE.md files)

### 🔧 Configuration & Tools
- Claude Code integration with Figma bridge
- MCP (Model Context Protocol) setup
- NPM package management
- Playwright E2E testing

### 📚 Documentation
- Multiple planning docs (PLAN*.md files)
- Comprehensive CLAUDE.md instructions
- Project workflow in compact.md

---

## 12. KEY TECHNICAL INSIGHTS

1. **Modular Architecture:** Each page section has isolated CSS/JS
2. **Conditional Loading:** Assets only load when needed (via `is_*()` conditionals)
3. **Vietnamese Localization:** UI strings in Vietnamese (₫ currency, Vietnamese copy)
4. **ES Modules:** Modern JavaScript with `type="module"` for specific sections
5. **API-First Design:** Multiple REST endpoints for data fetching
6. **WooCommerce Focus:** Heavy customization for cosplay product rental/sales
7. **Performance:** Lazy loading strategies, CDN usage, minified assets
8. **Testing Ready:** E2E test infrastructure in place

---

## 13. QUICK FILE REFERENCE

**Main Entry Points:**
- `functions.php` - All hooks & functionality
- `front-page.php` - Homepage
- `page-contact.php` - Contact page

**Asset System:**
- `import-assets/import-css-js.php` - Master asset registry

**Component System:**
- `template-parts/home-page/` - Homepage sections
- `template-parts/single-product/` - Product page
- `template-parts/product-listing/` - Shop/category pages
- `template-parts/components/` - Reusable parts

**API Modules:**
- `inc/api.php` - Custom REST endpoints
- `inc/product-api.php` - Product-specific API
- `inc/blog-api.php` - Blog/post API

