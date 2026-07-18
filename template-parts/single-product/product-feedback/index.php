<?php
$reviews = get_field('product_review');
if (! is_array($reviews) || empty($reviews)) {
    return;
}

// nền feedback → file tĩnh theme (okhub_img)
?>

<section class="product-fb">
    <?= okhub_img('single-product/feedback-bg', array('class' => 'product-fb__bg')) ?>
    <div class="product-fb__container">

        <h2 class="product-fb__title">
            Feedback của khách hàng
        </h2>

        <!-- MEDIA SWIPER -->
        <div class="product-fb__media-swiper swiper">
            <div class="swiper-wrapper">

                <?php foreach ($reviews as $review_id) : ?>
                    <?php
                    $customer_media = get_field('customer_media', $review_id);
                    if (!is_array($customer_media)) {
                        continue;
                    }
                    $media_type = $customer_media['type_media'] ?? 'image';
                    $image_id = $customer_media['image'] ?? '';
                    $video = $customer_media['video'] ?? [];
                    $video_link = $video['link_tiktok'] ?? '';
                    $video_thumbnail = $video['thumbnail'] ?? '';

                    $tiktok_video_id = '';
                    if (! empty($video_link) && preg_match('#/video/(\d+)#', $video_link, $m)) {
                        $tiktok_video_id = $m[1];
                    }
                    ?>
                    <div class="product-fb__media-slide swiper-slide">

                        <article class="product-fb__media-video">
                            <?php if ($media_type === 'image' && $image_id) : ?>
                                <div class="product-fb__media-thumbnail">
                                    <?= wp_get_attachment_image($image_id, 'full', false, array('class' => 'product-fb__media-thumbnail-image')); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($media_type === 'tiktok' && $tiktok_video_id && $video_thumbnail) : ?>
                                <div class="product-fb__media-thumbnail">
                                    <?= wp_get_attachment_image($video_thumbnail, 'full', false, array('class' => 'product-fb__media-thumbnail-image')); ?>
                                </div>
                                <button class="product-fb__media-play-button" type="button" aria-label="Play video">
                                    <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.3" cx="26" cy="26" r="26" fill="white" />
                                        <g clip-path="url(#clip0_3724_14126)">
                                            <path d="M20.2856 17L35.7142 26L20.2856 35V17Z" fill="white" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_3724_14126">
                                                <rect width="18" height="18" fill="white" transform="translate(19 17)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </button>
                                <div class="product-fb__media-embed" data-video-id="<?= esc_attr($tiktok_video_id) ?>">
                                    <iframe
                                        class="product-fb__media-iframe"
                                        src=""
                                        loading="lazy"
                                        allow="autoplay; encrypted-media; picture-in-picture"
                                        title="TikTok video">
                                    </iframe>

                                    <div class="product-fb__media-overlay" aria-hidden="true"></div>
                                </div>
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CONTENT SWIPER -->
        <div class="product-fb__content-swiper swiper">
            <div class="swiper-wrapper">

                <?php foreach ($reviews as $review_id) : ?>
                    <?php
                    $review_post = get_post($review_id);
                    if (!$review_post) {
                        continue;
                    }
                    $customer_media = get_field('customer_media', $review_id);
                    $video = $customer_media['video'] ?? [];
                    $video_link = $video['link_tiktok'] ?? '';
                    ?>

                    <div class="product-fb__content-slide swiper-slide">
                        <article class="product-fb__content">
                            <div class="product-fb__text">
                                <?= apply_filters('the_content', $review_post->post_content); ?>
                            </div>
                            <div class="product-fb__meta">
                                <p class="product-fb__author"><?= esc_html($review_post->post_title); ?></p>
                                <?php if ($video_link) : ?>
                                    <a href="<?= esc_url($video_link) ?>" target="_blank" rel="noopener noreferrer" class="product-fb__link">
                                        Xem video trên TikTok →
                                    </a>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pagination -->
        <div class="product-fb__pagination">
            <button type="button" class="product-fb__pagination-button product-fb__pagination-button--prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M3.55762 10.0003L8.91992 15.3617L10.2939 15.3197L8.14355 13.1693C7.4445 12.4712 6.80521 11.8461 6.22461 11.2943L5.42383 10.5326L6.52832 10.5277L15.415 10.4857L15.3672 9.42416L6.44629 9.46713L5.32324 9.47299L6.13867 8.70053C6.42226 8.43184 6.72109 8.14411 7.03516 7.83725L8.02344 6.85873L10.252 4.6312L8.9707 4.5892L3.55762 10.0003Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                </svg>
            </button>
            <button type="button" class="product-fb__pagination-button product-fb__pagination-button--next">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.4424 10.0003L11.0801 15.3617L9.70605 15.3197L11.8564 13.1693C12.5555 12.4712 13.1948 11.8461 13.7754 11.2943L14.5762 10.5326L13.4717 10.5277L4.58496 10.4857L4.63281 9.42416L13.5537 9.46713L14.6768 9.47299L13.8613 8.70053C13.5777 8.43184 13.2789 8.14411 12.9648 7.83725L11.9766 6.85873L9.74805 4.6312L11.0293 4.5892L16.4424 10.0003Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                </svg>
            </button>
        </div>
    </div>
</section>
