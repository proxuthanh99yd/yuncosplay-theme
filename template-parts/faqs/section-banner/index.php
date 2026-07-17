<?php
$banner = get_field('banner', get_the_ID());

$image_desktop = $banner['image_desktop'] ?? null;
$image_mobile  = $banner['image_mobile'] ?? null;
$title         = $banner['title'] ?? '';
$desc          = $banner['desc'] ?? '';

$image_desktop_id = is_array($image_desktop) ? ($image_desktop['ID'] ?? null) : $image_desktop;
$image_mobile_id  = is_array($image_mobile) ? ($image_mobile['ID'] ?? null) : $image_mobile;

$image_desktop_alt = is_array($image_desktop)
  ? ($image_desktop['alt'] ?: ($image_desktop['title'] ?? 'FAQ Background Desktop'))
  : 'FAQ Background Desktop';

$image_mobile_alt = is_array($image_mobile)
  ? ($image_mobile['alt'] ?: ($image_mobile['title'] ?? 'FAQ Background Mobile'))
  : 'FAQ Background Mobile';
?>

<?php if (!empty($banner)) : ?>
  <section class="faq-hero">
    <div class="faq-hero__media">

      <?php if (!empty($image_desktop_id)) : ?>
        <?= wp_get_attachment_image($image_desktop_id, 'full', false, okhub_image_attrs([
          'class' => 'faq-hero__bg faq-hero__bg--desktop',
          'alt' => esc_attr($image_desktop_alt),
        ], !IS_MOBILE ? 'lcp' : 'lazy')); ?>
      <?php else : ?>
        <div class="faq-hero__debug">Không có ảnh desktop trong ACF: image_desktop</div>
      <?php endif; ?>

      <?php if (!empty($image_mobile_id)) : ?>
        <?= wp_get_attachment_image($image_mobile_id, 'full', false, okhub_image_attrs([
          'class' => 'faq-hero__bg faq-hero__bg--mobile',
          'alt' => esc_attr($image_mobile_alt),
        ], IS_MOBILE ? 'lcp' : 'lazy')); ?>
      <?php else : ?>
        <div class="faq-hero__debug">Không có ảnh mobile trong ACF: image_mobile</div>
      <?php endif; ?>

      <div class="faq-hero__overlay"></div>
    </div>

    <div class="faq-hero__container">
      <div class="faq-hero__content">
        <div class="faq-hero__text">

          <?php if (!empty($title)) : ?>
            <div class="faq-hero__title">
              <?= wp_kses_post($title); ?>
            </div>
          <?php else : ?>
            <div class="faq-hero__debug">Không có title trong ACF</div>
          <?php endif; ?>

          <?php if (!empty($desc)) : ?>
            <p class="faq-hero__desc">
              <?= esc_html($desc); ?>
            </p>
          <?php else : ?>
            <div class="faq-hero__debug">Không có desc trong ACF</div>
          <?php endif; ?>

        </div>

        <form class="faq-hero__search" action="#faq-section" method="get">
          <input
            class="faq-hero__input"
            type="search"
            name="s"
            placeholder="Tìm câu hỏi của bạn">

          <button class="faq-hero__button" type="submit" aria-label="Tìm kiếm">
            <svg class="faq-hero__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26" fill="none">
              <path d="M12.4577 22.7493C18.1416 22.7493 22.7493 18.1416 22.7493 12.4577C22.7493 6.77375 18.1416 2.16602 12.4577 2.16602C6.77375 2.16602 2.16602 6.77375 2.16602 12.4577C2.16602 18.1416 6.77375 22.7493 12.4577 22.7493Z" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M23.8327 23.8327L21.666 21.666" stroke="#F6F3EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </form>
      </div>
    </div>
  </section>
<?php else : ?>
  <div class="faq-hero__debug">Không có dữ liệu ACF field: banner</div>
<?php endif; ?>