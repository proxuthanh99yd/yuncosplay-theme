# Plan Schema SEO — Ghi đè Rank Math

## Mục tiêu

Ghi đè schema JSON-LD do Rank Math output cho:

- Trang blog single post: `Article`, `BreadcrumbList`
- Trang sản phẩm WooCommerce single product: `Product`, `Offer`, `BreadcrumbList`

Không tự render `<script type="application/ld+json">` riêng qua `wp_head`, để tránh duplicate schema.

---

## Nguyên tắc

- Dùng Rank Math làm nguồn output JSON-LD duy nhất.
- Ghi đè bằng filter của Rank Math.
- Không thêm schema thủ công ngoài Rank Math.
- Không đổi UI.
- Không đổi dữ liệu WordPress.

---

## Phạm vi file

### File tạo mới

- `inc/schema.php` — chứa helper + Rank Math schema filters.

### File sửa

- `functions.php` — require schema module:

```php
require get_theme_file_path('/inc/schema.php');
```

### File có thể sửa nếu cần

- `rank-math.php` — hiện đang xử lý breadcrumb HTML/items. Có thể thêm hoặc chỉnh breadcrumb condition cho WooCommerce product nếu cần.

---

## Rank Math filters dự kiến

### Schema graph filter

Dùng filter:

```php
add_filter('rank_math/json_ld', 'cosplay_override_rank_math_schema', 99, 2);
```

Hàm chính:

```php
function cosplay_override_rank_math_schema($data, $json_ld)
{
    if (is_singular('post')) {
        return cosplay_override_rank_math_blog_schema($data);
    }

    if (is_singular('product')) {
        return cosplay_override_rank_math_product_schema($data);
    }

    return $data;
}
```

### Breadcrumb items filter

Hiện có sẵn trong `rank-math.php`:

```php
add_filter('rank_math/frontend/breadcrumb/items', function ($crumbs, $class) {
    if (is_singular('post')) {
        $crumbs = get_post_crumbs($crumbs);
    }

    return $crumbs;
}, 10, 2);
```

Cần sửa condition product:

```php
if (is_singular('product')) {
    $crumbs = get_product_crumbs($crumbs);
}
```

Hiện code đang dùng `is_singular('san-pham')`, có khả năng sai với WooCommerce product.

---

## Cách ghi đè schema graph

Trong `rank_math/json_ld`, `$data` là graph schema Rank Math chuẩn bị output.

Cách làm:

1. Build schema mong muốn theo page type.
2. Remove node Rank Math cũ có type trùng:
   - Blog: `Article`, `BlogPosting`, `BreadcrumbList`
   - Product: `Product`, `Offer`, `AggregateOffer`, `BreadcrumbList`
3. Add node mới vào `$data`.
4. Return `$data`.

Mục tiêu: source chỉ còn 1 schema chính mỗi loại.

---

## Blog single post

Điều kiện:

```php
is_singular('post')
```

Output Rank Math sau override:

- `Article`
- `BreadcrumbList`

### Article data

Nguồn dữ liệu:

- Title: `get_the_title()`
- URL: `get_permalink()`
- Description: `get_the_excerpt()` hoặc fallback từ content
- Published date: `get_the_date('c')`
- Modified date: `get_the_modified_date('c')`
- Author: `get_the_author_meta('display_name')`
- Publisher: `get_bloginfo('name')`
- Image: featured image nếu có
- Logo: custom logo nếu có

Cấu trúc:

```php
[
    '@type' => 'Article',
    '@id' => get_permalink($post_id) . '#article',
    'headline' => get_the_title($post_id),
    'description' => $description,
    'url' => get_permalink($post_id),
    'datePublished' => get_the_date('c', $post_id),
    'dateModified' => get_the_modified_date('c', $post_id),
    'author' => [
        '@type' => 'Person',
        'name' => get_the_author_meta('display_name', $author_id),
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $logo_url,
        ],
    ],
    'image' => $image_url,
]
```

Bỏ field rỗng, không output `null`, `''`, empty array.

### Blog BreadcrumbList

Logic:

```text
Trang chủ → Danh sách bài viết → current post
```

Nguồn hiện tại:

- UI breadcrumb hardcode: `template-parts/single-blog/index.php`
- Rank Math helper: `rank-math.php`, `get_post_crumbs()`
- Blog listing page ID hiện dùng: `72`

Cấu trúc:

```php
[
    '@type' => 'BreadcrumbList',
    '@id' => get_permalink($post_id) . '#breadcrumb',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Trang chủ',
            'item' => home_url('/'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => get_the_title(72),
            'item' => get_permalink(72),
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => get_the_title($post_id),
            'item' => get_permalink($post_id),
        ],
    ],
]
```

---

## Product single

Điều kiện:

```php
is_singular('product')
```

Output Rank Math sau override:

- `Product`
- nested `Offer`
- `BreadcrumbList`

### Product data

Nguồn dữ liệu:

