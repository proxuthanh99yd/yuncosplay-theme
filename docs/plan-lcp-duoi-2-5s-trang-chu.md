# Plan: Đưa LCP trang chủ xuống dưới 2.5 s

> **Trạng thái**: Chờ duyệt · **Ngày**: 17/07/2026
> **Mục tiêu**: LCP mobile < 2.5 s (hiện tại **4.4 s**)
> **Thay thế**: `docs/plan-toi-uu-lighthouse-trang-chu.md` — Phase 1 của plan đó đã xong, nút thắt đã dịch chuyển sang chỗ khác. Xem §2.

---

## 1. Baseline đo thật

Đo bằng `npx lighthouse@12` trên `https://yuncosplay.local/`, form-factor mobile, throttling `simulate`.

> **Bắt buộc đo trên `https://`.** LocalWP phục vụ `http://` bằng HTTP/1.1 (giới hạn 6 connection) nhưng `https://` bằng HTTP/2. Đo trên `http://` sẽ tạo ra một nút thắt giả không tồn tại trên production.

| Metric | Giá trị | Score | Trọng số |
|---|---|---|---|
| FCP | 2.7 s | 0.60 | 10% |
| Speed Index | 2.9 s | 0.95 | 10% |
| **LCP** | **4.4 s** | **0.40** | **25%** |
| TBT | 40 ms | 1.00 | 30% |
| CLS | 0 | 1.00 | 25% |

**Performance mobile: 81.**

TBT (40 ms) và CLS (0) đã hoàn hảo. **Không đụng vào chúng.** Toàn bộ điểm mất nằm ở LCP và FCP.

---

## 2. Nút thắt đã dịch chuyển — plan cũ không còn đúng

Phase 1 của plan cũ (resize ảnh nền CSS, commit `16f9b44`) **đã có tác dụng lớn và đúng hướng**:

| Pha của LCP | Plan cũ (trước) | Đo lại (nay) | Ghi chú |
|---|---|---|---|
| TTFB | — | 814 ms | 19% |
| **Load Delay** | ~0 ms | **2626 ms** | **60% — nút thắt mới** |
| Load Time | 0 ms | 86 ms | 2% |
| **Render Delay** | **4422 ms** | **838 ms** | ✅ giảm 3584 ms nhờ Phase 1 |

→ Render Delay — thứ mà Phase 3/4 của plan cũ nhắm tới — **đã không còn là vấn đề chính**. Giờ 60% LCP là **Load Delay**: ảnh hero mất 2.6 s mới tải xong.

### Điều quan trọng: đây KHÔNG phải lỗi priority hint

| Bằng chứng | Kết luận |
|---|---|
| Audit `prioritize-lcp-image` = **score 1** | Lighthouse xác nhận priority đã đúng |
| Ảnh hero bắt đầu request tại **831 ms** (ngay sau document) | Preload scanner tìm thấy ngay, không hề trễ |
| Thẻ hero có sẵn `loading="eager" fetchpriority="high"` | `okhub_image_attrs(..., 'lcp')` hoạt động đúng, `IS_MOBILE` detect đúng |

→ **Đừng đi sửa `fetchpriority` / preload / `okhub_image_attrs()`.** Không còn gì để lấy ở đó.

"Load Delay 2626 ms" là con số **mô phỏng** của Lantern, không phải đo thật (LocalWP là localhost, không có độ trễ mạng thật). Lantern giả lập đường 4G ~1.6 Mbps (~200 KB/s) rồi tính xem 149 KB ảnh hero mất bao lâu **khi phải chia băng thông với mọi thứ tải cùng lúc**.

### Waterfall — ai đang giành băng thông với hero

| Bắt đầu | Dung lượng | Priority | Resource |
|---|---|---|---|
| 2 ms | 68 KB | VeryHigh | Document (HTML) |
| 828–830 ms | ~38 KB | VeryHigh | 6 stylesheet (ez-toc, swiper, lenis, 2× background-css, wc-blocks) |
| 829–830 ms | **35 KB** | High | **jQuery + jquery-migrate** |
| 830 ms | **70 KB** | Medium | **logo** |
| **831 ms** | **149 KB** | **High** | **← ảnh hero (LCP)** |

