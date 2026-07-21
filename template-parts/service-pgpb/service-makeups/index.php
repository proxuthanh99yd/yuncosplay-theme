<?php
$service_data = get_field('service_data');
$title = $service_data['title'];
$feature_list = $service_data['feature_list'];
$subtitle = $service_data['subtitle'];
$services_list = $service_data['services'];
$card_button = $service_data['button'] ?? null;
$card_btn_text = !empty($card_button['title']) ? $card_button['title'] : 'Liên hệ ngay';
$card_btn_href = !empty($card_button['url']) ? $card_button['url'] : okhub_page_url('lien-he');
$card_btn_target = !empty($card_button['target']) ? $card_button['target'] : '_self';
?>

<section class="pgbg-service services-section ">
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
                  $title_service = $service['title'];
                  $thumbnail = $service['image'];
                  $offer_items = $service['offer_items'];
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
                            <div class="card-title">
                                <h4>
                                    <?= $title_service ?>
                                </h4>
                                <?php if (isset($offer_items)) : ?>
                                <ul>
                                    <?php foreach ($offer_items as $item) : ?>
                                    <li>
                                        <!--<svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">-->
                                        <!--      <path d="M6.26918 0.199326L7.17133 3.15802C7.29149 3.55211 7.50672 3.91061 7.79806 4.20195C8.08939 4.49328 8.44789 4.70851 8.84198 4.82867L11.8007 5.73082C12.0664 5.81192 12.0664 6.18808 11.8007 6.26918L8.84198 7.17133C8.44789 7.29149 8.08939 7.50672 7.79806 7.79806C7.50672 8.08939 7.29149 8.44789 7.17133 8.84198L6.26905 11.8007C6.18796 12.0664 5.81179 12.0664 5.73069 11.8007L4.82855 8.84198C4.70838 8.44789 4.49315 8.08939 4.20182 7.79806C3.91049 7.50672 3.55199 7.29149 3.1579 7.17133L0.199326 6.26905C-0.0664421 6.18796 -0.0664421 5.81179 0.199326 5.73069L3.15802 4.82855C3.55211 4.70838 3.91061 4.49315 4.20195 4.20182C4.49328 3.91049 4.70851 3.55199 4.82867 3.1579L5.73095 0.199326C5.81204 -0.0664421 6.18808 -0.0664421 6.26918 0.199326Z" fill="#CB5140" />-->
                                        <!--</svg>-->
                                        <svg width="14" height="26" viewBox="0 0 14 26" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.31404 6.92005L8.36655 10.3719C8.50674 10.8316 8.75784 11.2499 9.09773 11.5898C9.43762 11.9297 9.85587 12.1808 10.3156 12.321L13.7675 13.3735C14.0775 13.4681 14.0775 13.9069 13.7675 14.0015L10.3156 15.054C9.85587 15.1942 9.43762 15.4453 9.09773 15.7852C8.75784 16.1251 8.50674 16.5434 8.36655 17.0031L7.3139 20.455C7.21928 20.765 6.78042 20.765 6.68581 20.455L5.63331 17.0031C5.49311 16.5434 5.24201 16.1251 4.90212 15.7852C4.56223 15.4453 4.14399 15.1942 3.68421 15.054L0.232547 14.0014C-0.0775158 13.9068 -0.0775158 13.4679 0.232547 13.3733L3.68436 12.3208C4.14413 12.1806 4.56238 11.9295 4.90227 11.5896C5.24216 11.2497 5.49326 10.8315 5.63345 10.3717L6.68611 6.92005C6.78072 6.60998 7.21943 6.60998 7.31404 6.92005Z"
                                                fill="#CB5140" />
                                        </svg>
                                        <span>
                                            <?= $item['offer_item'] ?>
                                        </span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>



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