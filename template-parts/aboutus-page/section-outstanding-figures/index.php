<?php
$figures = get_field("about_outstanding-figures");
$title = isset($figures['title']) ? $figures['title'] : '';
$rating = isset($figures['rating']) ? $figures['rating'] : [];
$specs = $figures['specs'];
$number_of_tours = $specs['number_of_tours'];
$coverage_area = $specs['coverage_area'];
$partner = $specs['partner'];
$customer = $specs['customer'];

$icon_stars = 1330;
$icon_star = 2302;
$image_outstanding_figures_1 = 2266;

function formatNumberVN($number) {
    return $number >= 1000
        ? number_format($number, 0, ',', '.')
        : $number;
}

$roundedRating = round($rating['rating_point']);
?>

<section class="outstanding__figures">
  <div class="container">
    <div class="outstanding__figures__container">
      <div class="outstanding__figures__content">
        <div class="outstanding__figures__title__wrapper">
          <h3 class="outstanding__figures__title__text">
            <?= esc_html($title); ?>
          </h3>
          <div class="outstanding__figures__title__items">
            <div class="outstanding__figures__title__content">
              <div class="outstanding__figures__stars">
                  <?php for($i = 0; $i < $roundedRating; $i++): ?>
                    <?= wp_get_attachment_image($icon_star, 'full', false, array('class' => 'outstanding__figures__icon_star')) ?>
                  <?php endfor; ?>
              </div>
              
              <a href="<?= esc_attr($rating['rating_link']['url']); ?>"
                class="outstanding__figures__title__content__text">rating of guests
                <?= esc_html($rating['rating_point']) ?></a>
            </div>
            <div class="outstanding__figures__image__items">
              <?= wp_get_attachment_image($rating['rating_avatar_1'], 'full', false, array('class' => 'outstanding__figures__image__item')) ?>
              <?= wp_get_attachment_image($rating['rating_avatar_2'], 'full', false, array('class' => 'outstanding__figures__image__item')) ?>
              <?= wp_get_attachment_image($rating['rating_avatar_3'], 'full', false, array('class' => 'outstanding__figures__image__item')) ?>
            </div>
          </div>
        </div>
        <div class="outstanding__figures__content__items">
          <div class="outstanding__figures__content__item">
            <span class="outstanding__figures__content__item__title">NUMBER OF TOURS</span>
            <span class="outstanding__figures__content__item__value__backup"><?= esc_html($number_of_tours) ?>+</span>
            <span class="outstanding__figures__content__item__value"><?= esc_html($number_of_tours) ?>+</span>
          </div>
          <div class="outstanding__figures__content__item">
            <span class="outstanding__figures__content__item__title">COVERAGE AREA</span>
            <span class="outstanding__figures__content__item__value__backup"><?= esc_html($coverage_area) ?>+</span>
            <span class="outstanding__figures__content__item__value"><?= esc_html($coverage_area) ?>+</span>
          </div>
          <div class="outstanding__figures__content__item">
            <span class="outstanding__figures__content__item__title">PARTNER</span>
            <span class="outstanding__figures__content__item__value__backup"><?= esc_html($partner) ?>+</span>
            <span class="outstanding__figures__content__item__value"><?= esc_html($partner) ?>+</span>
          </div>
          <div class="outstanding__figures__content__item">
            <span class="outstanding__figures__content__item__title">CUSTOMER</span>
            <span class="outstanding__figures__content__item__value__backup"><?= formatNumberVN($customer) ?>+</span>
            <span class="outstanding__figures__content__item__value"><?= formatNumberVN($customer) ?>+</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>