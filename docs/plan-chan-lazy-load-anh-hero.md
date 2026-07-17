# Chặn lazy load ảnh banner/viewport trên tất cả các trang

> **ĐÃ IMPLEMENT — 17/07/2026.** Kết quả verify: 5 trang × 2 thiết bị = **10/10 pass**.
> Variant hiển thị = `src` thật + `loading="eager"` + `fetchpriority="high"`;
> variant bị ẩn = Rocket-LAZY (không tải). Đúng 1 ảnh `high`/trang.
>
> **Hai chỗ plan này SAI, đã sửa khi implement:**
>
> 1. **"Không thêm filter" — sai.** Plan lập luận attr tường minh đã thắng core nên
>    không cần filter. Đúng về việc core không *ghi đè* ta, nhưng bỏ sót việc core
>    *thêm* `fetchpriority="high"` cho ảnh **khác**. Sau khi logo nhả cờ ra
>    (`fetchpriority="auto"`), suất `high` tự động rơi xuống một ảnh mega-menu
>    (`mask-gradient-2.png`) render trước hero → 2 ảnh cùng `high`. Đã thêm filter
>    `wp_get_loading_optimization_attributes` (`okhub_only_explicit_fetchpriority()`
>    trong `inc/helpers.php`) để chỉ ảnh nào theme chỉ định tường minh mới giữ
>    `fetchpriority`. Gọn hơn nhiều so với sửa ~60 ảnh header.
> 2. **Mục "cố ý loại mega-menu khỏi phạm vi" — lập luận sai.** Plan nói Rocket đã
>    trung hoà chúng nên vô hại. Đúng về *download*, sai về *cướp cờ fetchpriority*.
>    Filter ở trên xử lý luôn, không cần đụng vào 60 call site đó.
>
> Ngoài ra `about-us/section-banner` cũng là swiper nên cần thêm điều kiện `$i === 0`
> (bảng §2 ghi thiếu), và nhánh large-item của gallery product detail cũng dùng chung
> cờ `$hero_img_done` thay vì cứng `lazy`.

## Context

Theme hardcode `'loading' => 'lazy'` vào **~129 call site** `wp_get_attachment_image()` trải trên 44 file — **bao gồm ảnh banner/hero của mọi trang**, tức chính LCP element.

Nhưng thủ phạm thật sự **không phải** attr `loading` native. Project đang chạy **WP Rocket 3.21.3** với **LazyLoad (JS) đang BẬT**. Rocket rewrite HTML qua output buffer: gỡ `loading`, thay `src` bằng SVG placeholder rỗng, đẩy URL thật sang `data-lazy-src`/`data-lazy-srcset`. Ảnh chỉ tải sau khi JS chạy.

Bằng chứng trong `wp-content/cache/wp-rocket/yuncosplay.local/index.html`:

```html
<img width="1672" height="941"
     src="data:image/svg+xml,%3Csvg...%3E"      ← src thật bị thay bằng SVG rỗng
     class="banner-image" decoding="async"
     data-lazy-srcset="http://.../cua-hang-cho-thue-do-hoa-trang....jpg 1672w">
```

Toàn bộ HTML đã cache: `data-lazy-src` × 531, `loading="eager"` × **0**.

Tệ hơn: chỗ `fetchpriority="high"` **duy nhất** trên homepage lại nằm trên **logo header** (`yun_cosplay_logo.webp`) — WP core tự gán vì nó là ảnh đầu DOM — **và chính nó cũng bị Rocket lazyload**. Priority hint đang nằm trên một data-URI rỗng.

### Phát hiện quyết định: `loading="eager"` là chìa khoá

`getExcludedAttributes()` trong `inc/Dependencies/RocketLazyload/Image.php:441` — danh sách loại trừ **mặc định** của Rocket đã chứa sẵn:

```php
return apply_filters( 'rocket_lazyload_excluded_attributes', [
    'data-src=', 'data-no-lazy=', 'data-lazy-src=', ...
    'loading="eager"',        // ← chính nó
    'data-skip-lazy', 'skip-lazy', ...
] );
```

`isExcluded()` (`Image.php:407`) khớp bằng `strpos()` — chuỗi con nguyên văn trên cả thẻ `<img ...>`. `wp_get_attachment_image()` xuất đúng `loading="eager"` dấu nháy kép → khớp chắc chắn.

