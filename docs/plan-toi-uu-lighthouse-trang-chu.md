# Plan: Tối ưu Lighthouse trang chủ

> **Trạng thái**: Chờ duyệt · **Ngày**: 17/07/2026
> **Mục tiêu**: Desktop > 95, Mobile > 90 (Lighthouse Performance)

---

## 1. Context

Trước khi lên plan, đã đo baseline thật bằng `npx lighthouse@12` trên `yuncosplay.local`. Kết quả cho thấy **hai điều làm thay đổi hoàn toàn hướng đi**.

### 1.1. Desktop đã đạt mục tiêu rồi — con số 90 là artifact của LocalWP

| Giao thức | Desktop | Mobile |
|---|---|---|
| `http://` (HTTP/1.1) | 90 | 67 |
| `https://` (HTTP/2) | **99** ✅ | **75** |

LocalWP phục vụ `http://` bằng HTTP/1.1 nhưng `https://` bằng HTTP/2. Trên HTTP/1.1, giới hạn 6-connection làm ảnh hero `fetchpriority=high` bị trì hoãn **5713 ms** — một vấn đề *không tồn tại* trên production (mọi host thật đều có HTTP/2). Lighthouse cũng tự flag "Use HTTP/2: 1210 ms".

→ **Mọi phép đo từ giờ phải chạy trên `https://yuncosplay.local/`.** Đo trên `http://` sẽ đuổi theo một con ma và dẫn đến tối ưu sai chỗ.

→ **Desktop 99/100 — không cần làm gì.** Toàn bộ plan này nhắm vào mobile.

### 1.2. Mobile 75 → cần +15 điểm, và tất cả nằm ở một chỗ

Phân rã điểm mobile (đo trên HTTP/2):

| Metric | Giá trị | Score | Trọng số | Điểm |
|---|---|---|---|---|
| FCP | 2.7 s | 0.61 | 10% | 6.1 |
| Speed Index | 4.0 s | 0.80 | 10% | 8.0 |
| **LCP** | **5.0 s** | **0.26** | **25%** | **6.5** |
| TBT | 120 ms | 0.97 | 30% | 29.1 |
| CLS | 0.001 | 1.00 | 25% | 25.0 |

**TBT và CLS đã gần như hoàn hảo** — công tối ưu asset trước đây (`docs/PLAN-optimize-import-css-js.md`) đã làm tốt phần này, **đừng làm lại**. Điểm mất chỉ ở FCP / SI / LCP, và cả ba đều bị gate bởi **cùng một nút thắt: Render Delay 4422 ms** (88% của LCP 5.0 s). Ảnh hero tải xong tức thì (Load Delay 0 ms, Load Time 0 ms) nhưng không vẽ được trong 4.4 giây.

Main-thread breakdown mobile: `Other 1897 ms`, `Style & Layout 766 ms`, `Script Evaluation 614 ms`, `Rendering 492 ms`.

### 1.3. Đường tới >90

| Thay đổi | Điểm cộng |
|---|---|
| Baseline | **75** |
| LCP 5.0 s → ~2.5 s (0.26 → 0.85) | **+14.8** |
| FCP 2.7 s → ~1.8 s (0.61 → 0.88) | +2.7 |
| SI 4.0 s → ~2.8 s (0.80 → 0.92) | +1.2 |
| **Dự kiến** | **~93** ✅ |

---

## 2. Phase 1 — Ảnh nền CSS khổng lồ

**Tác động lớn nhất. Rủi ro thấp nhất. Làm đầu tiên.**

Đây là nguyên nhân số 1 của Render Delay. Các ảnh nền là CSS `background-image` nên **không có `srcset`** — trình duyệt luôn decode full-size bất kể viewport:

| File | Kích thước | Dung lượng | Megapixel | RAM raster |
|---|---|---|---|---|
| `2026/02/concept.webp` | **3200 × 15892** | 418 KB | **50.9 MP** | **~203 MB** |
| `2026/02/backgroundimage-4.webp` | 3200 × 4864 | 111 KB | 15.6 MP | ~62 MB |
| `2026/02/backgroundimage-5.webp` | 750 × 3974 | 23 KB | 3.0 MP | ~12 MB |

