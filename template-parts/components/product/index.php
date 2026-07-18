<?php
$product = wc_get_product(get_the_ID());
$product_id = get_the_ID();
$product_title = get_the_title();
$product_link = get_permalink();

$thumb_id = get_post_thumbnail_id($product_id);
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

// Prices
// Rent price: ONLY from regular_price (no fallback)
$rent_price_raw = $product->get_regular_price();
$rent_price = number_format((float) ($rent_price_raw ?: 0), 0, '.', '.');

// Sale price: ONLY from _sale_price_custom (no fallback)
$sale_price_raw = get_post_meta($product_id, '_sale_price_custom', true);
$sale_price = number_format((float) ($sale_price_raw ?: 0), 0, '.', '.');

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
        <?= okhub_img('common/rental-price-container', array('class' => 'product__content-background-img')); ?>
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
        <p class="product__price-value"><?= esc_html($sale_price); ?>đ</p>
      </div>
      <div class="product__price-mb">( Giá bán: <?= esc_html($sale_price); ?>đ )</div>
    </div>
  </a>
</article>