# Audit: Hardcode data / chưa ghép data — okhub-theme

_Ngày: 2026-07-18. Phạm vi: toàn bộ `template-parts/` + template root + `woocommerce/`._

## Bối cảnh

Theme đã có ACF field group cho gần như mọi page (`Trang chủ`, `Giới thiệu`, `Dịch vụ`, `FAQs`, `Sản phẩm`, `Review`, `Site settings`, `Site Business Schema`…). Vì vậy phần lớn section **đã ghép ACF/WP_Query đúng** — các mục dưới đây là những chỗ còn **hardcode** hoặc **chưa nối vào nguồn data**.

Quy ước bỏ qua (không tính là lỗi): `okhub_img()` cho ảnh trang trí theme-owned; nhãn UI cố định ngắn ("Xem thêm", "Thuê đồ ngay"…); SVG icon inline; class CSS; config swiper/animation.

---

## 🔴 CAO — hỏng hoặc không có data — ✅ ĐÃ FIX (2026-07-18)

| # | Vị trí | Vấn đề | Cách xử lý |
|---|--------|--------|-----------|
| 1 | `template-parts/service-details/` | ~~File rỗng~~ → thực chất là **code mồ côi**: directory không được load/route ở đâu (routing `single-service.php` chỉ đi tới take-photo/pgpb/makeup). Không phải "trang hỏng". | ✅ **Đã xoá** thư mục dead code |
| 2 | `faqs/section-detail-shop/index.php:163` | **Bug biến**: `href` Messenger dùng `$messenger_link` (không tồn tại) → link luôn rỗng. Biến đúng là `$mess_link` (dòng 12) | ✅ Đổi `$messenger_link` → `$mess_link` |
| 3 | `faqs/section-question/index.php:11-15` | Label + tiêu đề hardcode `"Câu hỏi thường gặp"` / `"Giải mã mọi thắc mắc về Yun"` dù ACF đã có field | ✅ Ghép `Question.title` (h2) + `Question.desc` (label) từ ACF, **giữ text cũ làm fallback** |
| 4 | `single-blog/index.php:226` | `<img src="https://placehold.co/78x137">` — **placeholder từ dịch vụ ngoài** cho thumbnail sản phẩm thiếu | ✅ Thay bằng `okhub_img('common/placeholder')` |
| 5 | `contact-page/section-contact-info/index.php:9-40` | **Danh sách social lặp cứng** với `url => '#'` (link chết) khi ACF trống | ✅ Bỏ fallback giả, ẩn khối social khi ACF trống. _Còn lại (chưa làm): fallback cứng giờ/địa chỉ (dòng 8-9) — cân nhắc chuyển sang options Site Business Schema để hết trùng dữ liệu; xem ghi chú dưới_ |

> **Ghi chú kiến trúc (chưa xử lý):** options **Site Business Schema** (`business_phone/address/opening_hours/socials`) hiện chỉ dùng cho JSON-LD (`inc/schema.php`). Header/footer/CTA/contact mỗi nơi tự khai field liên hệ/social riêng → dễ lệch dữ liệu. Việc gộp về 1 nguồn chuẩn là cải tiến riêng, cần bạn quyết định vì đổi nguồn data.

---

## 🟡 TRUNG BÌNH

| # | Vị trí | Vấn đề | Nguồn đúng |
|---|--------|--------|-----------|
| 6 | `single-blog/index.php:90` + `blog-list/hero-section/index.php:10` | Hardcode attachment ID `IS_MOBILE ? 10058 : 10056` cho line trang trí | `okhub_img(...)` (ảnh theme-owned) |
| 7 | `service-take-photo-page/section-change/index.php:2-3` | Hardcode ID ảnh nền `10513/10515`; thêm biến `$target` (dòng 31, 100) chưa được gán | Thêm field ảnh nền vào ACF group `change`; `$target` lấy từ `$link['target']` |
| 8 | `service-take-photo-page/section-contact-form/index.php:11` | Hardcode CF7 form ID `9898` | Đưa vào ACF option (site-settings), như `contact-page` đã làm với `contact_cf7_form_id` |
| 9 | `header-mobile/header-menu.php:229` ("Chỉ đường") và `header-mobile/header-product.php:120` ("Xem tất cả sản phẩm") | `href="/"` chưa nối link — bản desktop đã đúng | `business_map_url` / `get_permalink(wc_get_page_id('shop'))` |
| 10 | `service-makeup/feedback/index.php` + `service-pgpb/feedback/index.php` (dòng 22,29,36,43) | 4 icon social (YT/IG/FB/TikTok) đều `href="#"` chưa nối | `get_field('cta','option')` hoặc `business_socials` |
| 11 | `home-page/section-banner/index.php:52` | `<span>(12)</span>` — số đếm hardcode cạnh link banner | Đếm động (số sản phẩm/mục của banner) hoặc field ACF |

