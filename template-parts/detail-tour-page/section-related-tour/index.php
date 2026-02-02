<?php
$section_related_tour = get_field('related_tour', 'option');
$related_tour_title = $section_related_tour['title'] ?? '';
$related_tour_description = $section_related_tour['description'] ?? '';
$contact_us = $section_related_tour['contact_us'];
$contact_us_title = $contact_us['title'] ?? '';
$contact_us_subtitle = $contact_us['subtitle'] ?? '';
$contact_us_description = $contact_us['description'] ?? '';
$contact_us_link = $contact_us['link'] ?? '';
if(!empty($contact_us_link)) {
	$contact_us_link_url = $contact_us_link['url'] ?? '';
	$contact_us_link_title = $contact_us_link['title'] ?? '';
	$contact_us_link_target = $contact_us_link['target'] ? $contact_us_link['target'] : '_self';
}

$current_tour_id = get_the_ID();
$dest_terms = get_the_terms($current_tour_id, 'destination');
$destination = (!empty($dest_terms) && !is_wp_error($dest_terms)) ? $dest_terms[0] : null;


$related_tours = [];
if ($destination) {
	$related_tours = get_posts([
		'post_type'      => 'tour',
		'posts_per_page' => 2,
		'post_status'    => 'publish',
		'post__not_in'   => [$current_tour_id],
		'tax_query'      => [
			[
				'taxonomy' => 'destination',
				'field'    => 'term_id',
				'terms'    => $destination->term_id,
			]
		]
	]);
}

$background_image_related_tour_contact_us = 2531;
?>
<section data-nav-target="tour-related-tour" class="related-tour">
	<div class="related-tour__container">
		<div class="related-tour__header">
			<h2 class="related-tour__title">
				<?= $related_tour_title; ?>
			</h2>
			<div class="related-tour__eyebrow">
				<?php if(!IS_MOBILE) :?>
					<p>We are a brand that caters <br/> to every memorable journey.</p>
				<?php else: ?>
					<p>We are a brand that <br/> caters to every memorable journey.</p>
				<?php endif; ?>
			</div>
		</div>

		<div class="related-tour__track js-drag-scroll">
			<?php if (!empty($related_tours)) : ?>
				<?php foreach ($related_tours as $tour) : ?>
					<?php
					$post = $tour;
					setup_postdata($post);
					?>

					<?php get_template_part('template-parts/components/tour-item/index'); ?>

				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

			<div class="related-tour__contact-us">
				<div class="related-tour__contact-us__background">
					<?= wp_get_attachment_image($background_image_related_tour_contact_us,'full',false,['loading' => 'lazy','decoding' => 'async',]); ?>
				</div>
				<div class="related-tour__contact-us__content">
					<h3 class="related-tour__contact-us__content-title">Contact us</h3>
					<p class="related-tour__contact-us__content-subtitle">Designing a journey for you</p>
					<p class="related-tour__contact-us__content-description">
						Culinary arts to ancient history and everything in between. Contact us to learn more about our expert guides!
					</p>
					<?php 
					$tour_id = get_the_ID();
					$tour_slug = get_post_field( 'post_name', $tour_id );
					$contact_params = !empty($tour_slug) ? '?tour=' . $tour_slug : '';
					?>
					<a href="/contact<?= $contact_params; ?>" class="related-tour__contact-us__content-link compound-avian-button">
						<p class="compound-avian-button__content">Contact us for advice</p>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
