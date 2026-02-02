<?php
$journey = get_field("about_journey");
$title = isset($journey['title']) ? $journey['title'] : '';
$contact = isset($journey['contact']) ? $journey['contact'] : [];

$image_background = 2282;
$image_background_mb = 2281;

?>

<section class="section__journey">
  <div class="section__journey__content">
    <h2 class="section__journey__title">
      <?= esc_html($title); ?>
    </h2>
    <div class="section__journey__content__contact">
      <div class="section__journey__content__contact__item">
        <span class="section__journey__content__contact__item__text"><?= esc_html($contact['contact_text']); ?></span>
        <a href="<?= esc_attr($contact['contact_phone']['url']); ?>"
          class="section__journey__content__contact__item__link">
          <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
            <path
              d="M8.72895 11.8116L7.26758 13.273C6.95951 13.5811 6.46975 13.5811 6.15378 13.2809C6.06688 13.194 5.97999 13.115 5.8931 13.0281C5.07947 12.2066 4.34484 11.3456 3.68919 10.4451C3.04145 9.54453 2.5201 8.64401 2.14093 7.75139C1.76966 6.85087 1.58008 5.98984 1.58008 5.16832C1.58008 4.63116 1.67487 4.11771 1.86445 3.64375C2.05404 3.16189 2.35421 2.71953 2.77287 2.32457C3.27843 1.82691 3.83138 1.58203 4.41593 1.58203C4.63711 1.58203 4.85829 1.62943 5.05577 1.72422C5.26115 1.81901 5.44284 1.9612 5.58503 2.16658L7.41767 4.74965C7.55985 4.94714 7.66254 5.12882 7.73364 5.3026C7.80473 5.46849 7.84423 5.63438 7.84423 5.78446C7.84423 5.97405 7.78893 6.16363 7.67834 6.34531C7.57565 6.527 7.42556 6.71658 7.23598 6.90616L6.63563 7.53021C6.54874 7.6171 6.50924 7.71979 6.50924 7.84618C6.50924 7.90938 6.51714 7.96467 6.53294 8.02786C6.55664 8.09106 6.58034 8.13845 6.59614 8.18585C6.73833 8.44653 6.9832 8.7862 7.33077 9.19696C7.68624 9.60773 8.06541 10.0264 8.47617 10.4451C8.55516 10.524 8.64206 10.603 8.72105 10.682C9.03702 10.9901 9.04492 11.4957 8.72895 11.8116Z"
              fill="#630F3F" />
            <path
              d="M17.3554 14.4826C17.3554 14.7037 17.3159 14.9328 17.2369 15.154C17.2132 15.2172 17.1895 15.2804 17.1579 15.3436C17.0237 15.628 16.8499 15.8965 16.6208 16.1493C16.2337 16.5759 15.8072 16.8839 15.3253 17.0814C15.3174 17.0814 15.3095 17.0893 15.3016 17.0893C14.8355 17.2789 14.33 17.3816 13.7849 17.3816C12.9792 17.3816 12.1182 17.192 11.2098 16.8049C10.3013 16.4179 9.39293 15.8965 8.4924 15.2409C8.18433 15.0118 7.87626 14.7827 7.58398 14.5378L10.1671 11.9548C10.3882 12.1207 10.5857 12.247 10.7516 12.3339C10.7911 12.3497 10.8385 12.3734 10.8938 12.3971C10.957 12.4208 11.0202 12.4287 11.0913 12.4287C11.2256 12.4287 11.3283 12.3813 11.4151 12.2944L12.0155 11.702C12.213 11.5045 12.4026 11.3544 12.5842 11.2596C12.7659 11.149 12.9476 11.0938 13.1451 11.0938C13.2952 11.0938 13.4532 11.1253 13.627 11.1964C13.8007 11.2675 13.9824 11.3702 14.1799 11.5045L16.7946 13.3609C17 13.503 17.1421 13.6689 17.229 13.8664C17.308 14.0639 17.3554 14.2614 17.3554 14.4826Z"
              fill="#630F3F" />
          </svg>
          <?= esc_html($contact['contact_phone']['title']); ?>
        </a>
      </div>
      <a href="<?= esc_attr($contact['contact_page']['url']); ?>" class="highlights-content__contact-link highlights-content__contact-link--pc compound-avian-button compound-avian-button--lg section__journey__content__contact__link">
        <div class="compound-avian-button__content">
          <?= esc_html($contact['contact_page']['title']) ?>
        </div>
      </a>
    </div>
  </div>
  <picture class="section__journey__background__picture">
    <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($image_background_mb, 'full')) . '" />' ?>
    <?= wp_get_attachment_image($image_background, 'full', false, array('class' => 'section__journey__background')) ?>
  </picture>
</section>