- Product object: `wc_get_product(get_the_ID())`
- Name: `$product->get_name()`
- Description: short description, fallback full description
- SKU: `$product->get_sku()` nếu có
- URL: `get_permalink($product_id)`
- Image: product featured image nếu có
- Brand: `get_bloginfo('name')`
- Currency: `get_woocommerce_currency()`
- Price: custom price hoặc WooCommerce price
- Availability: WooCommerce stock status

Price priority:

1. `_sale_price_custom`
2. `$product->get_sale_price()`
3. `$product->get_regular_price()`
4. `$product->get_price()`

Availability map:

```php
[
    'instock' => 'https://schema.org/InStock',
    'outofstock' => 'https://schema.org/OutOfStock',
    'onbackorder' => 'https://schema.org/PreOrder',
]
```

Cấu trúc:

```php
[
    '@type' => 'Product',
    '@id' => get_permalink($product_id) . '#product',
    'name' => $product->get_name(),
    'description' => $description,
    'sku' => $product->get_sku(),
    'url' => get_permalink($product_id),
    'image' => $image_url,
    'brand' => [
        '@type' => 'Brand',
        'name' => get_bloginfo('name'),
    ],
    'offers' => [
        '@type' => 'Offer',
        '@id' => get_permalink($product_id) . '#offer',
        'url' => get_permalink($product_id),
        'priceCurrency' => get_woocommerce_currency(),
        'price' => $price,
        'availability' => $availability_url,
        'itemCondition' => 'https://schema.org/NewCondition',
    ],
]
```

Bỏ optional field rỗng như SKU, image, price.

### Product BreadcrumbList

Logic:

```text
Trang chủ → Danh sách sản phẩm → current product
```

Nguồn hiện tại:

- Product listing page ID trong Rank Math helper đang là `448`
- Existing helper đang dùng `is_singular('san-pham')`, cần đổi sang `is_singular('product')`.

Cấu trúc:

```php
[
    '@type' => 'BreadcrumbList',
    '@id' => get_permalink($product_id) . '#breadcrumb',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Trang chủ',
            'item' => home_url('/'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => get_the_title(448),
            'item' => get_permalink(448),
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $product->get_name(),
            'item' => get_permalink($product_id),
        ],
    ],
]
```

---

## Helper functions dự kiến

Trong `inc/schema.php`:

```php
cosplay_override_rank_math_schema($data, $json_ld)
cosplay_override_rank_math_blog_schema($data)
cosplay_override_rank_math_product_schema($data)
cosplay_remove_schema_types($data, $types)
cosplay_schema_node_has_type($node, $types)
cosplay_get_logo_url()
cosplay_get_featured_image_url($post_id)
cosplay_get_article_schema($post_id)
cosplay_get_product_schema($product)
cosplay_get_blog_breadcrumb_schema($post_id)
cosplay_get_product_breadcrumb_schema($product)
cosplay_get_product_schema_price($product)
cosplay_get_product_schema_availability($product)
cosplay_clean_schema_array($data)
```

---

## Duplicate prevention

Không dùng:

```php
add_action('wp_head', ...)
```

Không tự echo JSON-LD.

Chỉ modify `$data` trong Rank Math graph. Sau override, source cần có:

### Blog

- 1 `Article` hoặc compatible article node
- 1 `BreadcrumbList`

### Product

- 1 `Product`
- 1 `Offer` nested trong Product
- 1 `BreadcrumbList`

---

## Validation checklist

### Single blog

- View source chỉ có 1 `application/ld+json` graph từ Rank Math.
- Graph có `Article` đúng dữ liệu.
- Graph có `BreadcrumbList` đúng: home → blog list → post.
- Không còn `BlogPosting` duplicate nếu đã dùng `Article`.
- `datePublished`, `dateModified` là ISO 8601.
- `image` absolute URL nếu có featured image.

### Single product

- View source chỉ có 1 `application/ld+json` graph từ Rank Math.
- Graph có 1 `Product`.
- Product có nested `Offer`.
- Không còn `AggregateOffer` duplicate nếu không dùng.
- `price` numeric string, không có ký hiệu tiền.
- `priceCurrency` từ WooCommerce.
- `availability` đúng schema URL.
- Breadcrumb đúng: home → listing → product.

### Tool validate

- Google Rich Results Test.
- Schema.org validator.
- Browser view-source check.

---

## Thứ tự implement sau khi duyệt

1. Kiểm tra Rank Math graph hiện tại trên single blog/product trong view-source.
2. Tạo `inc/schema.php` với Rank Math filters + helpers.
3. Require `inc/schema.php` trong `functions.php`.
4. Sửa product breadcrumb condition trong `rank-math.php` từ `san-pham` sang `product` nếu xác nhận WooCommerce dùng `product`.
5. Test single blog source.
6. Test single product source.
7. Fix duplicate nếu Rank Math giữ node cũ ngoài `$data` key dự kiến.
8. Cập nhật `docs/vault/project-snapshot.md` và `docs/vault/changelog.md` sau khi code xong.
