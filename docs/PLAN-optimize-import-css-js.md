# PLAN — Tối ưu import CSS/JS theo pattern source `vietdung`

> Mục tiêu: port kiến trúc `asset-manifest.php` + `bundler.php` + `import-css-js.php` mỏng
> từ `vietdung/okhub-theme` sang `yuncosplay/okhub-theme`.
>
> **Nguyên tắc chốt**: bỏ hẳn `@import` trong CSS và `import {}` trong JS.
> **Manifest là nguồn sự thật duy nhất** về file nào thuộc trang nào và theo thứ tự nào.
>
> Nguồn tham chiếu: `/Users/taamlee/Local Sites/vietdung/app/public/wp-content/themes/okhub-theme/import-assets/`
>
> **Cập nhật 2026-07-16 (rà soát lại)**: Phase 0 + 1a đã xong. §1.1 bản cũ **SAI về Swiper** —
> đã sửa ở §1.5. Baseline đo lại trên site thật ở §1.0.

---

## 0. Trạng thái — làm tới đâu rồi

| Phase | Nội dung | Trạng thái |
|---|---|---|
| 0 | Dọn dead code (AOS, ScrollSmoother) | ✅ XONG (chưa commit) |
| 1a | CSS: bỏ `@import`, dựng `asset-manifest.php` CSS | ✅ XONG (chưa commit) |
| **1b** | **JS: bỏ `import {}` → self-init** | ✅ **XONG (chưa commit)** — xem §8 |
| **2** | **Manifest JS** | ✅ **XONG** — gộp vào 1b (không tách được: xoá aggregator thì buộc phải liệt kê section ở manifest) |
| **3** | **`bundler.php` + `import-css-js.php` viết lại** | ✅ **XONG (chưa commit)** — xem §9 |
| **4** | **Per-page libs** | ✅ **XONG (chưa commit)** — xem §10 |
| 5 | Verify bằng Chrome DevTools MCP | ✅ verify Phase 1b (§8.4), 3 (§9.3), 4 (§10.5), 6 (§11.4) |
| **6** | **Self-host toàn bộ CDN** (GSAP, Lenis, Google Fonts) | ✅ **XONG (chưa commit)** — xem §11 |

Đang có đủ bộ 4 file: `asset-manifest.php` (CSS + JS) + **`bundler.php`** + `import-css-js.php`
(gộp bundle, fallback enqueue rời) + `reset-css-js.php`.

**Kết quả đo thật (đã xong Phase 0→4):**
- **Request CSS/JS: 743 → 109** trên 12 trang (kể cả lib CDN). Trang chủ **66 → 11**.
- **Byte qua dây (gzip): 90–152 KB/trang.** 7 trang bỏ hẳn GSAP (−48 KB gzip mỗi trang).

---

## 1. Hiện trạng đo được

### 1.0. Baseline thật (curl `http://yuncosplay.local/`, 2026-07-16)

| Chỉ số | Giá trị |
|---|---|
| `<link rel=stylesheet>` trang chủ | **38** |
| `<script src>` trang chủ | **30** |
| **Tổng request asset trang chủ** | **68** |
| `app.js` xuất hiện | **2 lần** 🐛 |
| Swiper xuất hiện | **2 lần** (local `2.8` + CDN `swiper@11`), cả CSS lẫn JS 🐛 |
| File CSS (không tính lib min) | 108 |
| File JS (không tính lib min) | 85 |

→ 2 bug double-load ở §5.10 là **có thật, đang chạy trên site**, không phải giả định.

### 1.2. Cache-busting đang hỏng

`import-css-js.php:17` — `$VERSION = false ? time() : wp_get_theme()->get('Version');`
Nhánh `time()` bị tắt cứng bằng `false` → `THEME_VERSION` = version tĩnh (vd `2.8`).
→ Sửa 1 file CSS **không đổi version** → browser ăn cache cũ → phải hard-refresh thủ công.
→ Bật `time()` thì ngược lại: **không file nào cache được**.
CLAUDE.md đang mô tả sai hành vi này. Bundler fingerprint theo **nội dung** giải quyết cả hai đầu.

> Phase 1a đã thêm `okhub_file_version()` = `filemtime` cho CSS → nhánh CSS đã đúng.
> JS vẫn đang dùng `THEME_VERSION` tĩnh → còn hỏng.

### 1.3. Waterfall

- **CSS**: đã xử lý ở Phase 1a (manifest, không còn `@import`).
- **JS**: **25 file** dùng `import { … } from` → mỗi file 1 request + phụ thuộc tuần tự,
  và `type="module"` ⇒ defer + waterfall theo cây import.

### 1.4. ⚠️ Thứ tự cascade hiện tại là `lib → core → page` (NGƯỢC với `vietdung`)

`wp_enqueue_lib` chạy ở priority **1000**, enqueue theme ở **1001** → **lib CSS in TRƯỚC theme CSS**.
Nghĩa là `_reset.css` / `global.css` hiện đang **đè lên** Swiper.

`vietdung` thì ngược lại (`core → fancybox → swiper → page`, xem `import-css-js.php:142-145`).
→ **Không được bê thứ tự của `vietdung` sang.** Phải giữ nguyên `lib → core → page` của theme này,
nếu không giao diện Swiper/Fancybox sẽ đổi.

Chi tiết cần giữ đúng vị trí (đã cài ở `okhub_asset_page_lib_css()`):
- `nouislider.css` in **trước** `product-listing/assets/styles.css`
- `fancybox.css` in **sau** `single-product/assets/styles.css`

### 1.5. 🔴 SỬA LỖI PLAN CŨ — Swiper là **site-wide**, KHÔNG per-page được

Bản plan cũ (§1.1) viết: *"swiper (168KB) không dùng ở: faqs, product-listing, search-page,
service-makeup, service-pgpb"* → **SAI**. Nó bỏ sót **header**.

`layouts/header/assets/header-desktop/scripts.js:207-208` và
`layouts/header/assets/header-mobile/scripts.js:61,78` đều gọi `new Swiper(...)` cho carousel
mega-menu. Header render ở **mọi trang**.

**Verify trên site thật** — đếm markup `parent-categories-swiper`:

