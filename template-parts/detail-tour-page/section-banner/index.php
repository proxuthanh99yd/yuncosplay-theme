<?php
$tour_title = get_the_title() ?: '';
$tour_duration = is_numeric(get_field('tour_duration')) ? (int) get_field('tour_duration') : 0;
$tour_price = is_numeric(get_field('tour_price')) ? '$' . number_format(get_field('tour_price'), 0, '.', ',') : '$ 0';

$duration_label = ($tour_duration <= 1) ? 'Day' : 'Days';

$section_banner = get_field('banner');
$description = '';
$background_pc = null;
$background_mb = null;
$reference_blog = null;

if(!empty($section_banner)) {
	$description = $section_banner['description'];
	$background_pc = $section_banner['background_pc'];
	$background_mb = $section_banner['background_mb'];
    $reference_blog = $section_banner['reference_blog'];
}

$icon_discover_id = 1122;
$icon_location_id = 1707;
$icon_arrow_right_id = 1706;

?>
<section id="banner" class="detail-tour-banner">
	<div class="detail-tour-banner__overlay"></div>

	<!-- Background pc -->
	<?php if($background_pc): ?>
	<?php echo wp_get_attachment_image($background_pc, 'full', false, array( 'class' => 'detail-tour-banner__image detail-tour-banner__image--pc')); ?>
	<?php endif; ?>

	<!-- Background mb -->
	<?php if($background_mb): ?>
	<?php echo wp_get_attachment_image($background_mb, 'full', false, array( 'class' => 'detail-tour-banner__image detail-tour-banner__image--mb')); ?>
	<?php endif; ?>


	<div class="detail-tour-banner__content">
		<h1 class="detail-tour-banner__title">
			<?= $tour_title; ?>
		</h1>

		<div class="detail-tour-banner__tooltip">
			<div class="detail-tour-banner__tooltip-hover">
				<?= $tour_duration; ?> <?= $duration_label ?> from <span><?= $tour_price; ?></span>
			</div>
			<div class="detail-tour-banner__tooltip-box">
				<p><?= $description; ?></p>
			</div>
		</div>
	</div>

	<div class="detail-tour-banner__logo banner-discover-btn">
		<?= wp_get_attachment_image($icon_discover_id, 'full', false, array( 'class' => 'detail-tour-banner__discover-icon')); ?>
		<div class="detail-tour-banner__logo-text">Discover</div>
	</div>

	<?php if($reference_blog): 
		// Lấy title và link của bài post
		$post_title = get_the_title($reference_blog->ID); // Lấy tiêu đề bài post
		$post_link = get_permalink($reference_blog->ID); // Lấy link bài post	
	?>
    <div class="banner__where-is-this">
		<?= wp_get_attachment_image($icon_location_id, 'full', false, array( 'class' => 'banner__where-is-this-icon')) ?>
		<div class="banner_line"></div>
		<div class="">
		<p class="banner__where-is-this-text">Where is this ?</p>
		<p class="banner__where-is-this-title"><?= $post_title ?></p>
		<div class="banner__where-is-this-link-container">
			<a href="<?= $post_link ?>" class="banner__where-is-this-link">See more of this location</a>
			<svg class="banner__where-is-this-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
			<path d="M4.66602 11.0835L9.04116 6.6045L4.66602 2.33349" stroke="white" stroke-opacity="0.8"/>
			</svg>
		</div>
		</div>
	</div>
    <?php endif; ?>
</section>