`concept.webp` là resource lớn nhất trang (418 KB) **và** bị WP Rocket preload `fetchpriority="high"` — nghĩa là nó vừa chiếm băng thông, vừa tranh ưu tiên với ảnh hero thật, rồi bắt CPU mobile (throttle 4×) decode 50.9 megapixel. Trong khi nó chỉ là backdrop `background-size: cover` cho `<main>`.

### Việc cần làm — sửa `template-parts/home-page/assets/styles.css`

1. **Resize `concept.webp`**: giữ tỉ lệ 1:4.97, xuất bản desktop ~1200×5960 và bản mobile ~600×2980. Vì `background-size: cover` + đây là texture mềm, giảm độ phân giải không nhìn ra khác biệt. Mục tiêu < 60 KB/bản. Decode giảm ~7×.
2. **Resize `backgroundimage-4.webp`** → ~1200×1824.
3. **Thêm biến thể mobile cho `main`** trong `@media (max-width: 639px)` — hiện tại chỉ `.sections__bottom` (dòng 24) có bản mobile, còn `main` (dòng 12) bắt điện thoại tải nguyên bản 3200px.
4. Cân nhắc `image-set()` để tách bản desktop/mobile sạch hơn.

### Lưu ý

- URL trong CSS đang hardcode `/wp-content/uploads/2026/02/...` (dòng 4, 12, 24) — giữ nguyên pattern này khi thay file để không phá bundler.
- **Đặt tên file mới** thay vì ghi đè, tránh cache cũ.
- `concept.webp` còn được dùng ở `service-makeup/assets/styles.css:5` và `service-pgpb/assets/styles.css:5` — kiểm tra 2 trang đó sau khi thay.

---

## 3. Phase 2 — Gỡ render-blocking không dùng khỏi trang chủ

`<head>` trang chủ đang có **6 script render-blocking**, không cái nào được dùng:

```
/wp-includes/js/jquery/jquery.min.js                        (30.7 KB)
/wp-includes/js/jquery/jquery-migrate.min.js                ( 5.2 KB)
woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js
woocommerce/assets/js/frontend/add-to-cart.min.js
woocommerce/assets/js/js-cookie/js.cookie.min.js
woocommerce/assets/js/frontend/woocommerce.min.js
```

cộng `wc-blocks.css` và `easy-table-of-contents/assets/css/screen.min.css` + 4 JS của TOC.

**Đã xác minh an toàn**: grep `add_to_cart|woocommerce_.*cart|wc_get_cart` trong `template-parts/home-page/`, `components/product/`, `layouts/` → **0 kết quả**. Trang chủ không hề dùng giỏ hàng. `wc-cart-fragments` cũng không load. Trang chủ cũng không có TOC.

Lighthouse ước tính: **1273 ms**.

### Việc cần làm

Tạo `inc/perf-dequeue.php`, require từ `functions.php` (đặt sau asset import, theo thứ tự import hiện có):

```php
add_action('wp_enqueue_scripts', function () {
    if (!is_front_page()) {
        return;
    }

    // WooCommerce — trang chủ không dùng cart/add-to-cart
    wp_dequeue_script('wc-add-to-cart');
    wp_dequeue_script('woocommerce');
    wp_dequeue_script('jquery-blockui');
    wp_dequeue_script('js-cookie');
    wp_dequeue_script('wc-order-attribution');
    wp_dequeue_script('sourcebuster-js');
    wp_dequeue_style('wc-blocks-style');

    // Easy TOC — trang chủ không có TOC
    wp_dequeue_style('ez-toc');
    wp_dequeue_script('ez-toc-js');

    // jQuery — chỉ bỏ SAU khi các dep trên đã gỡ
    wp_dequeue_script('jquery');
    wp_dequeue_script('jquery-migrate');
}, 99);
```

### Cẩn trọng khi thực thi

- Handle name phải **verify lại bằng `wp_scripts()->queue`** thay vì tin danh sách trên — WooCommerce đổi handle giữa các version (đang chạy WC 10.5.2).
- Dequeue `jquery` **cuối cùng** và chỉ khi không còn ai depend. Nếu Rank Math / CF7 cần jQuery ở trang chủ thì giữ lại — vẫn còn lời từ việc bỏ WooCommerce + TOC.
- Chạy priority 99 để sau mọi enqueue của plugin.
- Sau khi sửa: **flush WP Rocket cache** rồi mới đo lại.