| URL | HTTP | markup swiper mega-menu |
|---|---|---|
| `/` | 200 | 11 |
| `/dich-vu-makeup` | 200 | 11 |
| `/lien-he` | 200 | 11 |
| `/blogs` | 200 | 11 |
| trang 404 | 404 | 11 |

→ Mega-menu là **ACF-driven** (`$header_menu` từ option), giống hệt nhau ở mọi trang — kể cả 404.
→ **Swiper phải nằm ở `core`.** Khoản "−168KB cho 5 page-group" của plan cũ **không tồn tại**.

*(Muốn cắt thật thì phải lazy-load Swiper lúc mở mega-menu — refactor riêng, xem §7.)*

### 1.6. Lib map (đã grep xác minh lại)

| Lib | Ai dùng | Kết luận |
|---|---|---|
| `swiper` | **header (site-wide)** + 16 file section | 🔴 **CORE** — không cắt được (§1.5) |
| `lenis` | `assets/js/app.js` | **CORE** |
| `gsap-core` + `ScrollTrigger` | `home-page/section-{about,gallery,products}`, `components/section-events`, `components/marquee` — **KHÔNG có faqs**, xem §10.1 | **per-page** ✅ |
| `gsap-custom-ease` | **CHỈ** `home-page/section-about` | **chỉ home-page** ✅ |
| `fancybox` | chỉ `single-product` | đã conditional ✅ |
| `nouislider` | chỉ `product-listing` | đã conditional ✅ |
| `aos`, `gsap-scroll-smoother` | không ai | ✅ đã xoá (Phase 0) |

**Page-group KHÔNG cần GSAP** — **7** group (121 KB raw / **48 KB gzip**):
`faqs`, `blog-list`, `single-blog`, `single-product`, `product-listing`, `search-page`, `contact-page`.

**Page-group CẦN GSAP** — **5** group: `home-page` (+CustomEase), `about-us`
(qua `components/section-events`), `service-makeup`, `service-pgpb`, `service-take-photo-page`
(3 cái sau chỉ vì `components/marquee`).

> ⚠️ `faqs` đã bị **chuyển sang nhóm KHÔNG cần** — bản đầu ghi sai, xem §10.1.

---

## 2. Kiến trúc mới — manifest là nguồn sự thật duy nhất

### 2.1. Vì sao bỏ `@import` / `import {}`

Bản `vietdung` phải viết **cả một cỗ máy** chỉ để gỡ import lúc runtime:
`okhub_bundle_walk_css()` đệ quy expand `@import`, `okhub_bundle_walk_js()` inline `import "…"`.
Và nó **vẫn bail** khi gặp ESM thật:

```php
// bundler.php:331-337 (vietdung)
if ( preg_match( '/^[ \t]*export[\s{]/m', $source ) )      return false;
if ( preg_match( '/^[ \t]*import\s+[^\'"]/m', $source ) )  return false;
```

`yuncosplay` dùng ESM thật → nếu giữ nguyên cách viết, bundler buộc phải có **ESM registry
transform** bằng regex — phần rủi ro nhất của cả kế hoạch.

**Bỏ import trong source thì toàn bộ rủi ro đó biến mất**: bundler chỉ còn nối file + bọc IIFE.

### 2.2. 🔑 IIFE wrap là BẮT BUỘC — đã có 4 cụm trùng tên top-level

Nối thẳng thành classic script sẽ ném `SyntaxError` và **giết cả bundle**. Đã grep ra:

| Tên | Số file khai báo trùng |
|---|---|
| `sectionBannerScripts` | **4** — `home-page/section-banner`, `faqs/section-banner`, `service-take-photo-page/section-banner`, `service-take-photo-page/section-intro-service` |
| `sectionServicesScripts` | **3** — `home-page/`, `about-us/`, `service-take-photo-page/` |
| `sectionAboutScripts` | 2 — `home-page/section-about`, `about-us/section-about` |
| `sectionEventsScripts` | 2 — `home-page/section-events`, `components/section-events` |

> `service-take-photo-page/section-banner` và `section-intro-service` cùng export **đúng tên
> `sectionBannerScripts`** — hiện ESM che được, bundle classic thì nổ.

### 2.3. Refactor section script: `export function` → tự init

```js
// TRƯỚC — cần aggregator gọi hộ
export function sectionBannerScripts() {
  const container = document.querySelector('#banner')
  if (!container) return
  /* … */
}

// SAU — tự init, guard DOM y như cũ
document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector('#banner')
  if (!container) return
  /* … */
});
```

Giữ `DOMContentLoaded` (thay vì chạy thẳng top-level như `vietdung`) để **timing không đổi**:
hiện aggregator đang gọi trong `DOMContentLoaded`. Đây là lựa chọn ít rủi ro nhất.

### 2.4. Phân loại aggregator JS (đã đọc từng file)

**XOÁ — aggregator thuần, 0 logic riêng (6 file):**

| File | Nội dung |
|---|---|
| `home-page/assets/scripts.js` | 7 `import {}` + 1 `import ""` + gọi |
| `about-us/assets/scripts.js` | 5 `import {}` + gọi |
| `faqs/assets/scripts.js` | 2 `import {}` + gọi |
| `service-take-photo-page/assets/scripts.js` | 2 `import {}` + 1 `import ""` + gọi |
| `components/assets/scripts.js` | `initProduct()` + `initMarqueeImages()` trong DOMContentLoaded |
| `contact-page/assets/scripts.js` | **chỉ 2 `import ""` side-effect**, 0 logic |

**GIỮ — còn logic thật (2 file):**

| File | Logic |
|---|---|
| `layouts/header/assets/scripts.js` | chọn desktop/mobile theo `window.innerWidth < 640` |
| `product-listing/assets/scripts.js` | ~180 dòng logic filter thật |

### 2.5. Binding dùng chung → `scope: 'global'` (**6 file**)

Chỉ những file mà **aggregator còn sống** gọi tới mới cần global. Section tự init thì không cần.

