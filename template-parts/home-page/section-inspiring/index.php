<?php
$arrow_right_id = 1210;
$arrow_right_mobile_id = 1365;
$background_desktop_id = 2478;
$background_mobile_id = 1337;

$section_tours = get_field('section_tours');
$title = $section_tours['title'];
$description = $section_tours['description'];
$outstanding_tours = $section_tours['outstanding_tours'];
$fallback_image_id = 1916;
?>

<section id="inspiring" class="inspiring">
	<?= wp_get_attachment_image($background_desktop_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'inspiring__background-image--desktop')) ?>
	<?= wp_get_attachment_image($background_mobile_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'inspiring__background-image--mobile')) ?>

	<div class="inspiring__container">
		<div class="inspiring__header">
			<p class="inspiring__description">
				<?= $description; ?>
			</p>
			<h2 class="inspiring__title">
				<?= $title; ?>
			</h2>
		</div>
		<div class="inspiring__content">
			<div id="inspiring-swiper" class="inspiring__swiper swiper">
				<div class="swiper-wrapper">
					<?php if (!empty($outstanding_tours)) : ?>
						<?php foreach ($outstanding_tours as $tour) : ?>
							<?php
							$post = $tour;
							setup_postdata($post);
							?>
							<div class="swiper-slide">
								<?php get_template_part('template-parts/components/tour-item/index'); ?>
							</div>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php
			// Đếm số lượng tours thực tế được hiển thị
			$tour_count = 0;
			if (!empty($outstanding_tours)) {
				foreach ($outstanding_tours as $tour_id) {
					if (is_numeric($tour_id)) {
						$tour_post = get_post($tour_id);
						if ($tour_post) {
							$tour_count++;
						}
					}
				}
			}
			// Chỉ hiển thị navigation nếu có nhiều hơn 3 tours
			if ($tour_count > 3) :
			?>
			<div class="inspiring__navigation">
				<button class="inspiring__button inspiring__button--prev" aria-label="Previous slide">
					<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'inspiring__button-icon')) ?>
				</button>
				<button class="inspiring__button inspiring__button--next" aria-label="Next slide">
					<?= wp_get_attachment_image($arrow_right_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'inspiring__button-icon')) ?>
				</button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>