---

## 4. Phase 3 — Giảm HTML: 672 KB → ~250 KB

HTML trang chủ hiện **672 KB raw / 71 KB gzip**, 2011–2288 element, **552 thẻ `<img>`**. Raw bytes quyết định chi phí parse + Style & Layout (766 ms) trên main thread.

Cấu thành đo được:

| Thành phần | Raw | Gzip |
|---|---|---|
| 246 khối `<noscript>` (nhân đôi markup ảnh) | 195 KB (29%) | ~6.5 KB |
| 68 inline `<svg>` (riêng 1 cái 38.7 KB) | 91 KB | ~28 KB |
| `srcset` (594 attribute) | 201 KB | — |

### 4.1. Tắt WP Rocket LazyLoad

WP Rocket LazyLoad và ShortPixel picture-mode đang **chồng lên nhau**, sinh ra `<noscript><picture><source srcset>…` nhân đôi **toàn bộ** markup ảnh — trong khi **theme đã tự set `loading="lazy"` native ở ~129 call site**.

Rocket JS lazyload ở đây là thừa hoàn toàn, mà lại còn thêm: `lazyload.min.js`, 195 KB noscript, và 243 placeholder `data:image/svg+xml`.

→ Tắt `lazyload` và `lazyload_css_bg_img` trong WP Rocket → native lazy của theme tiếp quản.

> Đây cũng chính là thứ mà `docs/plan-chan-lazy-load-anh-hero.md` đã phải viết code để chống đỡ — tắt ở gốc thì sạch hơn nhiều.

**Kiểm tra sau khi tắt**: ảnh dưới fold vẫn phải lazy (native), và **ảnh hero vẫn giữ `fetchpriority=high`**.

### 4.2. Externalize SVG mặt trời (38.7 KB)

`template-parts/home-page/section-about/index.php:130` nhúng inline một SVG 38.7 KB (19 path, 4061 số thập phân dài, 1 filter `feTurbulence`).

#### ⚠️ Ràng buộc quan trọng

Animation `.outer-rotate` được định nghĩa **trong CSS theme** (`section-about/assets/styles.css:199`) và có cơ chế pause `.section-about--paused .outer-rotate` do IntersectionObserver toggle (`section-about/assets/scripts.js:66-70`) để dừng quay khi ngoài viewport.

→ **KHÔNG dùng `<img src="sun.svg">`** — CSS ngoài không với được vào trong ảnh. Animation và cơ chế pause sẽ hỏng, và tệ hơn: SVG sẽ quay mãi không dừng → phản tác dụng về performance.

#### Cách làm: SVGO + fetch/inject

SVGO trước (đo thật: **38.7 → 25.1 KB**, gzip **17.7 → 11.9 KB**), lưu thành `assets/images/about-sun.svg`, rồi **fetch + inject** bằng chính IntersectionObserver đã có sẵn ở `scripts.js:66-70`:

```php
<div class="section-about__sun"
     data-svg="<?= esc_url(get_theme_file_uri('/assets/images/about-sun.svg')) ?>"></div>
```

- Set sẵn `width/height` trong CSS (đã có: `styles.css:231-243`) → **không phát sinh CLS**.
- Khi section sắp vào viewport → `fetch(url).then(r => r.text())` → `el.innerHTML = svg`, chỉ 1 lần.
- SVG thành inline thật trong document → `.outer-rotate` và `.section-about--paused` hoạt động **y nguyên**.
- File `.svg` được cache riêng, HTML nhẹ đi 38.7 KB.

**Cân nhắc thêm**: `feTurbulence` là filter noise render rất đắt trên vùng 1007×1007. Nếu sau khi đo lại vẫn thấy Rendering cao, thay nó bằng texture WebP nhẹ.

### 4.3. (Tuỳ chọn) Icon SVG lặp lại

67 SVG còn lại ≈ 52 KB, nhiều cái trùng nhau (ví dụ 1 icon 4.5 KB xuất hiện 2 lần). Gom thành SVG sprite + `<use>` nếu cần thêm điểm.

→ **Để sau** — làm Phase 1 + 2 trước rồi đo lại.

