<?php
// Get related articles based on current post's categories, or latest posts if no related
$current_post_id = get_the_ID();
$categories = get_the_category($current_post_id);

$related_articles_args = array(
    'post_type' => 'post',
    'posts_per_page' => 10,
    'post_status' => 'publish',
    'post__not_in' => array($current_post_id), // Exclude current post
    'orderby' => 'date',
    'order' => 'DESC',
);

// If post has categories, prioritize posts from same categories
if (!empty($categories)) {
    $category_ids = wp_list_pluck($categories, 'term_id');
    $related_articles_args['category__in'] = $category_ids;
}

$related_articles_query = new WP_Query($related_articles_args);

// If no related posts found via categories, get latest posts
if (!$related_articles_query->have_posts()) {
    $related_articles_args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'post__not_in' => array($current_post_id),
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $related_articles_query = new WP_Query($related_articles_args);
}

$blog_count = $related_articles_query->post_count;

$arrow_right_id = 1210;
$separator_id = 1202;
$fallback_image_id = 1916;

$related_article = get_field('related_article');
$title = $related_article['title'] ?? '';
$description = $related_article['description'] ?? '';
?>

<section id="related-article" class="related-article">

	<div class="related-article__container">
		<div class="related-article__title">
			<?php if (!empty($description)) : ?>
			<p class="related-article__title-text"><?= $description ?></p>
			<?php endif; ?>
			<?php if (!empty($title)) : ?>
			<h2 class="related-article__title-heading"><?= $title ?></h2>
			<?php endif; ?>
		</div>

		<div class="related-article__content">
			<div id="related-article-swiper" class="related-article__swiper swiper">
				<div class="swiper-wrapper">
					<?php if ($related_articles_query->have_posts()) : ?>
					<?php while ($related_articles_query->have_posts()) : $related_articles_query->the_post(); ?>
					<?php
					// dữ liệu cơ bản
					$permalink = get_permalink();
					$title = get_the_title();

					// thumbnail
					$thumb_id = get_post_thumbnail_id();

					// category (lấy category đầu tiên)
					$categories = get_the_category();
					$category_name = !empty($categories) ? $categories[0]->name : '';

					$content = get_the_content();
					$word_count = str_word_count(wp_strip_all_tags($content));
					$reading_time = max(1, ceil($word_count / 200));
					?>
					<div class="swiper-slide">
						<article href="<?= esc_url($permalink); ?>" class="related-article__item">
							<a href="<?= esc_url($permalink); ?>" class="related-article__item-link"></a>
							<div class="related-article__item-overlay"></div>
							<?php
							$image_id = $thumb_id ?: $fallback_image_id;
							if ($image_id) :
							?>
							    <?= wp_get_attachment_image($image_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'related-article__item-image')); ?>
							<?php endif; ?>

							<div class="related-article__item-content">
								<div class="related-article__item-meta">
									<?php if ($category_name) : ?>
									<span class="related-article__item-category"><?= esc_html($category_name); ?></span>
									<?= wp_get_attachment_image($separator_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'related-article__item-separator')); ?>
									<?php endif; ?>
									<span class="related-article__item-reading-time">
										<?= esc_html($reading_time); ?> min read
									</span>
								</div>

								<h3 class="related-article__item-title">
									<span class="title-normal"><?= esc_html($title); ?></span>
									<span class="title-hover"><?= esc_html($title); ?></span>
								</h3>
							</div>  
						</article>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($blog_count > 4) : ?>
			<div class="related-article__navigation">
				<button class="related-article__button related-article__button--prev" aria-label="Previous slide">
					<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'related-article__button-icon')) ?>
				</button>
				<button class="related-article__button related-article__button--next" aria-label="Next slide">
					<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'related-article__button-icon')) ?>
				</button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>