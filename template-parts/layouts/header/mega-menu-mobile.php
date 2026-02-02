<!-- mega menu mobile -->
<?php
$header = get_field('header', 'option');
$navigation = !empty($header['navigation']) ? $header['navigation'] : [];

$socials = !empty($header['socials']) ? $header['socials'] : [];
$phone_contact_link = !empty($header['phone_contact']) ? $header['phone_contact'] : [];
$contact_button_link = !empty($header['button_contact']) ? $header['button_contact'] : [];
$call_us_text = !empty($header['call_us_text']) ? $header['call_us_text'] : 'Call us today until 8pm';

// Language config (same logic as header)
$icon_language_america_id = 1329;
$icon_language_english_id = 1330;
$icon_language_japan_id = 1331;


$mobile_trigger_icon_id = $icon_language_english_id;
$mobile_trigger_text = 'EN';

$destinations_data = !empty($navigation['destinations']) ? $navigation['destinations'] : [];
$destination_terms = !empty($destinations_data['items']) ? $destinations_data['items'] : [];

$destinations_title = !empty($destinations_data['title']) ? $destinations_data['title'] : 'Destination';

$holiday_types = !empty($navigation['holiday_types']) ? $navigation['holiday_types'] : [];
$holiday_types_items = !empty($holiday_types['items']) ? $holiday_types['items'] : [];

$holiday_types_title = !empty($holiday_types['title']) ? $holiday_types['title'] : 'Holidays types';

$blog_categories = !empty($navigation['blog_categories']) ? $navigation['blog_categories'] : [];
$blog_categories_items = !empty($blog_categories['items']) ? $blog_categories['items'] : [];

$blog_categories_title = !empty($blog_categories['title']) ? $blog_categories['title'] : 'Inspiration';

$about_us = !empty($navigation['about_us']) ? $navigation['about_us'] : [];
$about_us_items = !empty($about_us['items']) ? $about_us['items'] : [];

$about_us_title = !empty($about_us['title']) ? $about_us['title'] : 'About Us';

$hotel_and_resorts = !empty($navigation['hotel_and_resorts']) ? $navigation['hotel_and_resorts'] : [];
$hotel_and_resorts_link = !empty($hotel_and_resorts['link']) ? $hotel_and_resorts['link'] : [];
$hotel_and_resorts_url = !empty($hotel_and_resorts_link['url']) ? $hotel_and_resorts_link['url'] : '#';
$hotel_and_resorts_title = !empty($hotel_and_resorts_link['title']) ? $hotel_and_resorts_link['title'] : 'Hotel & resorts';
$hotel_and_resorts_target = !empty($hotel_and_resorts_link['target']) ? $hotel_and_resorts_link['target'] : '_self';

$get_term_obj = static function ($term, $taxonomy) {
	$term_obj = is_object($term) ? $term : get_term($term, $taxonomy);
	if (is_wp_error($term_obj) || !$term_obj) return null;
	return $term_obj;
};

