<!-- Reviews Popup -->
<div class="reviews-popup">
	<div class="reviews-popup-overlay"></div>
	<div class="reviews-popup-content">
		<button class="reviews-popup-close" aria-label="Close popup">
			<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
				<path d="M0.271767 10.3446C-0.0881408 10.7045 -0.0954858 11.3435 0.279112 11.7181C0.661056 12.0927 1.30008 12.0854 1.65264 11.7328L6.00092 7.38454L10.3419 11.7255C10.7091 12.0927 11.3408 12.0927 11.7154 11.7181C12.09 11.3362 12.09 10.7119 11.7227 10.3446L7.38179 6.00367L11.7227 1.6554C12.09 1.28815 12.0973 0.656471 11.7154 0.281873C11.3408 -0.0927245 10.7091 -0.0927245 10.3419 0.274528L6.00092 4.61546L1.65264 0.274528C1.30008 -0.0853795 0.653711 -0.10007 0.279112 0.281873C-0.0954858 0.656471 -0.0881408 1.30284 0.271767 1.6554L4.6127 6.00367L0.271767 10.3446Z" fill="#fff" />
			</svg>
		</button>
		<div class="reviews-popup-gallery">
			<div class="reviews-popup-gallery-overlay"></div>
			<div class="reviews-popup-gallery-main">
				<div class="reviews-popup-gallery-swiper swiper">
					<div class="swiper-wrapper"></div>
				</div>
			</div>
			<div class="reviews-popup-gallery-thumbs">
				<div class="reviews-popup-gallery-thumbs-swiper swiper">
					<div class="swiper-wrapper"></div>
				</div>
			</div>
		</div>
		<div class="reviews-popup-content-inner">
			<div class="reviews-popup-header">
				<div class="reviews-popup-avatar">
					<img src="" alt="" class="reviews-popup-avatar-img">
				</div>
				<div class="reviews-popup-info">
					<h3 class="reviews-popup-name"></h3>
					<span class="reviews-popup-date"></span>
				</div>
				<div class="reviews-popup-social"></div>
			</div>

			<div class="reviews-popup-rating"></div>

			<div class="reviews-popup-review">
				<span class="reviews-popup-review-title"></span>
				<div class="reviews-popup-review-description" data-lenis-prevent></div>
			</div>

			<?php if (!empty($link_trustpilot_url)) : ?>
			<a href="<?= esc_url($link_trustpilot_url) ?>"
			   target="<?= esc_attr($link_trustpilot_target) ?>"
			   rel="noopener noreferrer" 
			   class="reviews-popup-trustpilot-badge">
				<span class="trustpilot-text">
					Rated&nbsp;<strong><?= $rating ?></strong>&nbsp;out of 5 based on
				</span>
				<span class="trustpilot-text">
					<strong><?= $reviews_count ?> reviews</strong>&nbsp;on
					<?= wp_get_attachment_image($trustpilot_star_mobile_id, 'full', false, ['class' => 'trustpilot-logo-image']) ?>
					<strong>Trustpilot</strong>
				</span>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>