| File | Binding | Ai dùng |
|---|---|---|
| `layouts/header/assets/auto-hide-on-scroll.js` | `createAutoHideOnScroll` | header-desktop, header-mobile |
| `layouts/header/assets/header-desktop/scripts.js` | `headerDesktopInit` | `header/assets/scripts.js` |
| `layouts/header/assets/header-mobile/scripts.js` | `headerMobileInit` | `header/assets/scripts.js` |
| `components/product/scripts.js` | `initProduct` | site-wide + `product-listing/section-content` (re-init sau filter) |
| `product-listing/section-sidebar/assets/scripts.js` | `sectionSidebarScripts`, `getActiveFilters`, `expandHeight`, `collapseHeight` | `product-listing/assets` |
| `product-listing/section-content/assets/scripts.js` | `sectionContentScripts` | `product-listing/assets` |

→ Bỏ `export`, để binding top-level, khai báo `[ 'src' => …, 'scope' => 'global' ]` trong manifest.

> `components/marquee/scripts.js` **KHÔNG** cần global: nó chỉ được `components/assets/scripts.js`
> (sắp xoá) gọi → cho tự init là xong.

---

## 3. Kiến trúc đích

```
import-assets/
├── asset-manifest.php    # MỞ RỘNG — thêm core_js / pages_js / libs
├── bundler.php           # MỚI — nối file, cache content-hash trong uploads/okhub-assets
├── import-css-js.php     # VIẾT LẠI — enqueue mỏng
└── reset-css-js.php      # giữ nguyên
```

Giữ đặc tính của bản `vietdung`:
- **Không build step** — sinh lúc runtime, workflow "sửa file → F5" không đổi
- **Fingerprint theo nội dung** (md5 10 ký tự) — deploy git/rsync đổi `mtime` nhưng nội dung
  không đổi → tên file đứng yên → browser không tải lại
- **Output vào `uploads/okhub-assets`** — thư mục theme có thể read-only trên production
- **Ghi atomic + prune giữ 5 bản** — tránh 404 CSS khi full-page cache còn trỏ hash cũ (§5.14)
- **Fail-safe** — lỗi → `null` → fallback enqueue rời
- **Tắt debug**: `define('OKHUB_BUNDLE', false)` trong `wp-config.php`

**Bỏ so với `vietdung`** (nhờ §2.1): `walk_css` expand `@import`, `walk_js` inline import, ESM guard.
**Vẫn giữ bắt buộc**:
- `rewrite_urls` — bundle nằm ở `uploads/` nên mọi `url(../images/…)` sẽ vỡ
- chuẩn hoá **CRLF → LF** — ~10 file đang là CRLF (§5.8)
- **IIFE wrap** — §2.2

---

## 4. Các bước còn lại

### Phase 1b — JS: bỏ `import {}` ⏱ nặng nhất

1. Convert ~20 file `export function/const` → self-init `DOMContentLoaded` (§2.3)
2. Xoá 6 aggregator thuần (§2.4)
3. 6 file `scope: 'global'` — bỏ `export`, giữ binding top-level (§2.5)
4. **Xoá `enqueue_featured_news_assets()`** (`functions.php:599-620`) → hết double-load
   `app.js` + Swiper (§5.10). `blog-list/featured-section/assets/scripts.js` chuyển vào
   page-group `blog-list`.
5. `import-css-js.php`: JS chuyển sang dep-chain + `okhub_file_version()` (mtime) như CSS

**Cổng nghiệm thu**: site chạy **y hệt** với enqueue rời, `node --check` sạch, console 0 error.
**Chưa đụng bundler ở bước này** — tách rủi ro làm 2.

### Phase 2 — Manifest JS

- `okhub_asset_core_js()`, `okhub_asset_pages_js()`, `okhub_asset_libs()`
- Tái dùng `okhub_current_page_key()` đã có (12 page key, đã verify ở Phase 1a)
- Giữ thứ tự `lib → core → page` (§1.4)

### Phase 3 — `bundler.php` + `import-css-js.php` viết lại

- Nối file + IIFE + content-hash + prune + fallback (§3)
- `wp_localize_script` phải đổi target sang handle bundle (§5.1)

### Phase 4 — Per-page libs

⚠️ **Giá trị đã giảm mạnh so với plan cũ** — Swiper ở lại core (§1.5).
Còn lại: tách GSAP khỏi 6 page-group không dùng → **−115KB** ở `blog-list`, `single-blog`,
`single-product`, `product-listing`, `search-page`, `contact-page`.

### Phase 5 — Verify (Chrome DevTools MCP)

Mỗi page key: đếm request / byte / console error, so với baseline §1.0.

---

## 5. Cạm bẫy đã nhận diện

1. **`wp_localize_script('header', 'wpApiSettings', …)`** (`import-css-js.php:416`)
   Khi `header` scripts bị nuốt vào bundle `okhub-core`, handle `header` **không còn tồn tại**
   → `wp_localize_script` im lặng không làm gì → `wpApiSettings` undefined → **search + REST API chết**.
   → Phải đổi target sang handle bundle mới, giữ đúng cả ở đường fallback.
   (Bản `vietdung` từng dính, xem ghi chú `import-css-js.php:245-250`.)

2. **`is_page_template('front-page.php')`** — đọc meta `_wp_page_template`; trang chủ chọn qua
   template hierarchy sẽ trả `false` → mất CSS/JS. ✅ Resolver đã dùng `is_front_page()` (Phase 1a).

3. **Trang dịch vụ: Page template mới là thật, CPT `service` single thì hỏng sẵn**

   **Trang dịch vụ thật = 3 WP Page**, published, gán page template:

   | Page | slug | template | → page key |
   |---|---|---|---|
   | Dịch vụ Makeup (10315) | `dich-vu-makeup` | `service-makeup-page.php` | `service-makeup` |
   | Dịch vụ PG/PB (10385) | `dich-vu-pg-pb` | `service-pgpb-page.php` | `service-pgpb` |
   | Dịch vụ chụp ảnh (10438) | `dich-vu-chup-anh` | `service-take-photo-page.php` | `service-take-photo-page` |

   **Riêng CPT `service` single hỏng sẵn** — `single-service.php` tìm
   `has_term('dich-vu-makeup'…, 'service_category')` nhưng đó là **slug của PAGE**, không phải
   slug term. Slug term thật: `makeup` / `pg-pb` / `combo-chup-anh` → cả 3 render "Không có dịch vụ".
   Bản cũ vẫn kéo **cả 3 bộ CSS (21 file)** cho trang không render gì.
   Resolver mới trả `null` → 0 page CSS. Không ảnh hưởng hình ảnh.

