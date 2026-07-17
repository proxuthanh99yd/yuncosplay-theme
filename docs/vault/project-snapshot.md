# Project Snapshot

## Trạng thái gần nhất

**Thời gian:** 2026-05-18 17:07

**Task:** Fix Organization postal code schema

**Branch:** main

**File thay đổi:**

- `inc/schema.php`
- `docs/vault/project-snapshot.md`
- `docs/vault/changelog.md`
- WordPress ACF option data

## [2026-05-18 17:07] — Fix Organization postal code schema

**Thay đổi:**

- Thêm `postalCode` vào `PostalAddress` trong Organization/LocalBusiness schema.
- Xác nhận ACF option `business_address.postal_code` đang có giá trị `100000`.
- Verify rendered JSON-LD home page có `address.postalCode`.

**Lý do:**

- Validator/schema check báo `address` thiếu trường `postalCode`.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 16:36] — Fix production empty schema type node

**Thay đổi:**

- Thêm `cosplay_remove_invalid_schema_nodes()` để xoá Rank Math graph nodes thiếu/rỗng `@type`.
- Bọc mọi schema override return qua invalid-node cleanup.
- Verify local home JSON-LD không còn node thiếu `@type`.

**Lý do:**

- Production validator báo `https://yuncosplay.okhub-tech.com/#schema-4281` thiếu `@type`, gây lỗi `Unspecified Type`.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 16:14] — Validate rendered JSON-LD with Playwright

**Thay đổi:**

- Tăng priority Rank Math JSON-LD filter lên `9999` để override sau Rank Math.
- Remove thêm Rank Math `Article`, `WebSite`, `Organization` nodes trên page/archive/search contexts để tránh duplicate/mismatch.
- Thêm custom `WebSite`/`Organization` từ ACF options cho mọi page schema chính.
- Sửa FAQ schema key để render đúng `@id`, `url`, `name`; dedupe FAQ rows trùng nhau.
- Browser-test JSON-LD các URL: home, about, contact, FAQs, blogs, shop, search, product search, single product, single blog.

**Lý do:**

- Render thực tế ban đầu còn Rank Math Article/Organization trùng và FAQPage thiếu `@id`; cần browser validation để đảm bảo JSON-LD parse được và không duplicate.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 15:44] — Create Site Business Schema ACF options

**Thay đổi:**

- Tạo ACF Options Page `Site Business Schema` qua MCP WordPress.
- Tạo field group `Site Business Schema` gồm business name, phone, address group, opening hours repeater, price range, area served, socials, map URL, geo.
- Seed option values cho Yun Cosplay: phone, address, opening hours, price range, area served, map URL.
- Refactor `inc/schema.php` để LocalBusiness/Organization đọc từ ACF option fields thay vì Contact page ACF.

**Lý do:**

- Dữ liệu business schema cần nguồn global có cấu trúc, tránh đọc text tự do từ Contact page và giảm rủi ro ACF rỗng/sai format.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 15:44] — Fix remaining schema edge cases

**Thay đổi:**

- Không emit `AggregateOffer` nếu variable product không có variation price hợp lệ.
- Chuẩn hoá WooCommerce shop page ID để `wc_get_page_id('shop') = -1` không tạo URL/breadcrumb sai.

**Lý do:**

- Tránh Product structured data thiếu `lowPrice/highPrice` và tránh shop breadcrumb trỏ sai khi WooCommerce chưa cấu hình shop page.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 15:44] — Fix product and local SEO schema

**Thay đổi:**

- Đổi Product schema sang ưu tiên rental price từ Woo regular price thay vì `_sale_price_custom`.
- Thêm `AggregateOffer` cho variable products và `businessFunction` lease/rental intent.
- Bỏ `itemCondition` hardcoded `NewCondition` khỏi Offer.
- Nâng `Organization` thành `Organization + LocalBusiness + Store`, thêm address locality/region/country, opening hours, price range nếu có dữ liệu.
- Canonicalize Search schema URL chỉ giữ `s` và `post_type`.

**Lý do:**

- Schema cần khớp website cho thuê cosplay, tránh giá bán lấn giá thuê và tăng tín hiệu local SEO.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 15:44] — Implement page-level Rank Math JSON-LD schema

**Thay đổi:**

- Mở rộng `inc/schema.php` để thêm schema cho Home, About, Contact, FAQs, Blog listing, Product archive/category, Search.
- Thêm helper dùng chung cho `Organization`, `WebSite`, `WebPage`, breadcrumb, FAQ entities, search/item lists.
- Giữ single post/product schema hiện có và tránh tạo ACF field group bằng code.

**Lý do:**

- Page templates đã có nhưng JSON-LD chỉ phủ single post/product; cần schema page/archive/search đầy đủ hơn.

**Trạng thái:** ✅ Hoàn thành

## [2026-05-18 15:17] — Implement Rank Math schema override

**Thay đổi:**

- Tạo `inc/schema.php` với Rank Math `rank_math/json_ld` override cho Article, Product, Offer, BreadcrumbList.
- Thêm require `inc/schema.php` trong `functions.php`.
- Đổi product breadcrumb condition trong `rank-math.php` từ `san-pham` sang `product`.
- Cập nhật `SCHEMA-SEO-PLAN.md` theo hướng ghi đè Rank Math.

**Lý do:**

- Dùng Rank Math làm nguồn JSON-LD duy nhất để tránh duplicate schema.

**Trạng thái:** ✅ Hoàn thành