Tất cả khởi hành trong cùng ~3 ms và chia nhau đường truyền. **Tổng ~360 KB phải qua ống 200 KB/s trước khi hero vẽ được.**

→ **Cách duy nhất để hạ Load Delay là giảm số byte** — của chính ảnh hero, và của những thứ tranh chấp cùng nó.

---

## 3. Phase A — Ảnh hero là JPEG, không phải WebP 🔴

**Đòn mạnh nhất. Làm đầu tiên.**

```
/wp-content/uploads/2026/06/cua-hang-cho-thue-do-hoa-trang-yun-cosplay-tai-ha-noi-1-731x1024.jpg
→ 149 KB — resource lớn thứ 2 của trang, và LÀ chính phần tử LCP
```

Kiểm tra trên đĩa: **không tồn tại bất kỳ biến thể `.webp`/`.avif` nào** cho ảnh này. Toàn bộ srcset là `.jpg`:

| Biến thể | Dung lượng |
|---|---|
| `-600x840.jpg` | 106 KB |
| **`-731x1024.jpg`** ← browser chọn | **149 KB** |
| `-768x1075.jpg` | 162 KB |
| `.jpg` (1060w) | 148 KB |

Plugin **ShortPixel có cài** nhưng chưa hề xử lý ảnh này (upload 2026/06). Audit `modern-image-formats` xác nhận: **tiết kiệm được 246 KB** toàn trang.

Vì sao browser chọn đúng bản 731w: `sizes="(max-width: 1060px) 100vw, 1060px"` → viewport 412 px × DPR 1.75 = **721 px** → khớp `731w`. **Logic `sizes` đang đúng — vấn đề thuần tuý là định dạng.**

### Việc cần làm

1. Chạy ShortPixel bulk-optimize cho ảnh hero (và ảnh banner còn lại), bật **WebP + AVIF** và **delivery mode `<picture>` hoặc rewrite**.
2. Nếu ShortPixel hết quota → convert thủ công bằng `cwebp -q 82` và thay ảnh trong ACF banner.
3. Xác minh sau khi làm: request LCP phải trả về `.webp`, **< 50 KB**.

**Ước tính: 149 KB → ~45 KB (−104 KB).**

> ⚠️ Ảnh hero là **JPEG ảnh chụp người** — dùng `-q 80..85`, đừng ép quá thấp làm vỡ mặt. Kiểm tra mắt thường trên điện thoại thật trước khi chốt.

---

## 4. Phase B — Logo 70 KB cho khung 40 px 🔴

Logo mobile tải **bản full 614×650 = 70 KB**, ở `priority=Medium`, khởi hành **1 ms trước ảnh hero** → giành băng thông trực tiếp với LCP.

Nhưng CSS chỉ hiển thị nó cao **40 px**:

```css
/* template-parts/layouts/header/assets/header-mobile/header-main.css:23 */
.header-main__logo   { height: 2.5rem; }   /* 40px — base font-size 16px */
.header-main__logo img { width: auto; height: 100%; }
```

40 px × DPR 1.75 ≈ **70 px** là đủ. Đang tải **614 px — thừa gần 9×**.

Nguyên nhân: `header-mobile/header-main.php:12` gọi size `'full'` và **không truyền `sizes`**:

```php
wp_get_attachment_image($header_logo, 'full', false, okhub_image_attrs(array('class' => 'header-main__logo-image'), 'eager'));
```

Đã có sẵn biến thể nhỏ trên đĩa: `yun_cosplay_logo-100x100.webp` (**5.3 KB**), `-150x150.webp` (9.2 KB).

### Việc cần làm

Đổi size `'full'` → biến thể nhỏ **và** thêm `sizes` để srcset chọn đúng:

```php
wp_get_attachment_image(
    $header_logo,
    'medium',                      // hoặc size custom ~150px
    false,
    okhub_image_attrs(array(
        'class' => 'header-main__logo-image',
        'sizes' => '40px',
    ), 'eager')
);
```