4. **WooCommerce guard** — `is_shop()` / `is_product_taxonomy()` fatal nếu Woo tắt
   → ✅ đã bọc `function_exists()` (Phase 1a).

5. **`single-blog-script` dep `jquery`** (`import-css-js.php:308`) — vào bundle page thì dep phải giữ.

6. **`faqs` có 2 điều kiện template**: `faqs.php` || `Faqs.php` → ✅ resolver giữ cả hai.

7. **`import-order.php`** — tên gây hiểu nhầm, **không liên quan asset**; là REST API import đơn hàng
   WooCommerce. Không đụng.

8. **File CRLF** (~10 file: `single-blog/*`, `contact-page/section-contact-info/*`, `blog-list/*`)
   → bundler phải chuẩn hoá CRLF→LF trước khi nối, nếu không regex neo cuối dòng trượt.

9. **Rác trong `template-parts/`**: `__MACOSX/`, `product-listing.zip`, `service-makeup.zip`,
   `service-details/index.php` (0B) → dọn ở commit riêng.

10. **🐛 BUG CÓ SẴN — `functions.php:599-620` load trùng asset trên MỌI trang** *(verify live §1.0)*

    `enqueue_featured_news_assets()` hook `wp_enqueue_scripts` **không có điều kiện**:

    | Thứ | Hậu quả |
    |---|---|
    | `swiper-css` + `swiper-js` (CDN swiper@11) | **Swiper load 2 lần** — đã có bản local ở `wp_enqueue_lib()` |
    | `featured-scripts` (`blog-list/featured-section`) | load mọi trang, chỉ trang `blogs` cần |
    | `app-script` → `/assets/js/app.js` | **app.js load 2 lần** → `new App()` chạy 2 lần → **2 instance Lenis + 2 vòng RAF** |

    → Xoá ở Phase 1b.

11. **Orphan: `home-page/section-events/`** — `home-page/assets/scripts.js:5` import
    `sectionEventsScripts` từ **`components/section-events/`**, không phải `home-page/section-events/`.
    CSS manifest cũng chỉ liệt kê bản `components/`. → `home-page/section-events/` là **rác**, xác minh rồi xoá.

12. **✅ FIXED — `service-makeup` mất CSS section intro** (bug có sẵn, manifest làm lộ ra)
    `service-makeup/assets/styles.css:2` comment mất `@import "../intro-service/…"` trong khi
    `index.php` vẫn render section đó → chạy không CSS. Đã thêm lại vào manifest, verify 200 / 4667 bytes.

13. **🐛 ACF/template — 3 trang service mất data (NGOÀI phạm vi asset, chưa sửa)**
    - `service-makeups/index.php:38` dùng `isset($services_list)` rồi `foreach`. `get_field()` trả
      `false` khi rỗng, mà **`isset(false) === true`** → `foreach(false)` → `E_WARNING`. Phải dùng `!empty()`.
    - Chụp ảnh (10438): data nằm ở `service_service_list`, template vẫn đọc `service.services`
      → **ACF field group đã đổi cấu trúc (`services` → `service_list`) nhưng template chưa cập nhật.**

14. **WP Rocket đang BẬT** → full-page cache là **có thật**. Cơ chế `prune` giữ 5 bản bundle (§3) là
    **bắt buộc**: sửa CSS → hash mới → HTML đã cache trỏ file cũ; xoá ngay bản cũ → **404 CSS** cho
    khách đang ăn HTML-cache, kéo dài tới khi purge.

---

## 6. Quyết định đã chốt

1. **Xoá AOS** ✅ — không có element `data-aos`, init là no-op
2. **Xoá ScrollSmoother** ✅ — `handleInitializeGSAP()` comment out, không bao giờ chạy
3. **Bỏ `@import` CSS + `import {}` JS**, xử lý toàn bộ qua manifest ✅
4. **Swiper ở lại core** 🆕 — header dùng site-wide, đã verify (§1.5)
5. **Không commit** — working tree đang có ~124 file WIP; bạn tự commit khi dọn xong

---

## 7. Kỳ vọng kết quả (đã hiệu chỉnh theo §1.5)

| | Trước (đo thật §1.0) | Sau |
|---|---|---|
| Request asset trang chủ | **68** (38 CSS + 30 JS) | **~8** (core.css/js + page.css/js + swiper + lenis + gsap×2) |
| `app.js` | 2 lần 🐛 | 1 |
| Swiper | 2 lần (local + CDN) 🐛 | 1 |
| Lib chết mọi trang (AOS + ScrollSmoother) | ~55 KB | 0 ✅ |
| GSAP ở 6 page-group không dùng | +115 KB | 0 |
| ~~Swiper ở 5 page-group~~ | ~~+168 KB~~ | ❌ **không cắt được** (§1.5) |
| Waterfall `@import` / ESM | 2 tầng | 0 |
| Cache khi sửa 1 file | hỏng (version tĩnh) | đúng (hash nội dung) |
| Thứ tự cascade | rải rác 13 file | 1 chỗ (manifest) |

**Việc còn để ngỏ**: muốn cắt nốt 168KB Swiper thì phải lazy-load lúc mở mega-menu
(`import()` động hoặc chèn `<script>` on-demand) — refactor riêng, **không nằm trong plan này**.

---

## 8. Phase 1b — nhật ký thực thi (2026-07-16)

### 8.1. Đã làm

| Việc | Chi tiết |
|---|---|
| Gỡ ESM | 0 `import`/`export` còn lại trong file được enqueue. `node --check` (chế độ classic script) **68/68 pass** |
| `scope: 'global'` | **7** file (nhiều hơn 6 như dự kiến ở §2.5 — thêm `blog-list/featured-section`, xem §8.3) |
| Self-init | 18 file `export function` → `DOMContentLoaded` tự gọi |
| Xoá aggregator thuần | **7** file (§2.4 đếm 6 — sót `single-product/assets/scripts.js`) |
| Giữ aggregator còn logic | `layouts/header/assets/scripts.js`, `product-listing/assets/scripts.js` |
| Manifest JS | `okhub_asset_core_js()` (12 file) + `okhub_asset_pages_js()` (12 group) + `okhub_asset_page_lib_js()` + `okhub_asset_page_js_deps()` |
| Xoá bug double-load | `enqueue_featured_news_assets()` (`functions.php:599`) — app.js 2→1, swiper CDN 1→0 |
| Xoá `add_type_attribute` | Không còn handle nào cần `type="module"`; filter cũ còn dựng lại thẻ `<script>` từ đầu → nuốt `id` + data `wp_localize_script` |
| `wp_localize_script` | Đổi target `'header'` (handle đã biến mất) → handle **ĐẦU** của core. Verify runtime: `wpApiSettings = {nonce, root}` ✅ |
| Cache-bust JS | JS nay dùng `okhub_file_version()` = `filemtime` (trước là `THEME_VERSION` tĩnh → §1.2 hỏng) |