---

## 🟢 THẤP — dọn dẹp / nhất quán

- **Code chết** (không được load ở đâu — orchestrator dùng bản `components/`):
  - `home-page/section-highlights/index.php` + `home-page/section-events/index.php` là bản trùng không dùng; còn hardcode ID `147/146/145/144`.
  - `components/section-highlights/index.php:2-5` — cũng còn dead ID `147/146/145/144` (loop thực tế dùng ACF).
  - `header-mobile/header-menu.php:10` — `$icon_list_disc_id = 71` gán nhưng không dùng.
  - `search-page/index.php:20` — `$blog_overlay_url = ''` vì ảnh overlay (id 9833) đã bị xoá khỏi media library.
  - `service-take-photo-page/section-contact-service/index.php:2-3` — dead ID `10244/10246`.
- **Text CTA hardcode** rải rác trang dịch vụ (nên đưa vào ACF nếu muốn CMS sửa được): `service-makeup/hero-section:28`, `workflow-section:22,45`, `service-makeups:66`; `service-pgpb/hero-section:27`, `workflow-section:22,45`, `service-makeups:89`; `service-take-photo-page/section-banner:28`.
- **Markup placeholder chết** còn text cứng: `service-take-photo-page/section-banner:49-56`, `section-intro-service:132-139`.
- **Link `"/"` thay vì `home_url('/')`** (chức năng vẫn về home, chỉ thiếu nhất quán): logo ở `footer/index.php:37`, `header-desktop/index.php:166`, `mega-menu-search-result.php:34,121`, `header-mobile/header-menu.php:34`; breadcrumb `single-blog/index.php:67`, `blog-list/hero-section:4`.
- **Chuỗi fallback dài hardcode**: `service-take-photo-page/section-intro-service:4-5`, `section-services:6`.
- **`faqs/section-quick-search/index.php:34`** — item `href="#"` (ACF chưa có field link).
- **Heading hardcode** (nhẹ): `home-page/section-category:171` "Danh sách danh mục"; `product-faq:103` "Câu hỏi thường gặp" (title chính đã dùng ACF).
- **Price slider min/max hardcode**: `product-listing/section-sidebar/index.php:122-126` (`100000`–`10000000`).
- **Ảnh theme manual (không theo okhub_img)**: `blog-list/featured-section` dùng `assets/images/default.jpg` (fallback) và `assets/images/layer_image.svg` (overlay) qua `get_template_directory_uri()`.
- **`single-service.php`**: còn `print_r("Không có dịch vụ")` (debug leftover) + hardcode slug taxonomy để routing (`dich-vu-chup-anh`/`dich-vu-pg-pb`/`dich-vu-makeup`).

---

## ✅ Đã ghép data đầy đủ (OK)

- **Home**: banner, about, products (WP_Query product), category (taxonomy `product_cat`), services (WP_Query `service`), gallery, blog (WP_Query post) — đều từ ACF/query.
- **Single-product**: product-detail (WooCommerce + ACF + CTA option), product-faq (relationship + custom + fallback query), product-feedback (ACF `product_review`), related-product (`wc_get_related_products`).
- **Product-listing**: breadcrumb, content (WP_Query + infinite scroll), sidebar (taxonomy động).
- **Blog**: blog-item, blog-item-v2, blog-list (list + featured), single-blog related-posts, search-page (WP_Query).
- **About-us** (toàn bộ), **FAQs** banner/mermaid, **service-makeup / service-pgpb / service-take-photo** phần data chính.
- **Layout**: header desktop/mobile (ACF `header` option + WP_Query), footer (ACF `footer` option), cta (ACF `cta` option), section-events (ACF option), marquee, animated-button, contact breadcrumb + form.

> Ghi chú: theme không dùng `wp_nav_menu()` — menu chạy bằng ACF repeater (`header['menu']`, `footer['footer_navigation']`), là data-driven nên không tính là hardcode.