Nên **`loading="eager"` làm hai việc cùng lúc**: hint native cho browser *và* kích hoạt luật loại trừ của Rocket. Không cần `data-no-lazy`, không cần filter, không cần đụng admin — giải pháp đi theo code, không trôi theo config.

**Đã verify bằng phép thử tự nhiên có sẵn trong repo** — trang faqs vốn đã có `loading="eager"`:

| Hero | PHP hiện tại | HTML Rocket cache ra |
|---|---|---|
| `faq-hero__bg` (faqs) | `loading="eager"` | `src="...banner.webp"` + `srcset` **nguyên vẹn**, `data-lazy` = **0/2** |
| `banner-image` (home) | `loading="lazy"` | `src="data:image/svg+xml,..."`, URL thật ở `data-lazy-srcset` |

### Quyết định đã chốt

- Cặp desktop/mobile → **gate bằng `IS_MOBILE`**. **An toàn**, vì `wp-content/wp-rocket-config/yuncosplay.local.php` có `$rocket_do_caching_mobile_files = 1` (tách file cache mobile) — xác nhận bằng `.mobile-active` + `index-mobile.html` trong cache dir.
- Phạm vi → **hero/banner + LCP thật + logo header**. ~120 call site lazy dưới fold **giữ nguyên**.
- **Không** thêm `<link rel="preload">`.

### Bốn sự thật khác đã verify

0. **`loading` là attr load-bearing — không bao giờ được bỏ trống.** Mọi page template ACF (`front-page.php`, `about-us-page.php`, `service-*-page.php`, `faqs.php`…) chỉ gọi `get_header()` + `get_template_part()` + `get_footer()`, **không bao giờ chạy main loop** → `$wp_query->before_loop` giữ nguyên `true` suốt lượt render. `wp_get_loading_optimization_attributes()` rơi vào nhánh:
   ```php
   } elseif ( $wp_query->before_loop && $wp_query->is_main_query()
       && did_action( 'get_header' ) && ! did_action( 'get_footer' ) ) {
       $maybe_in_viewport = true;
   }
   ```
   → core coi **mọi** ảnh là "trong viewport" và **không bao giờ tự thêm `lazy`**. Đây chính là lý do ~129 chỗ phải hardcode `'lazy'` — chúng **không phải cargo cult**. Bỏ trống `loading` = cả trang eager. Helper vì vậy luôn set `loading` tường minh ở mọi nhánh.

1. **5/6 hero toggle desktop/mobile bằng `display: none`** (không phải opacity). Ảnh `display:none` + lazy → **không bao giờ tải**; `eager` + `display:none` → **vẫn tải**. Nên chỉ variant đang hiện mới được eager → cắt luôn double-download.

   **Ngoại lệ — home banner có bug CSS tiềm ẩn:** `.banner-image-mb` **không có một rule CSS nào trong toàn theme** (grep `--include="*.css"` → 0 file). `@media (max-width: 639px)` (`styles.css:232`) chỉ ẩn `.banner-image`; trên desktop ảnh mobile **vẫn render**, chỉ bị `.swiper-slide { overflow: hidden }` (`:104-106`) cắt đi. Nó không tải hôm nay chỉ nhờ clipping làm IntersectionObserver không fire — quá mong manh để xây tiếp lên. Phải bổ sung rule còn thiếu (xem §2).
2. **Core sẽ cướp priority của hero nếu không chặn.** `wp_maybe_add_fetchpriority_high_attr()` (`wp-includes/media.php:6261`):
   ```php
   if ( isset( $attr['fetchpriority'] ) ) {
       if ( 'high' === $attr['fetchpriority'] ) { ...; wp_high_priority_element_flag( false ); }
       return $loading_attrs;   // ← giá trị khác 'high': core KHÔNG gán gì
   }
   ```
   Logo render **trước** hero. Nếu logo chỉ có `loading="eager"` mà không có `fetchpriority`, core tự gán `high` cho nó và tiêu mất flag → 2 ảnh `high`. Set `fetchpriority="auto"` tường minh → core return sớm, **không** tiêu flag → hero giữ `high` duy nhất.
3. **Tablet không phải rủi ro.** `$rocket_cache_mobile_files_tablet = 'desktop'` → tablet ăn cache desktop; breakpoint hero đều ~640px → tablet (768px+) hiển thị đúng ảnh desktop (eager). Khớp nhau.

---

## 1. Helper trong `inc/helpers.php`