### 8.2. 🔴 Phát hiện nguy hiểm — `const rootFontSize` khai báo 2 lần trên trang chủ

`components/section-events` và `home-page/section-products` **mỗi file tự định nghĩa lại**
`const rootFontSize` + `function remToPixels` (code giống hệt nhau, và giống bản đã có sẵn
ở `utils.js`).

Là ESM thì mỗi file 1 scope riêng nên vô hại. Chuyển sang **classic script** thì cả 2 vào chung
global scope → `SyntaxError: Identifier 'rootFontSize' has already been declared` → **chết toàn
bộ JS trang chủ**. Đây chính là rủi ro §2.2 nhưng ở dạng `const` (fatal), không phải `function`
(ghi đè im lặng).

→ **Đã xoá bản copy local ở cả 2 file**, dùng `remToPixels` global của `utils.js` (core in trước page).

> Đã quét toàn bộ 12 page-group tìm trùng tên top-level. Chỉ còn `escapeHtml` trùng ở
> `product-listing/section-content` + `product-listing/assets` — nhưng cả 2 là `function`
> **giống hệt nhau từng byte** ⇒ ghi đè vô hại, để nguyên.

### 8.3. 🔴 `initAllSwipers` — dependency suýt gãy

`blog-list/assets/scripts.js:217` gọi `initAllSwipers()`, hàm này định nghĩa ở
`blog-list/featured-section/assets/scripts.js`. Trước đây nó chạy được **chỉ nhờ** bug
`enqueue_featured_news_assets()` nạp featured-section site-wide.

Xoá hàm bug mà không xử lý → `ReferenceError: initAllSwipers is not defined` → **trang blogs chết**.

→ featured-section đưa vào page-group `blog-list`, đứng **trước** `blog-list/assets/scripts.js`,
đánh dấu `scope: 'global'`. Verify runtime: `initAllSwipers = "function"`, 4 swiper init, 60 blog card ✅

### 8.4. Verify

**Tập file** — dựng lại ESM graph cũ rồi so với tập file thật đang load:
**12/12 page-group KHỚP TUYỆT ĐỐI** (không thiếu, không thừa).

**Thứ tự** — thứ tự in ra khớp manifest 100%. Các điểm tinh vi đã giữ đúng:
- `home-page/section-gallery` chạy **trước** 7 section kia (bản cũ nó là `import "…"` side-effect,
  code chạy top-level ⇒ luôn trước) → xếp đầu list
- `about-us` theo thứ tự **GỌI** (banner → partners → about → services → events), không phải thứ tự import
- nhóm `header` + `components` xếp **sau** footer/cta (bản cũ là module ⇒ defer ⇒ chạy sau classic)

**Runtime (Chrome DevTools MCP)** — 12/12 trang: **0 lỗi JS**, 0 lỗi PHP, HTTP 200.
- shop: 7 binding cross-file resolve, noUiSlider init, 20 product card, `wpApiSettings` đủ nonce+root
- blog-list: `initAllSwipers` OK, 4 swiper
- single-product: `Fancybox` OK, 3 swiper, 16 related product
- service-makeup (manifest JS rỗng): marquee vẫn chạy nhờ core ✅

> ⚠️ **WP Rocket cache** làm sai lệch verify: `curl` trả HTML cache cũ (`Debug: cached`) nên
> lần đo đầu thấy "không có gì đổi". Phải thêm query string (`?nocache=…`) để bypass. Nhớ khi verify Phase 3.

**404 `placeholder.webp`** thấy ở mọi trang là **bug CÓ SẴN, ngoài phạm vi**:
`inc/api.php:3` + `inc/blog-api.php:3` định nghĩa `FALLBACK_IMAGE_URL` trỏ
`/wp-content/uploads/2025/10/placeholder.webp` — file **chưa từng được upload**. Console log cũ
(`.playwright-mcp/console-2026-05-18*.log`) đã ghi nhận 404 này từ tháng 5.

### 8.5. Rác đã xác minh (0 ref) — CHƯA xoá, để commit dọn riêng

Không đưa vào manifest ⇒ hành vi không đổi. Nhưng chúng **đang render mà không có JS**:

| File | Ghi chú |
|---|---|
| `service-take-photo-page/section-intro-service/assets/scripts.js` | bản copy cũ của `section-banner` (cùng export `sectionBannerScripts`, cùng nhắm `.swiper-banner` — selector chỉ có ở section-banner) |
| `home-page/section-events/assets/scripts.js` | trùng `components/section-events`, aggregator import bản `components/` |
| `blog-list/list-section/assets/scripts.js` | biến thể cũ của `blog-list/assets/scripts.js` (gán `window.initBlogAjax` thay vì khai báo hàm) |
| `components/animated-button/scripts.js` | tự đăng ký `load`/`resize`, chưa từng được enqueue |
| `faqs|service-makeup|service-pgpb/section-mermaid-banner/assets/scripts.js` | 11 dòng, không ai nạp |
| `single-blog/related-posts|table-content-mobile/assets/scripts.js` | không ai nạp |
| `service-makeup/assets/scripts.js`, `service-pgpb/assets/scripts.js` | **0 byte** — bản cũ vẫn enqueue (2 request rỗng/trang), nay bỏ qua |
| `__MACOSX/`, `*.zip`, `service-details/index.php` (0B) | rác giải nén |

### 8.6. Còn nợ

- **`single-blog` → dep `jquery`**: đã grep, `single-blog/assets/scripts.js` **không dùng jQuery**
  (0 ref `$(`/`jQuery`). Dep thừa nhưng **vẫn giữ** để "chạy y hệt". Gỡ ở Phase 4 sau khi rà plugin.
