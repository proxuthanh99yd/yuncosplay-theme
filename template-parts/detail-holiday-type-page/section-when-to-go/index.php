<?php
$term = get_queried_object();
$when = get_field('holiday_when-to-go', $term);
$title = isset($when['title']) ? $when['title'] : '';
$desc = isset($when['desc']) ? $when['desc'] : '';


$destinations = get_terms([
    'taxonomy'   => 'destination',
    'hide_empty' => false,
    'parent' => 0
]);


$destinations_data = [];

foreach ($destinations as $item) {
  $item_id = $item->term_id;
  $best_time_to_visit = get_field("destination_best-time-to-visit", 'destination_' . $item_id);
  $destinations_data[] = [
      'id'        => $item_id,
      'name'      => $item->name,
      'desc'      => $item->description,
      'slug'      => $item->slug,
      'best_time_to_visit' => $best_time_to_visit,
  ];
}

$default_destination = $destinations_data[0]; // default destination 
$default_destination_data = isset($default_destination['best_time_to_visit']) ? $default_destination['best_time_to_visit'] : [];
$best_time_to_visit_data = $default_destination_data[0]; // first month
$best_time_to_visit_items = $best_time_to_visit_data['items'];

$search_icon = 1195;
$arrow_left = 1210;
$close_icon = 1191;
$bg_id = 2335;
?>

