<?php 
$section_highlights = get_field('section_highlights');
$title = $section_highlights['title'];
$description = $section_highlights['description'];
$contact_link = $section_highlights['contact_link'];
$highlight_items = $section_highlights['highlight_items'];

if($contact_link) {
    $contact_link_url = $contact_link['url'];
    $contact_link_title = $contact_link['title'] ?? '';
    $contact_link_target = $contact_link['target'] ? $contact_link['target'] : '_self';
}
// Ảnh background card
$background_id_card_1_pc = 1088;
$background_id_card_2_pc = 1089;
$background_id_card_3_pc = 1090;
$background_id_card_1_mb = 1096;
$background_id_card_2_mb = 1097;
$background_id_card_3_mb = 1099;

$background_card_items = [
    ['background_pc' => $background_id_card_1_pc, 'background_mb' => $background_id_card_1_mb],
    ['background_pc' => $background_id_card_2_pc, 'background_mb' => $background_id_card_2_mb],
    ['background_pc' => $background_id_card_3_pc, 'background_mb' => $background_id_card_3_mb],
];

// Ảnh decor card
$image_decor_id_1 = 1092;
$image_decor_id_2 = 1093;
// Ảnh background
$image_background_farmer_id = 1094;
$image_background_flower_id = 1971;

if(!empty($highlight_items)) {
    // Gán background vào mảng gốc trước
    foreach($highlight_items as $index => &$highlight_item) {
        $highlight_item['background_pc'] = $background_card_items[$index]['background_pc'];
        $highlight_item['background_mb'] = $background_card_items[$index]['background_mb'];
    }
    unset($highlight_item); // Hủy tham chiếu sau khi dùng xong

    // Sau đó mới gán vào các biến riêng lẻ
    $highlight_item_1 = $highlight_items[0];
    $highlight_item_2 = $highlight_items[1];
    $highlight_item_3 = $highlight_items[2];
}
?>

<section id="highlights" class="highlights">
    <?= wp_get_attachment_image($image_background_farmer_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'highlights-image__farmer')) ?>
    <?= wp_get_attachment_image($image_background_flower_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'highlights-image__flower')) ?>
    
    <div class="highlights-container">

        <h2 class="highlight-title">
            <?= $title ?>
        </h2>

        <div class="highlights-content">
            <p class="highlights-content__description"><?= $description ?></p>

            <?php if(!empty($contact_link) && !empty($contact_link_url)) : ?>
                <a class="highlights-content__contact-link highlights-content__contact-link--pc compound-avian-button compound-avian-button--lg" href="<?= $contact_link_url ?>" target="<?= $contact_link_target ?>">
                    <div class="compound-avian-button__content">
                        <span class="highlights-content__contact-link__text compound-avian-button__content-text">
                            <?= $contact_link_title ?>
                        </span>
                    </div>
                </a>
            <?php endif; ?>
        </div>

        <div class="highlights-column__list highlights-column__list--pc">
            <div class="highlight-column__item">
                <div data-aos="fade-right" class="highlight-card__item">
                    <div class="highlight-card__item-image">
                        <?= wp_get_attachment_image($highlight_item_1['background_pc'], 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => '')) ?>
                    </div>
                    <div class="highlight-card__item-content">
                        <h3 class="highlight-card__item-content__title">
                            <?= $highlight_item_1['title'] ?? '' ?>
                        </h3>
                        <p class="highlight-card__item-content__description">
                            <?= $highlight_item_1['content'] ?? '' ?>
                        </p>
                    </div>
                </div>
                <div data-aos="fade-left" class="highlight-card__item">
                    <div class="highlight-card__item-image">
                        <?= wp_get_attachment_image($highlight_item_2['background_pc'], 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => '')) ?>
                    </div>
                    <div class="highlight-card__item-content">
                        <h3 class="highlight-card__item-content__title">
                            <?= $highlight_item_2['title'] ?>
                        </h3>
                        <p class="highlight-card__item-content__description">
                            <?= $highlight_item_2['content'] ?>
                        </p>
                    </div>
                </div>
                <?= wp_get_attachment_image($image_decor_id_1, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'highlight-column__image-decor')) ?>
            </div>
            <div class="highlight-column__item">
                <div class="highlight-column__image-decor"></div>
            </div>
            <div class="highlight-column__item">
                <div data-aos="fade-right" class="highlight-column__image-decor">
                    <?= wp_get_attachment_image($image_decor_id_2, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => '')) ?>
                </div>
                <div data-aos="fade-left" class="highlight-card__item">
                    <div class="highlight-card__item-image">
                        <?= wp_get_attachment_image($highlight_item_3['background_pc'], 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => '')) ?>
                    </div>
                    <div class="highlight-card__item-content">
                        <h3 class="highlight-card__item-content__title">
                            <?= $highlight_item_3['title'] ?? '' ?>
                        </h3>
                        <p class="highlight-card__item-content__description">
                            <?= $highlight_item_3['content'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="highlights-column__list highlights-column__list--mb">
            <?php if(!empty($highlight_items)): ?>
                <?php foreach($highlight_items as $highlight_item) : ?>
                    <div class="highlight-column__item">
                        <div class="highlight-card__item">
                            <div class="highlight-card__item-image">
                                <?= wp_get_attachment_image($highlight_item['background_mb'], 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => '')) ?>
                            </div>
                             <div class="highlight-card__item-content">
                                <h3 class="highlight-card__item-content__title">
                                    <?= $highlight_item['title'] ?? '' ?>
                                </h3>
                                <p class="highlight-card__item-content__description">
                                    <?= $highlight_item['content'] ?? '' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="highlights-content__contact-link-wrapper--mb">
            <?php if(!empty($contact_link) && !empty($contact_link_url)) : ?>
            <a class="highlights-content__contact-link highlights-content__contact-link--mb compound-avian-button compound-avian-button--lg" href="<?= $contact_link_url ?>" target="<?= $contact_link_target ?>">
                <div class="compound-avian-button__content">
                    <span class="highlights-content__contact-link__text compound-avian-button__content-text">
                        <?= $contact_link_title ?>
                    </span>
                </div>
            </a>
        <?php endif; ?>
        </div>
    </div>
</section>