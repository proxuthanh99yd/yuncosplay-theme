<?php
$section_travelguide = get_field('section_travelguide');
$title = $section_travelguide['title'];
$description = $section_travelguide['description'];

$background_desktop_id = 1205;
$background_mobile_top_id = 1203;
$background_mobile_bottom_id = 1204;
$arrow_right_id = 1210;
$separator_id = 1202;

$outstanding_blogs = $section_travelguide['outstanding_blogs'];
$blog_count = is_array($outstanding_blogs) ? count($outstanding_blogs) : 0;
?>

<section id="travelguide" class="travelguide">

	<div class="travelguide__background travelguide__background--desktop">
		<?= wp_get_attachment_image($background_desktop_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__background-image')) ?>
	</div>
	<div class="travelguide__background travelguide__background--mobile-top">
		<?= wp_get_attachment_image($background_mobile_top_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__background-image')) ?>
	</div>
	<div class="travelguide__background travelguide__background--mobile-bottom">
		<?= wp_get_attachment_image($background_mobile_bottom_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__background-image')) ?>
	</div>

	<div class="travelguide__container">
		<div class="travelguide__title">
			<?php if (!empty($description)) : ?>
			<p class="travelguide__title-text"><?= $description ?></p>
			<?php endif; ?>
			<?php if (!empty($title)) : ?>
			<h2 class="travelguide__title-heading"><?= $title ?></h2>
			<?php endif; ?>
		</div>

		<div class="travelguide__content">
			<div id="travelguide-swiper" class="travelguide__swiper swiper">
				<div class="swiper-wrapper">
					<?php if (!empty($outstanding_blogs)) : ?>
					<?php foreach ($outstanding_blogs as $post_id) : ?>
					<?php
					// dữ liệu cơ bản
					$permalink = get_permalink($post_id);
					$title = get_the_title($post_id);

					// thumbnail
					$thumb_id = get_post_thumbnail_id($post_id);

					// category (lấy category đầu tiên)
					$categories = get_the_category($post_id);
					$category_name = !empty($categories) ? $categories[0]->name : '';

					$content = get_post_field('post_content', $post_id);
					$word_count = str_word_count(wp_strip_all_tags($content));
					$reading_time = max(1, ceil($word_count / 200));
					?>
					<div class="swiper-slide">
						<article href="<?= esc_url($permalink); ?>" class="travelguide__item">
							<a href="<?= esc_url($permalink); ?>" class="travelguide__item-link"></a>
							<div class="travelguide__item-overlay"></div>
							<?php if ($thumb_id) : ?>
							<?= wp_get_attachment_image($thumb_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__item-image')); ?>
							<?php endif; ?>

							<div class="travelguide__item-content">
								<div class="travelguide__item-meta">
									<?php if ($category_name) : ?>
									<span class="travelguide__item-category"><?= esc_html($category_name); ?></span>
									<?= wp_get_attachment_image($separator_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__item-separator')); ?>
									<?php endif; ?>
									<span class="travelguide__item-reading-time">
										<?= esc_html($reading_time); ?> min read
									</span>
								</div>

								<h3 class="travelguide__item-title">
									<span class="title-normal"><?= esc_html($title); ?></span>
									<span class="title-hover"><?= esc_html($title); ?></span>
								</h3>
							</div>  
						</article>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($blog_count > 4) : ?>
			<div class="travelguide__navigation">
				<button class="travelguide__button travelguide__button--prev" aria-label="Previous slide">
					<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__button-icon')) ?>
				</button>
				<button class="travelguide__button travelguide__button--next" aria-label="Next slide">
					<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'travelguide__button-icon')) ?>
				</button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>