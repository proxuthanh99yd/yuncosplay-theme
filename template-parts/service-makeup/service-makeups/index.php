<?php
      $service_data = get_field('service_data');
      $title = $service_data['title'];
      $feature_list = $service_data['feature_list'];
      $subtitle = $service_data['subtitle'];
      $services_list = $service_data['services'];
      $card_button = $service_data['button'] ?? null;
      $card_btn_text = !empty($card_button['title']) ? $card_button['title'] : 'Liên hệ tư vấn';
      $card_btn_href = !empty($card_button['url']) ? $card_button['url'] : okhub_page_url('lien-he');
      $card_btn_target = !empty($card_button['target']) ? $card_button['target'] : '_self';
?>

<section class="services-section makeup-service">
    <div class="services-bg">
        <div class="bg-grid-lines">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>

    <div class="services-container">
        <?php if (isset($feature_list)) : ?>
        <div class="features-bar">
            <?php foreach ($feature_list as $feat) : ?>
            <div class="feature-item">
                <?= wp_get_attachment_image($feat['icon']['ID'], 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'icon')) ?>
                <div class="feature-text">
                    <h3><?= $feat['title'] ?></h3>
                    <p><?= $feat['subtitle'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="services-header">
            <span class="sub-title"><?= $title ?></span>
            <h2 class="main-title"><?= $subtitle ?></h2>
        </div>


        <?php if (isset($services_list)) : ?>
        <div class="services-grid">
            <?php foreach ($services_list as $service) : ?>
            <?php
                $title_service = $service["title"] ?? '';
                $thumbnail = $service["image"] ?? null;
            ?>
            <div class="service-card">
                <?php
                    echo wp_get_attachment_image($thumbnail, 'full', false, [
                            'class' => 'post-card__image zoom-image',
                            'loading' => 'lazy'
                    ]);
                ?>
                <div class="card-overlay">
                    <div class="card-text-window">
                        <div class="card-text-shift">
                            <h4 class="card-title">
                                <span>
                                    <?= $title_service ?>
                                </span>
                            </h4>

                            <a href="<?= esc_url($card_btn_href) ?>" class="card-link"<?= $card_btn_target !== '_self' ? ' target="' . esc_attr($card_btn_target) . '"' : '' ?>>
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="10" viewBox="0 0 11 10"
                                    fill="none">
                                    <path
                                        d="M10.3809 4.98145L5.88867 9.47168L4.90332 9.44141L6.62305 7.72266C7.21691 7.12965 7.75996 6.59838 8.25293 6.12988L9.05371 5.36914L7.94922 5.36328L0.464844 5.32812L0.499023 4.55957L8.01855 4.5957L9.1416 4.60156L8.32617 3.8291C8.08531 3.6009 7.8313 3.35642 7.56445 3.0957L6.72461 2.26465L4.93945 0.479492L5.84668 0.450195L10.3809 4.98145Z"
                                        fill="#F26C59" stroke="#F26C59" stroke-width="0.8888" />
                                </svg> <?= esc_html($card_btn_text) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>