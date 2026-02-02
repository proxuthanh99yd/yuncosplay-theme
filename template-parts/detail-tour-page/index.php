<?php
get_template_part('template-parts/detail-tour-page/assets/custom-components/index'); 
?>

<div class="detail-tour-page">
	<?php
	get_template_part('template-parts/detail-tour-page/section-banner/index'); 
	get_template_part('template-parts/detail-tour-page/section-tour-overview/index'); 
	get_template_part('template-parts/detail-tour-page/section-detailed-itinerary/index'); 
	?>
	<?= wp_get_attachment_image(2110, 'full', false, array( 'class' => 'detail-tour-decor')); ?>
	<div class="detail-tour-bg-wrap">
		<?php
		get_template_part('template-parts/detail-tour-page/section-when-to-go/index'); 
		get_template_part('template-parts/detail-tour-page/section-client-say/index'); 
		?>
	</div>

	<?php
	get_template_part('template-parts/detail-tour-page/section-related-tour/index'); 
	get_template_part('template-parts/home-page/section-reason/index'); 
	?>
</div>
