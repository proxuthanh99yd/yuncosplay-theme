<?php
$term = get_queried_object();
$cultures = get_field("destination_cultures", $term);
$items = isset($cultures) ? $cultures : [];

$destination = $term->slug ?? '';
$url = add_query_arg(
    ['destination' => $destination],
    site_url('/contact')
);
?>

<section id="cultures" class="destination-cultures">
    <div class="destination-cultures_slides swiper">
        <div class="swiper-wrapper">
            <?php foreach($items as $item): ?>
                <?php
                $title = isset($item['title']) ? $item['title'] : '';
                $desc = isset($item['desc']) ? $item['desc'] : '';
                $link = isset($item['link']) ? $item['link'] : [];
                $img = isset($item['image']) ? $item['image'] : '';
                ?>
                <div class="swiper-slide">
                    <div class="destination-cultures_content">
                        <div class="destination-cultures_left">
                            <!-- prettier-ignore -->
                            <h2 class="destination-cultures_title"><?= $title ?></h2>
                        </div>
                        <div class="destination-cultures_right">
                            <p class="destination-cultures_description">
                                <?= $desc ?>
                            </p>
                            <a href="<?= esc_url($url); ?>" class="destination-cultures_link compound-avian-button compound-avian-button--lg">
                                <div class="compound-avian-button__content">
                                    <span class="compound-avian-button__content-text">Start planning</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="destination-cultures_img-wrapper">
                        <?= wp_get_attachment_image($img, 'full', false, array( 'class' => 'destination-cultures_img')) ?>
                    </div>
            </div>
            <?php endforeach; ?>
            
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</section>
