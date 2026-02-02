<?php
$freedom = get_field("about_freedom");
$title = isset($freedom['title']) ? $freedom['title'] : '';
$desc = isset($freedom['desc']) ? $freedom['desc'] : '';
$our_values = isset($freedom['our_values']) ? $freedom['our_values'] : [];
$items = $our_values['items'];

$image_freedom_1_id = 2275;
$image_freedom_2_id = 2276;
$image_freedom_3_id = 2277;
$arrow_right_id = 1210;
?>
<section id="freedom" class="section__freedom">
  <?= wp_get_attachment_image($image_freedom_3_id, 'full', false, array('class' => 'section__freedom__image__third')) ?>
  <div class="container section__freedom__container">
    <h2 class="section__freedom__title"><?= esc_html($title); ?></h2>
    <p class="section__freedom__description"><?= esc_html($desc); ?></p>
  </div>
  <div class="section__freedom__values container">
    <h2 class="section__freedom__values__title"><?= esc_html($our_values['title']); ?></h2>
    <div class="section__freedom__values__container">
      <?php foreach($items as $item): ?>
      <div class="section__freedom__values__item">
        <h3 class="values__item__title"><?= esc_html($item['title']) ?></h3>
        <p class="values__item__description"><?= esc_html($item['desc']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?= wp_get_attachment_image($image_freedom_1_id, 'full', false, array('class' => 'section__freedom__image__first')) ?>
  <?= wp_get_attachment_image($image_freedom_2_id, 'full', false, array('class' => 'section__freedom__image__second')) ?>

</section>