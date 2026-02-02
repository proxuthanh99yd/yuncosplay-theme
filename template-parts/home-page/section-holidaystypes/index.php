<?php
$background_top_id = 1325;
$background_bottom_id = 1326;
$background_top_mobile_id = 1324;

$section_holidaystypes = get_field('section_holidaytypes');
$title = $section_holidaystypes['title'];
$description = IS_MOBILE ? $section_holidaystypes['description_mobile'] : $section_holidaystypes['description_desktop'];

$taxonomy = 'holiday-type';
$holiday_types = [];
if (taxonomy_exists($taxonomy)) {
	$terms = get_terms([
		'taxonomy'   => $taxonomy,
		'hide_empty' => false,
		'orderby'    => 'term_order',
		'order'      => 'ASC',
	]);


	if (!is_wp_error($terms) && !empty($terms)) {
		$holiday_types = $terms;
	}
}
?>

<section id="holidaystypes" class="holidaystypes">

	<?= wp_get_attachment_image($background_top_id, 'full', false, array('class' => 'holidaystypes-background--top')) ?>
	<?= wp_get_attachment_image($background_top_mobile_id, 'full', false, array('class' => 'holidaystypes-background--top-mobile')) ?>
	<?= wp_get_attachment_image($background_bottom_id, 'full', false, array('class' => 'holidaystypes-background--bottom')) ?>

	<div class="holidaystypes-container">
		<div class="holidaystypes-header">
			<?php if (!empty($title)) : ?>
			<h2 class="holidaystypes-header__title">
				<?= $title ?>
			</h2>
			<?php endif; ?>
			<?php if (!empty($description)) : ?>
			<p class="holidaystypes-header__description">
				<?= $description ?>
			</p>
			<?php endif; ?>
		</div>
		<div class="holidaystypes-content">
			<?php if (!empty($holiday_types)) : ?>
			<?php foreach ($holiday_types as $index => $term) :
			// Lấy ACF fields cho term
			$image_pc_id     = get_field('thumbnail_pc', $term);
			$image_mobile_id = get_field('thumbnail_mobile', $term);
			
			$title = $term->name;
			$description = $term->description ?: '';
			
			$term_link = get_term_link($term);
				if (is_wp_error($term_link)) {
					continue;
				}
			?>
			<a href="<?= esc_url($term_link) ?>" class="holidaystypes-content__item">
				<?php if($image_pc_id) { echo wp_get_attachment_image($image_pc_id, 'full', false, array('class' => 'holidaystypes-content__item-image'));}?>
				<?php if($image_mobile_id) { echo wp_get_attachment_image($image_mobile_id, 'full', false, array('class' => 'holidaystypes-content__item-image--mobile'));}?>
				<div class="holidaystypes-content__item-content">
					<h3 class="holidaystypes-content__item-title">
						<?= esc_html($title) ?>
					</h3>
					<?php if (!empty($description)) : ?>
					<p class="holidaystypes-content__item-description">
						<?= esc_html($description) ?>
					</p>
					<?php endif; ?>
				</div>
			</a>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>