---

## 5. Phase 4 — Ảnh còn lại (làm nếu chưa đủ điểm)

Lighthouse HTTP/2 mobile: *Serve images in next-gen formats* 300 ms, *Properly size images* 450 ms. Tổng payload **3047 KB**.

- Hero `2026/06/cua-hang-cho-thue-do-hoa-trang-yun-cosplay-tai-ha-noi.jpg` (1672×941, 147 KB) → WebP.
- `rectangle34628622-1-748x1536.webp` — **313 KB cho chỉ 1.1 MP**, nén kém → nén lại, mục tiêu < 80 KB.
- `Nguoi-doi-Batman-4-1-732x1024.jpg` (177 KB), `bo-do-con-gian_min-3-754x1024.jpg` (152 KB), `cosplay-cong-chua-disney-2-731x1024.jpg` (127 KB) → WebP.
- Media library: **49.220 jpg/png vs chỉ 1.294 webp** — ShortPixel coverage rất mỏng. Chạy bulk optimize.
- Áp dụng pattern `$about_img_sizes` / `okhub_about_img()` (đang có trong diff chưa commit ở `section-about/index.php`) cho `components/product/index.php` — 8 product card đang xin size `'full'` không kèm `sizes`, nên fallback về `100vw`.

---

## 6. Ngoài phạm vi (ghi nhận, không làm trong plan này)

| Vấn đề | Ghi chú |
|---|---|
| `assets/images/layer_image.svg` **10.8 MB** | Xuất hiện trên **mọi blog card** (`functions.php:665`, `blog-list/*`, `single-blog/*`). **Không nằm trên trang chủ** nên không ảnh hưởng mục tiêu này, nhưng là quả bom cho trang blog. Nên xử lý riêng. |
| ~1.3 MB ảnh 0-reference | `map-*.svg` (3 × 329 KB), `contact-page/assets/images/d-social-*.png` |
| Stored XSS tiềm ẩn | `section-category/index.php:210` dùng `json_encode()` không có `JSON_HEX_TAG` cho tên category (admin-controlled) |
| `THEME-OVERVIEW.md` lỗi thời nặng | Ở root, mô tả AOS / CDN / ES-module — đều đã bị gỡ. Dễ gây hiểu sai cho lần sau. |
| WP Rocket `minify_google_fonts = 1` | Vẫn bật dù theme không còn dùng Google Fonts → Rocket vẫn chèn `preconnect` tới `fonts.gstatic.com` |

---

## 7. Verification

### Bắt buộc đo trên `https://` (HTTP/2), không phải `http://`

```bash
# Sau MỖI phase — flush cache Rocket trước khi đo

npx lighthouse@12 https://yuncosplay.local/ \
  --only-categories=performance \
  --chrome-flags="--headless=new --ignore-certificate-errors" \
  --output=json --output-path=/tmp/lh-after-mobile.json --quiet

npx lighthouse@12 https://yuncosplay.local/ --preset=desktop \
  --only-categories=performance \
  --chrome-flags="--headless=new --ignore-certificate-errors" \
  --output=json --output-path=/tmp/lh-after-desktop.json --quiet
```

### Bảng theo dõi

| Mốc | Mobile | Desktop | LCP mobile |
|---|---|---|---|
| Baseline (đo 17/07/2026) | 75 | 99 | 5.0 s |
| **Sau Phase 1 (ảnh nền) — ĐO THẬT 17/07/2026** | **82** ✅ | **99** | **4.3 s** |
| Sau Phase 2 (render-blocking) | ~90 | 99 | ~2.5 s |
| Sau Phase 2 (render-blocking) | ~90 | 99 | ~2.5 s |
| Sau Phase 3 (HTML) | ~93 | 99 | ~2.2 s |
| **Mục tiêu** | **> 90** | **> 95** | |

### Kết quả Phase 1 (đã thực thi — commit `16f9b44`)

Mobile **75 → 82** (+7, ít hơn dự đoán ~85). Render Delay **4422 → 2826 ms**, Speed Index **4.0 → 2.6 s**, TBT 120 → 20 ms.