- Số `<script src>` trong HTML **tăng** (vd home 30→37): đúng và mong đợi — request do ESM import
  sinh ra trước đây **không nằm trong HTML** (browser tự fetch qua module graph, thành waterfall).
  Nay tất cả hiện ra và **tải song song, 0 waterfall**. Phase 3 gộp lại còn ~2.

---

## 9. Phase 3 — nhật ký thực thi (2026-07-16)

### 9.1. `bundler.php` — ngắn hơn bản `vietdung` đúng như dự tính §2.1

Nhờ Phase 1a + 1b bỏ sạch `@import` / ESM, bundler **không cần**: expand `@import` đệ quy,
inline `import "…"`, ESM registry transform. Chỉ còn: **nối file + bọc IIFE + rewrite `url()`**.

Giữ nguyên các đặc tính bắt buộc:

| Cơ chế | Vì sao bắt buộc |
|---|---|
| `okhub_bundle_rewrite_urls()` | Bundle nằm ở `uploads/`, không phải thư mục file gốc ⇒ **24 `@font-face` `url("SF-Pro-Display/…")` trong `assets/fonts/stylesheet.css` sẽ 404 toàn bộ** nếu không rewrite. Đây là url() tương đối DUY NHẤT của theme (đã grep). |
| Bọc IIFE | 4 file cùng khai báo `sectionBannerScripts`, 3 file cùng `sectionServicesScripts` (§2.2) |
| `scope => 'global'` không bọc | 7 file định nghĩa binding cho file khác dùng (§2.5 + §8.3) |
| Chuẩn hoá CRLF→LF | ~10 file dùng CRLF |
| Fingerprint theo **nội dung** | deploy git/rsync đổi mtime nhưng nội dung không đổi → tên file đứng yên → browser khỏi tải lại |
| Ghi atomic + prune giữ **5** bản | WP Rocket đang bật: HTML cache còn trỏ hash cũ, xoá ngay = 404 CSS toàn site |
| Guard `@import` / ESM → bail | Lưới an toàn: nếu ai lỡ thêm import lại → trả `null` → fallback enqueue rời, **không bao giờ sinh bundle hỏng** |

> **Lưu ý deploy**: `wp-content/uploads/okhub-assets/` phải **ghi được**. Không ghi được thì
> `okhub_bundle()` trả `null` → tự fallback enqueue rời (site vẫn chạy, chỉ mất tối ưu).

### 9.2. Kết quả đo (12 trang, đã bypass WP Rocket bằng `?nocache=`)

| Trang | CSS+JS theme (trước → sau) | Byte theme |
|---|---|---|
| home | **66 → 6** | 462 KB |
| about-us | 64 → 6 | 418 KB |
| faqs | 61 → 6 | 409 KB |
| blog-list | 60 → 6 | 403 KB |
| contact | 60 → 6 | 398 KB |
| service-makeup | 63 → **5** | 411 KB |
| service-pgpb | 64 → **5** | 413 KB |
| service-takephoto | 63 → 6 | 420 KB |
| shop | 62 → 8 | 470 KB |
| search | 57 → 6 | 388 KB |
| single-product | 64 → 8 | 593 KB |
| single-blog | 59 → 6 | 408 KB |
| **TỔNG** | **743 → 74** | |

Trang thường = 6: `swiper.css` + `core.css` + `{page}.css` + `swiper.js` + `core.js` + `{page}.js`.
`service-makeup`/`service-pgpb` = 5 (manifest JS rỗng → không có page bundle JS).
`shop` = 8 (+nouislider css/js). `single-product` = 8 (+fancybox css/js).

### 9.3. Verify

**Thứ tự cascade `lib → core → page` giữ nguyên** (verify bằng DOM thật):
- single-product: `swiper → lenis → core.css → single-product.css → fancybox.css`
  (fancybox **sau** page ✅)
- shop: `swiper → lenis → core.css → nouislider.css → product-listing.css`
  (nouislider **trước** page ✅)
- shop JS: `swiper → lenis → gsap×3 → core.js → nouislider.js → product-listing.js` ✅

**Scope trong bundle** — verify bằng bare identifier trong Chrome:

| Binding | Kỳ vọng | Thực tế |
|---|---|---|
| `remToPixels`, `initProduct`, `createAutoHideOnScroll`, `headerDesktopInit`, `headerMobileInit` | thấy được (scope global) | ✅ `function` |
| `sectionSidebarScripts`, `sectionContentScripts`, `getActiveFilters`, `expandHeight`, `collapseHeight` | thấy được (page bundle) | ✅ `function` |
| `initAllSwipers` | thấy được (blog-list bundle) | ✅ `function` |
| `sectionBannerScripts`, `initMarqueeImages` | **KHÔNG** thấy (bọc IIFE) | ✅ `undefined` — mà section vẫn chạy (7 swiper) |

> ⚠️ **Bẫy khi test**: `const` top-level trong classic script nằm ở **global lexical scope**,
> KHÔNG phải property của `window`. `typeof window.headerDesktopInit` trả `undefined` dù binding
> vẫn hoạt động. Phải test bằng **bare identifier** (`typeof headerDesktopInit`).
> Chỉ `function` và `var` mới lên `window`.

**Runtime 12/12 trang**: 0 lỗi JS, 0 lỗi PHP, HTTP 200. Font `.otf` sau rewrite trả **200**.
home: 7 swiper + 14 gsap tween + 28 font. shop: noUiSlider init + 20 product card + `wpApiSettings{nonce,root}`.
blog-list: 4 swiper. single-product: Fancybox + 3 swiper.

**Đã test cơ chế:**
- **Cache-bust**: sửa `global.css` → hash `525be84478` → `63ee178227`, bản cũ vẫn giữ ✅
- **Prune**: sửa 7 lần → còn đúng **5** bản ✅
- **Fallback**: `define('OKHUB_BUNDLE', false)` → 0 ref bundle, 53 file rời, 0 lỗi ✅
  (đã khôi phục `wp-config.php` byte-for-byte)

### 9.4. Còn nợ sang Phase 4

