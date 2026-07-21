<?php
      $hero_data = get_field('hero_data');
      $title = $hero_data['title'];
      $subtitle = $hero_data['subtitle'];
      $banner = $hero_data['banner'];
      $button = $hero_data['button'] ?? null;
      $btn_text = !empty($button['title']) ? $button['title'] : 'Đặt lịch makeup ngay';
      $btn_href = !empty($button['url']) ? $button['url'] : okhub_page_url('lien-he');
      $btn_target = !empty($button['target']) ? $button['target'] : '_self';
?>

<section class="hero">
      <div class="hero-background">
            <?= wp_get_attachment_image($banner['desktop']['ID'], 'full', false, okhub_image_attrs(array('class' => 'hero-img desktop-only'), !IS_MOBILE ? 'lcp' : 'lazy')) ?>
            <?= wp_get_attachment_image($banner['mobile']['ID'], 'full', false, okhub_image_attrs(array('class' => 'hero-img mobile-only'), IS_MOBILE ? 'lcp' : 'lazy')) ?>
            <div class="overlay-top"></div>
            <div class="overlay-bottom"></div>
      </div>

      <div class="hero-container">
            <div class="hero-content">
                  <div class="hero-text-left">
                        <h1 class="hero-title"><?= $title ?></h1>
                  </div>

                  <div class="hero-text-right">
                        <p class="hero-description">
                              <?= $subtitle ?>
                        </p>

                        <a href="<?= esc_url($btn_href) ?>" class=""<?= $btn_target !== '_self' ? ' target="' . esc_attr($btn_target) . '"' : '' ?>>
                              <?php get_template_part('template-parts/components/animated-button/index', null, ['text' => $btn_text]); ?>
                        </a>
                  </div>
            </div>
      </div>
</section>