$get_term_url = static function ($term_obj) {
	$term_link = get_term_link($term_obj);
	return (!is_wp_error($term_link) && !empty($term_link)) ? $term_link : '#';
};
?>
<div class="header-mega-menu-mobile header-mega-menu-mobile--hidden">
  <div class="header-mega-menu-mobile__search">
    <div class="header-mega-menu-mobile__search-field">
      <input
        id="header-mega-menu-mobile-search-input"
        type="text"
        placeholder="Enter search content" />
      <?= wp_get_attachment_image(1061, 'full', false, array('class' => 'header-mega-menu-mobile__search-icon')) ?>
    </div>
    <div class="header-mega-menu-mobile__search-results header-mega-menu-mobile__search-results--hidden">
      <ul id="header-mega-menu-mobile-search-result" class="header-mega-menu-mobile__search-results-list"></ul>
    </div>
  </div>
  <template id="header-mega-menu-mobile-search-result-item">
    <li class="header-mega-menu-mobile__search-results-item">
      <a class="header-mega-menu-mobile__search-results-link" href="#">
        <span class="header-mega-menu-mobile__search-results-text"></span>
      </a>
    </li>
  </template>
  <div class="header-mega-menu-mobile__content">
    <ul class="header-mega-menu-mobile__list">
      <li class="header-mega-menu-mobile__item" data-has-sub-menu="1">
        <a class="header-mega-menu-mobile__item-link" href="#">
          <?= wp_get_attachment_image(1202, 'full', false, array('class' => 'header-mega-menu-mobile__item-icon')) ?>
          <span class="header-mega-menu-mobile__item-text">
            <?= esc_html($destinations_title) ?>
          </span>
          <?= wp_get_attachment_image(1060, 'full', false, array('class' => 'header-mega-menu-mobile__item-arrow')) ?>
        </a>
        <ul class="header-mega-menu-mobile__submenu">
          <?php if (!empty($destination_terms)):
            foreach ($destination_terms as $term):
              $term_obj = $get_term_obj($term, 'destination');
              if (!$term_obj) continue;
              $link_url = $get_term_url($term_obj);
          ?>
          <li class="header-mega-menu-mobile__submenu-item">
            <a class="header-mega-menu-mobile__submenu-link" href="<?= esc_url($link_url) ?>">
              <?= esc_html($term_obj->name) ?>
            </a>
          </li>
          <?php
            endforeach;
          endif;
          ?>
        </ul>
      </li>

      <li class="header-mega-menu-mobile__item" data-has-sub-menu="2">
        <a class="header-mega-menu-mobile__item-link" href="#">
          <?= wp_get_attachment_image(1202, 'full', false, array('class' => 'header-mega-menu-mobile__item-icon')) ?>
          <span class="header-mega-menu-mobile__item-text">
            <?= esc_html($holiday_types_title) ?>
          </span>
          <?= wp_get_attachment_image(1060, 'full', false, array('class' => 'header-mega-menu-mobile__item-arrow')) ?>
        </a>
        <ul class="header-mega-menu-mobile__submenu">
          <?php if (!empty($holiday_types_items)):
            foreach ($holiday_types_items as $term):
              $term_obj = $get_term_obj($term, 'holiday-type');
              if (!$term_obj) continue;
              $link_url = $get_term_url($term_obj);
          ?>
          <li class="header-mega-menu-mobile__submenu-item">
            <a class="header-mega-menu-mobile__submenu-link" href="<?= esc_url($link_url) ?>">
              <?= esc_html($term_obj->name) ?>
            </a>
          </li>
          <?php
            endforeach;
          endif;
          ?>
        </ul>
      </li>

      <li class="header-mega-menu-mobile__item" data-has-sub-menu="3">
        <a class="header-mega-menu-mobile__item-link" href="#">
          <?= wp_get_attachment_image(1202, 'full', false, array('class' => 'header-mega-menu-mobile__item-icon')) ?>
          <span class="header-mega-menu-mobile__item-text">
            <?= esc_html($blog_categories_title) ?>
          </span>
          <?= wp_get_attachment_image(1060, 'full', false, array('class' => 'header-mega-menu-mobile__item-arrow')) ?>
        </a>
        <ul class="header-mega-menu-mobile__submenu">
          <?php if (!empty($blog_categories_items)):
            foreach ($blog_categories_items as $category):
              $term_obj = $get_term_obj($category, 'category');
              if (!$term_obj) continue;
              $link_url = $get_term_url($term_obj);
          ?>
          <li class="header-mega-menu-mobile__submenu-item">
            <a class="header-mega-menu-mobile__submenu-link" href="<?= esc_url($link_url) ?>">
              <?= esc_html($term_obj->name) ?>
            </a>
          </li>
          <?php
            endforeach;
          endif;
          ?>
        </ul>
      </li>

      <li class="header-mega-menu-mobile__item">
        <a class="header-mega-menu-mobile__item-link" href="<?= esc_url($hotel_and_resorts_url) ?>" target="<?= esc_attr($hotel_and_resorts_target) ?>">
          <span class="header-mega-menu-mobile__item-text">
            <?= esc_html($hotel_and_resorts_title) ?>
          </span>
        </a>
      </li>

      <li class="header-mega-menu-mobile__item" data-has-sub-menu="4">
        <a class="header-mega-menu-mobile__item-link" href="#">
          <?= wp_get_attachment_image(1202, 'full', false, array('class' => 'header-mega-menu-mobile__item-icon')) ?>
          <span class="header-mega-menu-mobile__item-text">
            <?= esc_html($about_us_title) ?>
          </span>
          <?= wp_get_attachment_image(1060, 'full', false, array('class' => 'header-mega-menu-mobile__item-arrow')) ?>
        </a>
        <ul class="header-mega-menu-mobile__submenu">
          <?php if (!empty($about_us_items)):
            foreach ($about_us_items as $item):
              $link = !empty($item['link']) ? $item['link'] : [];
              $link_url = !empty($link['url']) ? $link['url'] : '#';
              $link_title = !empty($link['title']) ? $link['title'] : '';
              $link_target = !empty($link['target']) ? $link['target'] : '_self';
              if (empty($link_title)) continue;
          ?>
          <li class="header-mega-menu-mobile__submenu-item">
            <a class="header-mega-menu-mobile__submenu-link" href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>">
              <?= esc_html($link_title) ?>
            </a>
          </li>
          <?php
            endforeach;
          endif;
          ?>
        </ul>
      </li>
    </ul>
  </div>
  <div class="header-mega-menu-mobile__footer">
    <div class="header-mega-menu-mobile__footer-content">
      <div class="header-mega-menu-mobile__footer-content-contact">
        <div class="header-mega-menu-mobile__footer-content-contact-left">
          <span class="header-mega-menu-mobile__footer-content-contact-left-text"><?= esc_html($call_us_text) ?></span>
          <div class="header-mega-menu-mobile__footer-content-contact-left-phone">
            <?= wp_get_attachment_image(1056, 'full', false, array('class' => 'header-mega-menu-mobile__footer-content-contact-left-phone-icon')) ?>
            <a class="header-mega-menu-mobile__footer-content-contact-left-phone-text" href="<?= esc_url(!empty($phone_contact_link['url']) ? $phone_contact_link['url'] : '#') ?>" target="<?= esc_attr(!empty($phone_contact_link['target']) ? $phone_contact_link['target'] : '_self') ?>">
              <?= esc_html(!empty($phone_contact_link['title']) ? $phone_contact_link['title'] : '+84 906 888 888') ?>
            </a>
          </div>
        </div>
        <a class="header-mega-menu-mobile__footer-content-contact-right compound-avian-button" href="<?= esc_url(!empty($contact_button_link['url']) ? $contact_button_link['url'] : '#') ?>" target="<?= esc_attr(!empty($contact_button_link['target']) ? $contact_button_link['target'] : '_self') ?>">
          <div class="compound-avian-button__content">
            <span class="compound-avian-button__content-text">
              <?= esc_html(!empty($contact_button_link['title']) ? $contact_button_link['title'] : 'Request a Quote') ?>
            </span>
          </div>
        </a>
      </div>
      <div class="header-mega-menu-mobile__footer-content-socials-language">
        <div class="header-mega-menu-mobile__footer-content-socials">
          <?php if (!empty($socials)):
            foreach ($socials as $social):
              $social_link = !empty($social['link']) ? $social['link'] : [];
              $social_url = !empty($social_link['url']) ? $social_link['url'] : '#';
              $social_target = !empty($social_link['target']) ? $social_link['target'] : '_self';

              $social_image = $social['image'] ?? 0;
              $social_image_id = 0;
              if (is_array($social_image)) {
                $social_image_id = (int) ($social_image['ID'] ?? 0);
              } elseif (is_numeric($social_image)) {
                $social_image_id = (int) $social_image;
              }

              if (!$social_image_id) continue;
          ?>
          <a class="header-mega-menu-mobile__footer-content-socials-item" href="<?= esc_url($social_url) ?>" target="<?= esc_attr($social_target) ?>">
            <?= wp_get_attachment_image($social_image_id, 'full', false, array('class' => 'header-mega-menu-mobile__footer-content-socials-item-icon')) ?>
          </a>
          <?php
            endforeach;
          endif;
          ?>
        </div>
        <div class="header-mega-menu-mobile__footer-content-language">
          <span class="header-mega-menu-mobile__footer-content-language-text">Language:</span>
          <div class="header-mega-menu-mobile__footer-content-language-drawer-container">
            <custom-drawer class="header-mega-menu-mobile__footer-content-language-drawer" data-direction="bottom">
              <custom-drawer-trigger class="header-mega-menu-mobile__footer-content-language-drawer-trigger">
                <div class="header-mega-menu-mobile__footer-content-language-drawer-trigger-content">
                  <?= wp_get_attachment_image($mobile_trigger_icon_id, 'full', false, array('class' => 'header-mega-menu-mobile__footer-content-language-drawer-trigger-icon')) ?>
                  <span class="header-mega-menu-mobile__footer-content-language-drawer-trigger-text notranslate"><?= esc_html($mobile_trigger_text) ?></span>
                  <?= wp_get_attachment_image(1060, 'full', false, array('class' => 'header-mega-menu-mobile__footer-content-language-drawer-trigger-arrow')) ?>
                </div>
              </custom-drawer-trigger>
              <custom-drawer-content class="header-mega-menu-mobile__footer-content-language-drawer-content">
                <div class="header-mega-menu-mobile__footer-content-language-drawer-content-inner ">
                  <p class="header-mega-menu-mobile__footer-content-language-drawer-content-inner-title notranslate">Change Language</p>
                  <?= do_shortcode('[gtranslate]') ?>
                </div>
              </custom-drawer-content>
            </custom-drawer>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- mega menu mobile -->