Đã làm: `concept.webp` → `concept-1200w.webp` (37 KB) + `concept-600w.webp` (8 KB, mobile); `backgroundimage-4.webp` → `backgroundimage-4-1200w.webp` (16 KB). Áp cho cả `home-page`, `service-makeup`, `service-pgpb`.

**4 điều học được — ảnh hưởng tới Phase 2/3:**

1. **Mục 2 (dòng 65) ĐÚNG, và còn nặng hơn tưởng**: `concept.webp` không chỉ bị preload `fetchpriority=high` — WP Rocket còn nhận diện nó **chính là phần tử LCP** (`{"type":"bg-img"}` trong bảng `wp_wpr_above_the_fold`). Sau Phase 1, phần tử LCP đã chuyển sang **ảnh hero thật** — lành mạnh hơn nhiều.

2. **⚠️ GOTCHA — bảng `wp_wpr_above_the_fold` KHÔNG bị xoá khi purge cache**: Rocket lưu ảnh LCP trong DB. Đổi ảnh trong CSS mà không xoá bảng này → Rocket vẫn preload file **cũ** ở `fetchpriority=high` (tải 418 KB vô ích). Phải `DELETE FROM wp_wpr_above_the_fold` rồi để beacon tự phân tích lại. **Phase 3 (tắt Rocket LazyLoad) gần như chắc chắn dính lỗi này.**

3. **Mục "resize backgroundimage-4" KHÔNG giúp mobile**: `@media (max-width: 639px)` override sang `backgroundimage-5.webp` → bg4 không hề tải trên mobile (đã xác minh trong browser: `bg4LoadedOnMobile: false`). Chỉ lợi desktop — vốn đã 99đ.

4. **Mục 4 `image-set()` — BỎ, sai công cụ**: `image-set()` chọn theo **DPR**, không theo viewport. Mobile DPR 2–3 → sẽ phục vụ bản **lớn nhất**, ngược mục tiêu. `@media` mới đúng.

→ **Phase 2 là đòn tiếp theo và rất đúng hướng**: Lighthouse hiện báo *"Eliminate render-blocking resources"* **1303 ms** (plan ước tính 1273 ms), chiếm phần lớn Render Delay 2826 ms còn lại.

### Kiểm tra hồi quy sau mỗi phase (không chỉ nhìn điểm)

- [ ] Banner Swiper vẫn chạy, autoplay + parallax bình thường
- [ ] `section-about`: mặt trời vẫn quay, **vẫn dừng khi cuộn ra ngoài viewport** (`.section-about--paused`)
- [ ] Mega-menu header (desktop + mobile) mở/đóng bình thường — Swiper là site-wide, **đừng đụng**
- [ ] Ảnh dưới fold vẫn lazy; **ảnh hero vẫn `loading=eager fetchpriority=high`** (xem `okhub_image_attrs()` — `inc/helpers.php:292`)
- [ ] Trang shop/product **vẫn còn** WooCommerce JS (dequeue chỉ áp cho `is_front_page()`)
- [ ] Trang `service-makeup` và `service-pgpb` vẫn hiển thị đúng nền (dùng chung `concept.webp`)
- [ ] Xem network requests: jQuery/WC/TOC đã biến mất khỏi trang chủ và **vẫn còn** ở trang product

### Caveat cần nhớ

LocalWP trả `Cache-Control: no-cache, public, must-revalidate` cho mọi static asset, nên Lighthouse sẽ luôn báo *"Serve static assets with an efficient cache policy"*. Đây là **diagnostic, không tính vào điểm Performance** — bỏ qua, production sẽ khác.

---

## 8. Thứ tự thực thi

1. **Phase 1** — ảnh nền. Đòn mạnh nhất, rủi ro thấp nhất, chỉ đụng file ảnh + 1 file CSS. → Đo lại.
2. **Phase 2** — dequeue. → Đo lại + kiểm tra kỹ trang shop/product không hỏng.
3. **Phase 3a** — tắt Rocket LazyLoad. → Đo lại + kiểm tra hero/lazy.
4. **Phase 3b** — externalize SVG. → Đo lại.
5. Nếu chưa > 90 → **Phase 4**.

Commit riêng từng phase theo convention (`refactor: ...` / `fix: ...`), **không gộp**.

Khi xong, ghi báo cáo kết quả vào `docs/` (kèm bản `.html`).