Theme chưa có image helper nào. Thêm mới, prefix `okhub_`, docblock tiếng Việt theo style `okhub_product_order_clauses()`. Không cần file mới → **không đụng thứ tự require ở `functions.php:2-13`** (normative theo `CLAUDE.md:248`); `inc/helpers.php` đã require ở dòng 3.

```php
/**
 * Trả về mảng attr cho wp_get_attachment_image().
 *
 * QUAN TRỌNG — loading="eager" ở đây làm ĐỒNG THỜI 2 việc:
 *   1. Hint native cho browser (đừng hoãn ảnh LCP).
 *   2. Kích hoạt luật loại trừ của WP Rocket LazyLoad: 'loading="eager"' nằm sẵn
 *      trong getExcludedAttributes() (Image.php:441) và được khớp bằng strpos()
 *      trên cả thẻ img. Không có nó, Rocket nuốt src thành SVG rỗng và mọi tối ưu
 *      ở đây thành vô nghĩa. Đừng đổi 'eager' thành giá trị khác.
 *
 * LUÔN set 'loading' ở MỌI nhánh — không bao giờ bỏ trống. Các template ACF của
 * theme không chạy main loop nên $wp_query->before_loop luôn true, khiến core coi
 * mọi ảnh là "trong viewport" và KHÔNG tự thêm lazy (wp-includes/media.php:6131).
 * Bỏ trống 'loading' = cả trang eager.
 *
 * Mỗi hero render cả 2 ảnh desktop + mobile rồi CSS ẩn 1 cái bằng display:none.
 * Ảnh display:none + lazy sẽ KHÔNG bao giờ tải -> truyền 'lazy' cho variant đang
 * bị ẩn để cắt double-download. Ngược lại eager + display:none vẫn tải, nên đừng
 * bao giờ eager cả 2 variant.
 *
 * 'eager' (không kèm high) dành cho ảnh trên fold nhưng KHÔNG phải LCP (logo).
 * fetchpriority='auto' ở đó là cố ý: nó chặn core tự gán 'high' và tiêu mất
 * wp_high_priority_element_flag() trước khi hero kịp render.
 *
 * Cách dùng:
 *      // variant desktop, slide đầu của swiper
 *      wp_get_attachment_image($id, 'full', false, okhub_image_attrs(
 *          ['class' => 'banner-image'],
 *          $i === 0 && !IS_MOBILE ? 'lcp' : 'lazy'
 *      ));
 *
 * @param array  $attrs    Attr riêng của call site (class, alt, ...).
 * @param string $priority 'lcp' = ảnh LCP | 'eager' = trên fold, không LCP | 'lazy'.
 * @return array
 */
function okhub_image_attrs(array $attrs = [], string $priority = 'lazy'): array
{
    $attrs['decoding'] = $attrs['decoding'] ?? 'async';

    if ($priority === 'lcp') {
        $attrs['loading']       = 'eager';
        $attrs['fetchpriority'] = 'high';
        return $attrs;
    }

    if ($priority === 'eager') {
        $attrs['loading']       = 'eager';
        $attrs['fetchpriority'] = 'auto'; // chặn core tự gán 'high' — xem docblock
        return $attrs;
    }

    $attrs['loading'] = 'lazy';
    unset($attrs['fetchpriority']); // lazy + high mâu thuẫn -> core _doing_it_wrong()

    return $attrs;
}
```

## 2. Sửa từng hero

Pattern: thay `array('loading' => 'lazy', 'decoding' => 'async', 'class' => ...)` bằng `okhub_image_attrs(['class' => ...], <đk> ? 'lcp' : 'lazy')`.

| File | Điều kiện `'lcp'` |
|---|---|
| `template-parts/home-page/section-banner/index.php:13-14` | PC: `$i === 0 && !IS_MOBILE` · MB: `$i === 0 && IS_MOBILE` |
| `template-parts/about-us/section-banner/index.php:18-29` | desktop: `!IS_MOBILE` · mobile: `IS_MOBILE` |
| `template-parts/faqs/section-banner/index.php:26-42` | desktop: `!IS_MOBILE` · mobile: `IS_MOBILE` (bỏ double-high hiện tại) |
| `template-parts/service-makeup/hero-section/index.php:10-11` | desktop: `!IS_MOBILE` · mobile: `IS_MOBILE` |
| `template-parts/service-pgpb/hero-section/index.php:10-11` | desktop: `!IS_MOBILE` · mobile: `IS_MOBILE` |
| `template-parts/service-take-photo-page/section-banner/index.php:11-20` | pc: `!IS_MOBILE` · mb: `IS_MOBILE` |

