<?php 
$image_decor_pc = 1876;

?>

<div data-nav-target="tour-client-feedback" class="reviews-wrapper">
	<div class="reviews-background-decor">
		<?= wp_get_attachment_image($image_decor_pc, 'full', false, array('loading'  => 'lazy')); ?>
	</div>
	<?php get_template_part('template-parts/components/section-reviews/index'); ?>
</div>