<section id="when-to-go" class='ht-when'>
  <?= wp_get_attachment_image($bg_id, "full", false, ["class" => "ht-when_bg"]) ?>
  <div class="ht-when_header">
    <div class="ht-when_header-wrapper">
      <h2 class="ht-when_title">
        <?= esc_html($title); ?>
      </h2>
      <p class="ht-when_desc">
        <?= esc_html($desc); ?>
      </p>
    </div>
    <div class="ht-when_tabs">
      <?php foreach($default_destination_data as $index => $data): ?>
      <?php $month = isset($data['month']) ? $data['month'] : '' ?>
      <button type="button" class="ht-when_tab <?= $index === 0 ? 'active' : '' ?>"
        data-month="<?= esc_attr($month) ?>">
        <?= esc_html($month) ?>
      </button>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="ht-when_body">
    <div class="ht-when_item-header">
      <span>
        Country
      </span>
      <span>
        Beach & Island area
      </span>
      <span>
        Daily max temperature (°C)
      </span>
      <span>
        Monthly rainfall (mm)
      </span>
      <span>
        Do or don’t?
      </span>
    </div>
    <div class="ht-when_line"></div>

    <div class="ht-when_item-body">
      <div class="swiper">
        <div class="swiper-wrapper">
          <?php foreach($destinations_data as $destination): ?>
          <?php 
            $best_time_to_visit = $destination['best_time_to_visit'];
            $first_best_time_to_visit = $best_time_to_visit[0]; // first month
            $items = $first_best_time_to_visit['items'];
          ?>
          <div class="swiper-slide ht-when_item" data-id="<?= esc_attr($destination['id']); ?>">
            <span class="ht-when_item-country">
              <?= esc_html($destination['name']); ?>
            </span>
            <div class="ht-when_item-line"></div>
            <div class="ht-when_items">
              <?php foreach($items as $item): ?>
              <?php
                  $temperature = $item['daily_max_temperature'];
                  $rainfall = $item['monthly_rainfall'];
                  $rainfallPercent = $rainfall / 1000 * 100; // In acf max range rainfall is 1000
              ?>
              <div class="ht-when_item-row">
                <div class="ht-when_item-wrapper">
                  <span class="ht-when_item-area">
                    <?= esc_html($item['beach_island_area']); ?>
                  </span>
                </div>
                <div class="ht-when_ranges">
                  <div class="ht-when_range range-color">
                    <div class="ht-when_range-thumb" style="width: <?= esc_attr($temperature); ?>%"></div>
                    <div class="ht-when_range-value" style="left: <?= esc_attr($temperature); ?>%">
                      <?= esc_html($temperature); ?>
                    </div>
                  </div>
                  <div class="ht-when_range">
                    <div class="ht-when_range-thumb" style="width: <?= esc_attr($rainfallPercent); ?>%"></div>
                    <div class="ht-when_range-value" style="left: <?= esc_attr($rainfallPercent); ?>%">
                      <?= esc_html($rainfall); ?>
                    </div>
                  </div>
                </div>
                <div class="ht-when_circle lg <?= esc_attr(sanitize_title($item['do_or_dont'])); ?>"></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
    <div class="ht-when_line"></div>
  </div>

  <div class="ht-when_body-mb">
    <div class="ht-when_item-header-mb">
      <custom-drawer data-direction="bottom">
        <custom-drawer-trigger>
            <button type="button" class="ht-when_btn-drawer">
                  <span>
                    <?= esc_html($default_destination['name']); ?>
                  </span>
                  <?= wp_get_attachment_image($arrow_left, "full", false, [
                  "class" => "ht-when_icon"
                ]) ?>
            </button>
        </custom-drawer-trigger>
        <custom-drawer-content data-touch-bar="false" data-drag-content="false">
          <custom-drawer-close>
            <button type="button" class="ht-when_drawer-btn-close">
              <?= wp_get_attachment_image($close_icon, "full", false, [
                "class" => "ht-when_close-icon"
                ]) ?>
            </button>
          </custom-drawer-close>
          <div class="ht-when_drawer-header">
            <p>
              Select a destination
            </p>
            <div class="ht-when_drawer-search">
              <input type="text" class="ht-when_drawer-search-input" placeholder="Enter search content" />
              <?= wp_get_attachment_image($search_icon, "full", false, [
                "class" => "ht-when_search-icon"
                ]) ?>
            </div>

          </div>
          <div class="ht-when_drawer-body">
            <?php foreach($destinations_data as $destination): ?>
            <?php 
                $name = isset($destination['name']) ? $destination['name'] : '';
                $id = $destination['id'];
                $isActive = $default_destination['id'] === $id;
            ?>
            <custom-drawer-close>
                <div class="ht-when_drawer-item <?= $isActive ? "active" : "" ?>" data-id="<?= esc_attr($id); ?>">
                   <?= esc_html($name); ?>
                </div>
            </custom-drawer-close>
            <?php endforeach; ?>
          </div>
        </custom-drawer-content>
      </custom-drawer>

      <div class="ht-when_info-wrapper">
        <div class="ht-when_info">
          <p>
            Daily max temperature (°C)
          </p>
          <div class="ht-when_range-circle color"></div>
        </div>
        <div class="ht-when_info">
          <p>
            Monthly rainfall (mm )
          </p>
          <div class="ht-when_range-circle"></div>
        </div>
      </div>
    </div>
    <div class="ht-when_line"></div>
    <div class="ht-when_items-mb">
      <?php foreach($best_time_to_visit_items as $item): ?>
      <?php
        $temperature = $item['daily_max_temperature'];
        $rainfall = $item['monthly_rainfall'];
      ?>
      <div class="ht-when_item-mb">
        <div class="ht-when_item-area">
          <div class="ht-when_circle <?= esc_attr(sanitize_title($item['do_or_dont'])); ?>"></div>
          <span>
            <?= esc_html($item['beach_island_area']); ?>
          </span>
        </div>
        <div class="ht-when_item-specifications">
          <div class="ht-when_range-circle lg color">
            <?= esc_html($temperature); ?>
          </div>
          <div class="ht-when_range-circle lg">
            <?= esc_html($rainfall); ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="ht-when_line"></div>
  </div>

  <div class="ht-when_footer">
    <div class="ht-when_footer-item">
      <div class="ht-when_circle best-month"></div>
      <span>
        Best month
      </span>
    </div>
    <div class="ht-when_footer-item">
      <div class="ht-when_circle acceptable"></div>
      <span>
        Acceptable
      </span>
    </div>
    <div class="ht-when_footer-item">
      <div class="ht-when_circle avoid"></div>
      <span>
        Avoid
      </span>
    </div>
  </div>
</section>

<script>
const whenToGoDatas = <?= wp_json_encode($destinations_data) ?>;
</script>