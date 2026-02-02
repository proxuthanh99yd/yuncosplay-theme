<?php 
$icon_arrow = 1119;
$icon_zoom_in = 1120;
$icon_zoom_out = 1121;
?>

<div class="destinations-popup">
	<div class="destination-popup-overlay"></div>
	<div class="destinations-popup-content">
		<button class="destinations-popup-close">
			<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
				<path d="M0.271767 10.3446C-0.0881408 10.7045 -0.0954858 11.3435 0.279112 11.7181C0.661056 12.0927 1.30008 12.0854 1.65264 11.7328L6.00092 7.38454L10.3419 11.7255C10.7091 12.0927 11.3408 12.0927 11.7154 11.7181C12.09 11.3362 12.09 10.7119 11.7227 10.3446L7.38179 6.00367L11.7227 1.6554C12.09 1.28815 12.0973 0.656471 11.7154 0.281873C11.3408 -0.0927245 10.7091 -0.0927245 10.3419 0.274528L6.00092 4.61546L1.65264 0.274528C1.30008 -0.0853795 0.653711 -0.10007 0.279112 0.281873C-0.0954858 0.656471 -0.0881408 1.30284 0.271767 1.6554L4.6127 6.00367L0.271767 10.3446Z" fill="#111164" fill-opacity="0.6"/>
			</svg>
		</button>
		<div class="destinations-popup-header">
			<div class="destination-capital__popup__thumbnail">
				<img src="" alt="" class="destination-popup-thumbnail-img">
			</div>
			<div class="destination-capital__popup__info">
				<h3 class="destination-capital__popup__title"></h3>
				<p class="destination-capital__popup__tour-quantity">
					<span class="destination-capital__popup__tour-quantity__label">Local tours: </span>
					<span class="destination-capital__popup__tour-quantity__value"></span>
				</p>
			</div>
		</div>

		<p data-lenis-prevent class="destination-capital__popup-content__description"></p>
		<div class="destinations-popup-footer">
			<a href="#" class="destinations-popup__link <?= IS_MOBILE || wp_is_mobile() ? 'compound-avian-button' : '' ?>">
				<div class="compound-avian-button__content">
					<span class="destinations-popup__link-text">Explore the tour</span>
					<?= wp_get_attachment_image($icon_arrow, 'full', false, array( 'class' => 'destinations-popup__link-icon')) ?>
				</div>
			</a>

			<button class="destination-capital__popup__collapse-btn">
				<?= wp_get_attachment_image($icon_zoom_in, 'full', false, array( 'class' => 'destination-capital__popup__collapse-btn__icon destination-capital__popup__collapse-btn__icon--zoom-in')) ?>
				<?= wp_get_attachment_image($icon_zoom_out, 'full', false, array( 'class' => 'destination-capital__popup__collapse-btn__icon destination-capital__popup__collapse-btn__icon--zoom-out')) ?>
			</button>
		</div>
	</div>
</div>