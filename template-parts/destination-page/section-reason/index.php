<?php
$term = get_queried_object();
$reason = get_field('destination_reason', $term);
$title = isset($reason['title']) ? $reason['title'] : '';
$items = isset($reason['reason_items']) ? $reason['reason_items'] : [];

$bg_deco_id = 1361;
$bg_deco_mb_id = 1362;
$phone_icon_id = 1359;

$total = count($items);
?>


<section id="why-avian-odyssey" class="destination-reason">
    <div class="destination-reason_img-wrapper">
        <?= wp_get_attachment_image($bg_deco_id, 'full', false, array('class' => 'destination-reason_img')) ?>
        <?= wp_get_attachment_image($bg_deco_mb_id, 'full', false, array('class' => 'destination-reason_img-mb')) ?>
    </div>
    <div class="destination-reason_container">
        <h2 class="destination-reason_title"><?= $title ?></h2>
        <div class="destination-reason_cards">
            <?php foreach($items as $index => $item): ?>
                <?php 
                $is_last = ($index === $total - 1);
                $icon = isset($item['icon']) ? $item['icon'] : '';
                $title = isset($item['title']) ? $item['title'] : '';
                $descs = isset($item['description_items']) ? $item['description_items'] : [];
                ?>
                <div class="destination-reason_card">
                <div class="destination-reason_card-icon-wrapper">
                    <?= wp_get_attachment_image($icon, 'full', false, array('class' => 'destination-reason_card-icon')) ?>
                </div>
                <div class="destination-reason_card-content">
                    <h3 class="destination-reason_card-title"><?= $title ?></h3>
                    <ul class="destination-reason_card-list">
                        <?php foreach($descs as $desc): ?>
                            <li class="destination-reason_card-item"><?= $desc['description_item'] ?></li>
                        <?php endforeach; ?>
                        <?php if($is_last): ?>
                            <li class="destination-reason_card-item">
                                <div class="destination-reason_card-contact">
                                    <a
                                        href="/contact"
                                        class="destination-reason_card-link compound-avian-button compound-avian-button--lg"
                                    >
                                        <div class="compound-avian-button__content">
                                            <span class="compound-avian-button__content-text">
                                                Answering your queries
                                            </span>
                                        </div>
                                    </a>
                                    <div class="destination-reason_card-item-wrapper">
                                        <span class="destination-reason_card-phone-label"> Call us today until 8pm </span>
                                        <a href="tel:+84 888 789 346" class="destination-reason_card-phone-link">
                                            <?= wp_get_attachment_image($phone_icon_id, 'full', false, array('class' => 'destination-reason_card-phone')) ?>
                                            <span>+84 888 789 346</span>
                                        </a>
                                    </div>
                                </div>
                             </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="destination-reason_cards-mb swiper">
            <div class="swiper-wrapper">
                <?php foreach($items as $index => $item): ?>
                <?php 
                $is_last = ($index === $total - 1);
                $icon = isset($item['icon']) ? $item['icon'] : '';
                $title = isset($item['title']) ? $item['title'] : '';
                $descs = isset($item['description_items']) ? $item['description_items'] : [];
                ?>
                    <div class="swiper-slide">
                        <div class="destination-reason_card">
                            <div class="destination-reason_card-icon-wrapper">
                                <?= wp_get_attachment_image($icon, 'full', false, array('class' => 'destination-reason_card-icon')) ?>
                            </div>
                            <div class="destination-reason_card-content">
                                <h3 class="destination-reason_card-title"><?= $title ?></h3>
                                <ul class="destination-reason_card-list">
                                    <?php foreach($descs as $desc): ?>
                                        <li class="destination-reason_card-item"><?= $desc['description_item'] ?></li>
                                    <?php endforeach; ?>
                                    <?php if($is_last): ?>
                                        <li class="destination-reason_card-item">
                                            <div class="destination-reason_card-contact">
                                                <a
                                                    href="/contact"
                                                    class="destination-reason_card-link compound-avian-button compound-avian-button--lg"
                                                >
                                                    <div class="compound-avian-button__content">
                                                        <span class="compound-avian-button__content-text">
                                                            Answering your queries
                                                        </span>
                                                    </div>
                                                </a>
                                                <div class="destination-reason_card-item-wrapper">
                                                    <span class="destination-reason_card-phone-label"> Call us today until 8pm </span>
                                                    <a href="tel:+84 888 789 346" class="destination-reason_card-phone-link">
                                                        <?= wp_get_attachment_image($phone_icon_id, 'full', false, array('class' => 'destination-reason_card-phone')) ?>
                                                        <span>+84 888 789 346</span>
                                                    </a>
                                                </div>
                                            </div>
                                         </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="destination-reason_pagination swiper-pagination"></div>
</section>