**Ước tính: 70 KB → ~6 KB (−64 KB).**

> Kiểm tra cả `header-desktop/index.php:172` — cũng đang dùng `'full'`. Desktop logo hiển thị lớn hơn nên chọn size phù hợp riêng, đừng bê nguyên `40px`.
> Giữ `'eager'` cho logo (nó nằm trên fold), **đừng đổi thành lazy** — sẽ hỏng FCP.

---

## 5. Phase C — Gỡ render-blocking không dùng

Giữ nguyên nội dung Phase 2 của plan cũ — vẫn đúng, và giờ **được xác nhận bởi số mới**: audit `render-blocking-resources` báo **1353 ms**.

| Resource | Chặn |
|---|---|
| `jquery.min.js` (30 KB) | 150 ms |
| `background-css` (11 KB) | 150 ms |
| `swiper-bundle.min.css` (3 KB) | 150 ms |

jQuery + jquery-migrate = **35 KB ở priority High**, tranh chấp trực tiếp với hero, **và trang chủ không dùng đến**.

Phase này đánh vào **cả hai** phía: bỏ 35–40 KB khỏi cuộc tranh băng thông (→ Load Delay) **và** gỡ render-blocking (→ Render Delay 838 ms + FCP 2.7 s).

### Việc cần làm