3 file `service-*` được dùng bởi cả page template riêng **lẫn** `single-service.php` → sửa 1 lần, cả 2 route cùng hưởng.

**Home banner (Swiper):** slide dịch chuyển bằng transform chứ không `display:none`, slide 1..N nằm ngoài viewport nên lazy hoãn đúng ý. Chỉ slide 0 cần `'lcp'`:

```php
<?= wp_get_attachment_image($banner['image_pc'], 'full', false, okhub_image_attrs(
      ['class' => 'banner-image'], $i === 0 && !IS_MOBILE ? 'lcp' : 'lazy')) ?>
<?= wp_get_attachment_image($banner['image_mb'], 'full', false, okhub_image_attrs(
      ['class' => 'banner-image-mb'], $i === 0 && IS_MOBILE ? 'lcp' : 'lazy')) ?>
```

**Kèm theo — vá rule CSS còn thiếu** trong `template-parts/home-page/section-banner/assets/styles.css`. `.banner-image-mb` hiện không có rule nào; nó chỉ "ẩn" nhờ bị `overflow:hidden` cắt. Thêm `display:none` tường minh để hành vi tải trở nên xác định và đồng bộ với 5 hero còn lại:

```css
/* cạnh rule #banner .banner-image sẵn có */
#banner .banner-image-mb {
  display: none;
}
```
```css
/* trong @media (max-width: 639px) đã có sẵn ở dòng 232, cạnh rule ẩn .banner-image */
#banner .banner-image-mb {
  display: block;
}
```

Kết quả hiển thị **giống hệt hiện tại** ở cả 2 breakpoint (ảnh mb vốn đã bị cắt khuất trên desktop) — chỉ có hành vi download trở nên xác định.

## 3. `single-product/product-detail/index.php`

Story mobile (`:105-153`) và gallery desktop (`:155+`) **cùng render**, CSS ẩn 1 cái (`styles.css:485-488` ẩn story ở desktop, `:512-513` ẩn gallery ở mobile) → gate được.

- **Story mobile (`:118-122`)** — đã gate `$idx === 0` nhưng thiếu fetchpriority và vẫn eager cả khi ở desktop (lúc đó story bị ẩn):
  `okhub_image_attrs(['class' => 'product-detail__story-img'], $idx === 0 && IS_MOBILE ? 'lcp' : 'lazy')`

- **Gallery desktop (`:189-193`)** — hardcode lazy, **đây là LCP desktop**. Cần cờ chạy vì item đầu có thể là video (không nên ăn mất priority). Khai báo cạnh `$media_index = 0;` (`:160`):
  ```php
  $hero_img_done = false; // ảnh đầu tiên của gallery desktop là LCP
  ```
  rồi trong nhánh `else` non-video (`:184`):
  ```php
  $is_hero       = !$hero_img_done && !IS_MOBILE;
  $hero_img_done = true;
  echo wp_get_attachment_image($m['id'], 'large', false, okhub_image_attrs(
      ['class' => 'product-detail__gallery-img'], $is_hero ? 'lcp' : 'lazy'));
  ```
- Thumbnail strip (`:144-147`) và large-img (`:228-233`) giữ lazy — chỉ đổi sang gọi helper cho nhất quán.

## 4. Logo header

`template-parts/layouts/header/index.php` fork server-side qua `isMobileDevice()` → chỉ 1 header render. Logo là ảnh **đầu tiên** trong DOM và hiện đang bị Rocket lazyload (hiện placeholder tới khi JS chạy).

