<?php
$image_item_1_id = 147;
$image_item_2_id = 146;
$image_item_3_id = 145;
$image_item_4_id = 144;

$section_highlight = get_field('highlight');
$section_highlight_items = $section_highlight['items'];
?>
<?php if (!empty($section_highlight_items)) : ?>
<section class="highlights">
  <div class="highlights__container">
    <div class="highlights__list">
      <?php foreach ($section_highlight_items as $highlight_item) : ?>
      <?php 
      $highlight_item_image = $highlight_item['image'];
      $highlight_item_title = $highlight_item['title'];
      $highlight_item_subtitle = $highlight_item['subtitle'];
      ?>
      <div class="highlights__list-item">
        <?php if(!empty($highlight_item_image)): ?>
          <?= wp_get_attachment_image($highlight_item_image, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'highlights__list-item-image')) ?>
        <?php endif; ?>
        <div class="highlights__content">
          <h2 class="highlights__content-title">
            <?= $highlight_item_title ?>
          </h2>
          <p class="highlights__content-description">
            <?= $highlight_item_subtitle ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>