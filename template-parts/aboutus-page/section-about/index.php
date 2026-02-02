<?php
$about = get_field("about_about");
$item_1 = $about['item_1'];
$item_2 = $about['item_2'];
$item_3 = $about['item_3'];
$item_4 = $about['item_4'];

$image_about_shadow_2 = 2288;
?>

<section class="section__about">
  <div class="container about">
    <div class="about__item">
      <picture data-aos="fade-right">
        <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($item_1['image']['mobile'], 'full')) . '" />' ?>
        <?= wp_get_attachment_image($item_1['image']['desktop'], 'full', false, array('class' => 'about__image__item')) ?>
      </picture>
      <div></div>
      <div class="about__content" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="about__title">
          <?= esc_html($item_1['title']) ?>
        </h2>
        <p class="about__description">
          <?= esc_html($item_1['desc']); ?>
        </p>
      </div>
    </div>
    <div class="about__item">
      <div class="about__content" data-aos="fade-up" data-aos-duration="1000">
        <h3 class="about__subtitle">
          <?= esc_html($item_2['title']); ?>
        </h3>
        <p class="about__description">
          <?= esc_html($item_2['desc']); ?>
        </p>
        <p class="about__description">
          <?= esc_html($item_2['desc_2']); ?>
        </p>
      </div>
      <picture data-aos="fade-left">
        <?= wp_get_attachment_image($image_about_shadow_2, 'full', false, array('class' => 'about__image__shadow')) ?>
        <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($item_2['image']['mobile'], 'full')) . '" />' ?>
        <?= wp_get_attachment_image($item_2['image']['desktop'], 'full', false, array('class' => 'about__image__item')) ?>
      </picture>
    </div>
    <div class="about__item">
      <picture data-aos="fade-right">
        <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($item_3['image']['mobile'], 'full')) . '" />' ?>
        <?= wp_get_attachment_image($item_3['image']['desktop'], 'full', false, array('class' => 'about__image__item')) ?>
      </picture>
      <div class="about__content" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="about__title">
          <?= esc_html($item_3['title']) ?>
        </h2>
        <h3 class="about__subtitle"><?= esc_html($item_3['subtitle']); ?></h3>
        <p class="about__description">
          <?= esc_html($item_3['desc']); ?>
        </p>
      </div>
    </div>
    <div class="about__book">
      <div class="about__book__image">
        <picture data-aos="fade-right">
          <?= wp_get_attachment_image($image_about_shadow_2, 'full', false, array('class' => 'about__book__shadow')) ?>
          <?= '<source media="(max-width: 639px)" srcset="' . esc_url(wp_get_attachment_image_url($item_4['image']['mobile'], 'full')) . '" />' ?>
          <?= wp_get_attachment_image($item_4['image']['desktop'], 'full', false, array('class' => 'about__book__item')) ?>
        </picture>
      </div>
      <div class="about__book__content" data-aos="fade-up" data-aos-duration="1000">
        <h3 class="about__subtitle"><?= esc_html($item_4['title']); ?></h3>
        <p class="about__description"><?= esc_html($item_4['desc']) ?></p>
        <a href="<?= esc_attr($item_4['link']['url']); ?>"
          class="highlights-content__contact-link highlights-content__contact-link--pc compound-avian-button compound-avian-button--lg">
          <div class="compound-avian-button__content">
            <span class="highlights-content__contact-link__text compound-avian-button__content-text">
              <?= esc_html($item_4['link']['title']); ?>
            </span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>