Dùng `'eager'` — **không** `'lcp'`. Logo nhỏ; `fetchpriority="auto"` vừa tránh tranh băng thông với hero vừa chặn core tự gán `high` (xem Context #2).

- `template-parts/layouts/header/header-desktop/index.php:172` → `okhub_image_attrs(['class' => ''], 'eager')`
- `template-parts/layouts/header/header-mobile/header-main.php:12` → `okhub_image_attrs(['class' => 'header-main__logo-image'], 'eager')`

---

## Verification

Không có test runner. **Nguồn sự thật là HTML Rocket cache ra, không phải PHP** — vì Rocket rewrite sau khi PHP chạy xong.

**Bước 0 — baseline:** trước khi sửa, chạy `performance_start_trace` (`reload: true, autoStop: true`) + `performance_analyze_insight` insight `LCPBreakdown` trên homepage để có số đối chiếu.

**Bước 1 — purge cache** (bắt buộc, nếu không sẽ verify nhầm HTML cũ): WP Rocket admin → *Clear Cache*, hoặc `wp rocket clean --confirm`.

**Bước 2 — regenerate rồi soi HTML đã cache** (đây là bằng chứng chốt):
```bash
cd wp-content/cache/wp-rocket/yuncosplay.local
grep -oE '<img[^>]*banner-image[^>]*>' index.html | head -2
```
Kỳ vọng cho variant **đang hiện** (desktop trong `index.html`, mobile trong `index-mobile.html`):
- `src="http://..."` **thật** — không phải `data:image/svg+xml`
- có `loading="eager"` + `fetchpriority="high"`
- **không** có `data-lazy-src` / `data-lazy-srcset`

Variant **bị ẩn**: ngược lại — phải **có** `data-lazy-*` (chứng minh nó sẽ không tải).

**Bước 3 — đúng 1 `fetchpriority="high"` mỗi trang:**
```bash
grep -o 'fetchpriority="high"' index.html | wc -l   # kỳ vọng: 1, và nằm trên hero (không phải logo)
```

**Bước 4 — kiểm cả 2 biến thể cache:** `index.html` (desktop) và `index-mobile.html` (mobile UA). Lặp cho: `/`, `/about-us`, `/faqs`, 3 trang service, 1 single-service, 1 single-product.

**Bước 5 — Chrome DevTools MCP:** `list_network_requests` lọc `image` → file của variant bị ẩn **không** xuất hiện (chứng minh hết double-download). `performance_analyze_insight` → LCP element = ảnh hero, *resource load delay* giảm rõ so với baseline. `list_console_messages` → không có lỗi mới, đặc biệt không có `_doing_it_wrong` về "lazy-loaded and marked as high priority".

## Rủi ro

- **Quên purge cache** → tưởng không ăn thua. Đây là bẫy dễ dính nhất; luôn purge trước khi đo.
- **Phụ thuộc chuỗi `'loading="eager"'` trong list mặc định của Rocket.** Nó qua filter `rocket_lazyload_excluded_attributes` nên plugin khác *có thể* ghi đè list. Rủi ro thấp (không plugin nào trong site làm vậy) nhưng nếu sau này hero lại bị lazyload thì kiểm tra chỗ này trước. Cách chống chắc hơn nếu cần: thêm `data-no-lazy="1"` vào `$attrs` khi `'lcp'` — cũng nằm trong list mặc định, phòng 2 lớp.
- **Nâng cấp WP Rocket** có thể đổi list loại trừ → verify lại bằng Bước 2 sau mỗi lần update.
- **Resize desktop ↔ mobile**: variant vừa hiện sẽ tải tại thời điểm đó, có thể chớp 1 nhịp. Đây đã là hành vi hiện tại, không tệ hơn.

## Đã cân nhắc và cố ý loại khỏi phạm vi

- **Ảnh mega-menu header** (`header-desktop/index.php:235,272,276,278,296,313`) — không có attr `loading`, nên theo sự thật #0 core coi chúng là eager. Nghe như phải sửa gấp, **nhưng Rocket đã trung hoà sẵn**: mọi ảnh không mang `loading="eager"` đều bị rewrite thành `data-lazy-src`, nên chúng không hề tải sớm và không cạnh tranh với hero. HTML cache xác nhận. Đụng vào là thêm rủi ro mà không đổi được gì.
- **Logo chiếm `fetchpriority=high`** thì có thật và đã nằm trong §4 — HTML cache cho thấy đúng logo (`:172`) giữ `high`, không phải mega-menu (`:272`), vì nó render trước.
- **~120 call site lazy dưới fold** — giữ nguyên, đúng phạm vi đã chốt.

## Commit

Tách theo `CLAUDE.md` (không gộp nhiều thay đổi vào 1 commit):

1. `feat: thêm okhub_image_attrs() cho ảnh ưu tiên trong viewport`
2. `fix: bổ sung rule display cho .banner-image-mb ở home banner`
3. `perf: bỏ lazy load ảnh hero ở tất cả page template`
4. `perf: ưu tiên ảnh LCP trang product detail`
5. `perf: eager load logo header, tránh tranh fetchpriority với hero`
