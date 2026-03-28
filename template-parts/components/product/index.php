<?php
$product = wc_get_product(get_the_ID());
$product_id = get_the_ID();
$product_title = get_the_title();
$product_link = get_permalink();

$fallback_thumb_id = 86;

$thumb_id = get_post_thumbnail_id($product_id) ? get_post_thumbnail_id($product_id) : $fallback_thumb_id;
$thumb_html = $thumb_id
	? wp_get_attachment_image(
	$thumb_id,
	'full',
	false,
	[
		'class' => 'product__img',
		'alt' => esc_attr($product_title),
		'loading' => 'lazy',
		'decoding' => 'async',
	]
)
	: '';

$video_url = get_field("video");
// WooCommerce: rent_price lấy từ Regular price.
$regular_price_raw = $product ? $product->get_regular_price() : 0;
$regular_price = is_numeric($regular_price_raw) ? (float) $regular_price_raw : 0;
$rent_price = number_format($regular_price, 0, ".", ".");

$sale_price_raw = $product ? $product->get_price() : 0;
$sale_price = number_format((is_numeric($sale_price_raw) ? (float) $sale_price_raw : 0), 0, ".", ".");
$background_content_id = 9885;
?>


<article class="product">
  <a href="<?= esc_url($product_link); ?>" class="product__link">
    <div class="product__img-wrapper">
      <video src="<?= esc_url($video_url); ?>" playsinline muted loop preload="none" class="product__video"></video>
      <?= $thumb_html; ?>
      <div class="product__title-wrapper">
        <h3 class="product__title"><?= esc_html($product_title); ?></h3>
      </div>
    </div>
    <div class="product__content">
      <div class="product__content-background">
        <?= wp_get_attachment_image( $background_content_id, 'full', false, [ 'class' => 'product__content-background-img' ]); ?>
      </div>
      <div class="product__rent">
        <span class="product__rent-label">Giá thuê</span>
        <p class="product__rent-price">
          <span class="product__rent-price-value"><?= esc_html($rent_price); ?>đ</span>
          <span class="product__rent-price-time">/Ngày</span>
        </p>
      </div>
      <div class="product__price">
        <span class="product__price-label">Giá bán:</span>
        <p class="product__price-value"><?= $sale_price; ?>đ</p>
      </div>
      <div class="product__price-mb">( Giá bán: <?= $sale_price; ?>đ )</div>
    </div>
  </a>
</article>