- **GSAP vẫn load site-wide** (gsap + ScrollTrigger + CustomEase ≈115KB) — thấy rõ ở bảng §9.2:
  `search` 388KB dù không dùng GSAP. Đây chính là việc của Phase 4 (§1.6).
- **Swiper 168KB** không cắt được (§1.5) — trừ khi lazy-load mega-menu.
- `single-blog` → dep `jquery` thừa (§8.6).

---

## 10. Phase 4 — nhật ký thực thi (2026-07-16)

### 10.1. 🔴 SỬA LỖI PLAN LẦN 2 — `faqs` KHÔNG dùng GSAP (false positive)

§1.6 bản đầu xếp `faqs` vào nhóm cần GSAP. **Sai.** Nó khớp grep `ScrollTrigger` chỉ vì
`faqs/section-banner/assets/scripts.js:20` có **hàm local** tên `handleScrollTrigger`:

```js
const handleScrollTrigger = (e) => { e.preventDefault(); scrollToFaq(); };
faqSearchForm.addEventListener('submit', handleScrollTrigger);
```

Không liên quan gì GSAP ScrollTrigger. File này scroll bằng **Lenis** (`window.app.lenis`),
fallback `window.scrollTo`. → `faqs` chuyển sang nhóm **không cần GSAP**.

> Bài học: grep tên lib phải soi lại từng file. Đây là **lỗi thứ 2** của plan gốc cùng dạng
> (lỗi 1: Swiper site-wide, §1.5). Cả 2 đều là suy luận từ grep mà không đọc code.

### 10.2. Danh sách GSAP cuối cùng (đã đọc từng file, không chỉ grep)

Đúng **5 file** gọi GSAP thật:

| File | Dùng gì | Kéo theo page-group |
|---|---|---|
| `components/marquee/scripts.js` | `gsap.set`, `gsap.to` | **CORE** → nhưng chỉ render ở 4 group (dưới) |
| `components/section-events/assets/scripts.js` | gsap + ScrollTrigger | home-page, about-us |
| `home-page/section-about/assets/scripts.js` | gsap + **CustomEase** | home-page |
| `home-page/section-gallery/assets/scripts.js` | gsap | home-page |
| `home-page/section-products/assets/scripts.js` | gsap + ScrollTrigger | home-page |

`components/marquee` render bởi (đã grep `get_template_part`): `home-page/section-gallery`,
`service-makeup/feedback`, `service-pgpb/feedback`, `service-take-photo-page/section-change`.
**Không có** ở `layouts/` ⇒ không site-wide.

⇒ `okhub_asset_page_libs()`: home-page `[gsap, gsap-custom-ease]`; about-us / service-makeup /
service-pgpb / service-take-photo-page `[gsap]`. 7 group còn lại: **không lib nào**.

**CustomEase chỉ home-page** — trước đây load ở cả 12 trang.

### 10.3. Ràng buộc ngầm: marquee ở CORE nhưng cần GSAP

`components/marquee/scripts.js` nằm trong core bundle (site-wide) mà lại cần GSAP — trên 7 trang
không có GSAP nó vẫn được tải. **Không lỗi**: `initOneMarqueeImage()` đã tự guard sẵn
`if (!marquee || !window.gsap) return;` (dòng 30). Đã verify: faqs có `gsap === undefined`,
0 marquee element, 0 lỗi console.

Giữ marquee ở core (thay vì đẩy xuống 4 page bundle) vì: core là **1 bundle cache chung mọi
trang** → tốn ~3KB đúng 1 lần; đẩy xuống page thì nhân bản 4 lần. Đổi lại phải nhớ:
**thêm marquee vào trang mới ⇒ phải khai báo `gsap` cho group đó**, nếu không marquee render ra
mà đứng im (không animate, không báo lỗi). Đã ghi cảnh báo này ngay trong `okhub_asset_page_libs()`.

### 10.4. Kết quả đo (theme + CDN, **gzip** — đúng thứ browser tải)

| Trang | Request 0 → giờ | gzip | Phase 4 tiết kiệm |
|---|---|---|---|
| home | 66 → 11 | 152 KB | 0 (cần đủ gsap+CE) |
| about-us | 64 → 10 | 139 KB | −3 KB (bỏ CustomEase) |
| **faqs** | 61 → 8 | **92 KB** | **−48 KB (bỏ GSAP)** |
| blog-list | 60 → 8 | 92 KB | −48 KB |
| contact | 60 → 8 | 92 KB | −48 KB |
| service-makeup | 63 → 9 | 138 KB | −3 KB |
| service-pgpb | 64 → 9 | 138 KB | −3 KB |
| service-takephoto | 63 → 10 | 141 KB | −3 KB |
| shop | 62 → 10 | 110 KB | −48 KB |
| search | 57 → 8 | 90 KB | −48 KB |
| single-product | 64 → 10 | 144 KB | −48 KB |
| single-blog | 59 → 8 | 92 KB | −48 KB |
| **TỔNG REQUEST** | **743 → 109** | | |

> ⚠️ **Đừng so với bảng §9.2**: bảng đó chỉ đếm file `okhub-theme|okhub-assets`, **không tính lib CDN**
> (GSAP/Lenis) nên byte thấp hơn một cách giả tạo. Bảng này tính **cả CDN + gzip** — thước đo đúng.
>
> GSAP thật: gsap 72.8KB + ScrollTrigger 44.2KB + CustomEase 7.1KB = **121 KB raw / 48 KB gzip**.
> Con số "≈115KB" ở §1.6 là **raw**, không phải byte qua dây.

### 10.5. Verify

GSAP chỉ xuất hiện đúng 5 trang (đếm thẻ `<script>`), 7 trang còn lại = 0.

Runtime 12/12 trang: **0 lỗi JS**.
- home: `gsap`+`ScrollTrigger`+`CustomEase` đủ, 14 tween, 5 ScrollTrigger, marquee animate, 7 swiper
- about-us: gsap ✓, `CustomEase` **undefined** ✓ (đúng — chỉ home-page), 5 tween, 6 swiper
- service-makeup: gsap ✓, CustomEase undefined ✓, marquee animate (1 tween) ✓
- faqs: `gsap`/`ScrollTrigger` **undefined** ✓, 0 marquee, form + 287 FAQ item + 2 swiper vẫn chạy ✓
- shop / search / contact / blog-list / single-blog / single-product: 0 lỗi