Tạo `inc/perf-dequeue.php`, require từ `functions.php` (sau asset import):

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
    wp_dequeue_style('wc-blocks-style');

    // Easy TOC — trang chủ không có TOC
    wp_dequeue_style('ez-toc');
    wp_dequeue_script('ez-toc-js');

    // jQuery — chỉ bỏ SAU khi các dep trên đã gỡ
    wp_dequeue_script('jquery');
    wp_dequeue_script('jquery-migrate');
}, 99);
```

**Đã xác minh an toàn** (grep `add_to_cart|woocommerce_.*cart|wc_get_cart` trong `template-parts/home-page/`, `components/product/`, `layouts/` → 0 kết quả).

### Cẩn trọng

- **Verify handle bằng `wp_scripts()->queue`** thay vì tin danh sách trên — WooCommerce đổi handle giữa các version (đang chạy WC 10.5.2).
- Dequeue `jquery` **cuối cùng**, và chỉ khi không còn ai depend. Nếu Rank Math / CF7 cần jQuery ở trang chủ thì **giữ lại** — vẫn còn lời từ WooCommerce + TOC.
- Priority 99 để chạy sau mọi enqueue của plugin.
- **Flush WP Rocket cache** rồi mới đo lại.

**Ước tính: −40 KB băng thông, −1353 ms render-blocking.**

---

## 6. Ngân sách LCP

| Hạng mục | Hiện tại | Sau A+B+C |
|---|---|---|
| Document | 68 KB | 68 KB |
| CSS | 38 KB | ~33 KB |
| jQuery | 35 KB | **0** |
| Logo | 70 KB | **~6 KB** |
| Hero | 149 KB | **~45 KB** |
| **Tổng byte trước khi hero vẽ** | **~360 KB** | **~152 KB** |
| Thời gian truyền @200 KB/s | ~1800 ms | **~760 ms** |

| Pha LCP | Hiện tại | Dự kiến |
|---|---|---|
| TTFB | 814 ms | 814 ms |
| Load Delay | 2626 ms | **~760 ms** |
| Load Time | 86 ms | ~25 ms |
| Render Delay | 838 ms | ~500 ms |
| **LCP** | **4364 ms** | **~2100 ms** ✅ |

→ **A + B + C là đủ để đạt mục tiêu < 2.5 s.** Dự kiến score mobile ~90.

### ⚠️ Rủi ro: TTFB 814 ms ăn 1/3 ngân sách

Audit `server-response-time`: **770 ms, tiết kiệm được 668 ms**. Với LCP mục tiêu 2500 ms, TTFB 814 ms là rất sát.

Nhưng đây **rất có thể là artifact của LocalWP** (PHP dev, không opcache, không object cache) chứ không phản ánh production. **Không tối ưu TTFB dựa trên số đo local** — đo lại trên production trước khi động vào. Nếu production TTFB cũng > 600 ms thì mới mở Phase D.

---

## 7. Phase D — Chỉ làm nếu A+B+C chưa đủ

Xếp theo tỉ lệ lợi ích / rủi ro:

1. **`uses-responsive-images` — tiết kiệm 993 KB.** Con số lớn nhất của cả trang, nhưng phần lớn là ảnh **dưới fold** → không ảnh hưởng LCP, chỉ giảm tải chung. Ảnh lớn nhất trang là `rectangle34628622-1-748x1536.webp` (313 KB) — không phải LCP.
2. **Tắt WP Rocket LazyLoad** (Phase 3.1 plan cũ). Đã xác nhận vẫn đang bật (`data-lazy-srcset` + placeholder `data:image/svg+xml` trong HTML). Theme đã tự set `loading="lazy"` native ở ~129 call site → Rocket lazyload là thừa, lại còn sinh noscript nhân đôi markup. **Nhưng**: HTML hiện 570 KB raw / 68 KB gzip — parse cost đã không còn là nút thắt (TBT chỉ 40 ms), nên lợi ích chủ yếu là gọn HTML chứ không phải LCP.
3. **Externalize SVG mặt trời 38.7 KB** (Phase 4.2 plan cũ) — xem plan cũ để biết ràng buộc `.outer-rotate` / `.section-about--paused`. **Ưu tiên thấp**: TBT đã 40 ms, đây không còn là vấn đề.

> Ghi chú: `unused-css-rules` (12 KB) và `unminified-css` (8 KB) — số quá nhỏ, **bỏ qua**.

---

## 8. Kiểm tra hồi quy sau mỗi phase

- [ ] Ảnh hero **vẫn nét** trên điện thoại thật (Phase A đụng chất lượng ảnh)
- [ ] Logo header **vẫn nét** trên màn retina — cả mobile lẫn desktop (Phase B)
- [ ] Logo vẫn `eager`, không CLS khi load
- [ ] Banner Swiper vẫn chạy, autoplay + parallax bình thường
- [ ] Mega-menu header (desktop + mobile) mở/đóng bình thường — Swiper là site-wide, **đừng đụng**
- [ ] `section-about`: mặt trời vẫn quay, **vẫn dừng khi cuộn ra ngoài viewport**
- [ ] Trang shop/product **vẫn còn** WooCommerce JS (dequeue chỉ áp cho `is_front_page()`)
- [ ] Xem network: jQuery/WC/TOC đã biến mất khỏi trang chủ và **vẫn còn** ở trang product
- [ ] `prioritize-lcp-image` vẫn score 1

### Caveat cần nhớ

LocalWP trả `Cache-Control: no-cache, public, must-revalidate` cho mọi static asset → Lighthouse sẽ **luôn** báo *"Serve static assets with an efficient cache policy"*. Đây là **diagnostic, không tính vào điểm Performance** — bỏ qua, production sẽ khác.

---

## 9. Thứ tự thực thi

1. **Phase A** — hero JPG → WebP. Đòn mạnh nhất (−104 KB), đánh thẳng vào resource LCP. → Đo lại.
2. **Phase B** — logo. Rẻ, 1 dòng PHP, −64 KB. → Đo lại.
3. **Phase C** — dequeue. Rủi ro cao nhất → làm cuối, kiểm tra kỹ trang shop/product. → Đo lại.
4. Nếu LCP vẫn > 2.5 s → mở **Phase D**, và đo TTFB trên production trước.

Sau mỗi phase: **flush WP Rocket cache** → đo lại bằng lệnh dưới → ghi lại LCP + 4 pha.

```bash
npx -y lighthouse@12 https://yuncosplay.local/ \
  --form-factor=mobile --screenEmulation.mobile \
  --throttling-method=simulate --only-categories=performance \
  --output=json --output-path=./lh-mobile.json \
  --chrome-flags="--headless=new --ignore-certificate-errors"
```

Commit riêng từng phase theo convention (`refactor: ...` / `fix: ...`), **không gộp**.

Khi xong, ghi báo cáo kết quả vào `docs/` (kèm bản `.html`).
