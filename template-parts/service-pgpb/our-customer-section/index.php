<?php
$data = get_field('our_customer');
$title = $data['title'] ?? '';
$subtitle = $data['subtitle'] ?? '';
$list = $data['customer_list'] ?? [];
?>

<section class="customer-section">
      <div class="customer-header">
            <span class="customer-subtitle"><?php echo esc_html($subtitle); ?></span>
            <h2 class="customer-title"><?php echo esc_html($title); ?></h2>
      </div>

      <div class="customer-grid">
            <?php foreach ($list as $item) : ?>
                  <div class="customer-item">
                        <div class="customer-thumb">
                              <?php
                              if (!empty($item['thumbnail'])) {
                                    echo wp_get_attachment_image($item['thumbnail']['ID'], 'full');
                              }
                              ?>
                              <div class="customer-overlay"></div>
                        </div>
                        <div class="customer-item-name">
                              <?php echo esc_html($item['title']); ?>
                        </div>
                  </div>
            <?php endforeach; ?>
      </div>
</section>