### 10.6. Việc còn để ngỏ

| Việc | Giá trị | Vì sao chưa làm |
|---|---|---|
| Lazy-load Swiper ở mega-menu | −168 KB raw cho mọi trang | Refactor header, ngoài phạm vi plan (§1.5) |
| Gỡ dep `jquery` của `single-blog` | −90 KB ở single-blog | Script không dùng jQuery (đã grep) nhưng phải rà plugin trước (§8.6) |
| Dọn rác 8 nhóm file 0-ref | gọn repo | Để commit riêng (§8.5) |
| `FALLBACK_IMAGE_URL` 404 | hết 404 mọi trang | Bug nội dung, ngoài phạm vi asset (§9.3) |

---

## 11. Phase 6 — Self-host toàn bộ CDN (2026-07-16)

> Ngoài plan gốc. Trước đó theme lẫn lộn: swiper/fancybox/nouislider đã local, còn GSAP/Lenis/font
> vẫn CDN.

### 11.1. Vì sao — lý do thật KHÔNG phải "tiết kiệm byte"

Byte gần như **không đổi** (cùng file, khác host). Cái được là:

1. **Bỏ 3 host ngoài ⇒ bỏ 3 × (DNS + TCP + TLS handshake)** trên đường critical.
   Trước: `cdn.jsdelivr.net`, `unpkg.com`, `fonts.googleapis.com` (+`fonts.gstatic.com` cho file font).
   Trên LocalWP không thấy gì, nhưng mạng thật (3G/4G VN) mỗi handshake dễ 100–300ms.
   Nay tất cả cùng host với bundle ⇒ tái dùng connection sẵn có, **0 handshake mới**.
2. **Lý do "CDN cache dùng chung giữa các site" ĐÃ CHẾT** — từ ~2020 browser **partition HTTP cache
   theo site**. File GSAP user tải ở site khác **không** dùng lại được cho site này. Lợi ích duy nhất
   còn lại của CDN là đỡ băng thông server.
3. **GDPR** — Google Fonts serve từ `fonts.gstatic.com` = chuyển IP người dùng sang Google.
4. **Không chết theo bên thứ 3** — jsdelivr/unpkg down hoặc bị chặn thì site vẫn chạy.

### 11.2. Đã kéo về

| Trước | Sau | Byte |
|---|---|---|
| `cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js` | `/assets/js/gsap.min.js` | 72.8 KB |
| `…/ScrollTrigger.min.js` | `/assets/js/ScrollTrigger.min.js` | 44.2 KB |
| `…/CustomEase.min.js` | `/assets/js/CustomEase.min.js` | 7.1 KB |
| `unpkg.com/lenis@1.3.17/dist/lenis.min.js` | `/assets/js/lenis.min.js` | 17.0 KB |
| `unpkg.com/lenis@1.3.17/dist/lenis.css` | `/assets/css/lenis.css` | 0.4 KB |
| `fonts.googleapis.com/css2?family=Phudu:wght@300..900` | `/assets/fonts/phudu.css` + `/assets/fonts/phudu/*.woff2` | 49.9 KB (4 subset) |

Giấy phép: GSAP = GreenSock standard (cho phép self-host), Lenis = MIT, Phudu = SIL OFL. Header
license trong file min đã giữ nguyên.

### 11.3. Font Phudu — variable font, 4 subset

Google trả về **4 `@font-face`**, tất cả `font-weight: 300 900` ⇒ **variable font, 1 file phủ cả dải
300–900**, không cần mỗi weight 1 file. (CSS theme dùng weight 300→900; có vài chỗ 100/200 —
browser tự clamp về 300.)

Giữ **cả 4 subset + `unicode-range` y hệt Google** ⇒ parity tuyệt đối, browser chỉ tải subset nó cần:

| Subset | Byte | Thực tế tải? |
|---|---|---|
| vietnamese | 6.5 KB | ✅ (bắt buộc — site tiếng Việt) |
| latin | 26.5 KB | ✅ |
| latin-ext | 15.1 KB | ✅ |
| cyrillic-ext | 1.8 KB | ❌ `unloaded` — `unicode-range` chặn, không bao giờ fetch |

`assets/fonts/phudu.css` **sinh tự động từ CSS gốc của Google**, không gõ tay `unicode-range`
(đã assert chuỗi vietnamese khớp 100%).

`phudu.css` nằm trong `okhub_asset_core_css()` ⇒ **gộp thẳng vào `core.css` bundle** ⇒ bớt luôn
1 request stylesheet render-blocking từ host ngoài. Bundler tự rewrite `url(phudu/*.woff2)`
thành URL tuyệt đối (§9.1).

### 11.4. Verify

- **0 host ngoài**: `performance.getEntriesByType("resource")` lọc host ≠ location.host → `{}` (rỗng).
- Font: 3 subset `loaded`, `cyrillic-ext` `unloaded` ✅. Weight `300 900` ✅.
  Đo canvas chữ tiếng Việt: Phudu 577px vs fallback serif 595px ⇒ **font thật sự ăn**, không phải fallback.
- woff2 resolve 200; GSAP chạy từ `/assets/js/gsap.min.js`; marquee vẫn animate.
- 12/12 trang HTTP 200, 0 lỗi JS.
- Đã gỡ `my_add_preconnects()` (preconnect googleapis + gstatic) — nay vô nghĩa vì không còn request nào tới đó.

### 11.5. ⚠️ Còn 1 preconnect gstatic — do **WP Rocket**, không phải theme

`plugins/wp-rocket/inc/Engine/Optimization/GoogleFonts/Subscriber.php:56,84` hook
`wp_resource_hints` và add `<link rel=preconnect href="https://fonts.gstatic.com">` **vô điều kiện**.

Nay theme không còn dùng Google Fonts ⇒ preconnect này **thừa**: bắt browser mở TCP+TLS tới host
không bao giờ dùng, tranh băng thông với request thật. Nó **không phải request thật** nên
`performance` vẫn báo 0 resource ngoài — chỉ là thẻ `<link>` phí.

→ **Sửa đúng chỗ**: tắt option **"Optimize Google Fonts"** trong WP Rocket settings.
KHÔNG nên viết filter ở theme để gỡ — đó là đánh nhau với plugin.
