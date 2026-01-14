<?php 
$section_banner = get_field('section_banner');
$banner_list = $section_banner['banner_items'];

$icon_search_id = 1070;
$icon_close_id = 1062;

?>

<section id="banner" class="banner">
    <!-- Slider main container -->
    <div class="swiper banner-swiper banner-swiper--hide-pagination">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper banner-swiper-wrapper">
            <!-- Slides -->
            <?php if(!empty($banner_list)) : ?>
                <?php foreach($banner_list as $index => $banner_item) : 
                    $title = $banner_item['title'];    
                    $description = $banner_item['description'];    
                    $link = $banner_item['link'];    
                    $image_desktop_id = $banner_item['image_desktop'];    
                    $image_mobile_id = $banner_item['image_mobile'];
                    $is_first_slide = ($index === 0);

                    if($link) {
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                    }
                ?>
                    <div class="swiper-slide banner-swiper-slide">
                        <!-- Background overlay -->
                        <div class="banner-swiper-slide__background-overlay"></div>
                       <!-- Ảnh desktop  -->
                        <?= wp_get_attachment_image($image_desktop_id, 'full', false, array( 'class' => 'banner-swiper-slide__image banner-swiper-slide__image--desktop')) ?>

                       <!-- Ảnh mobile -->
                       <?= wp_get_attachment_image($image_mobile_id, 'full', false, array( 'class' => 'banner-swiper-slide__image banner-swiper-slide__image--mobile')) ?>

                        <?php if($is_first_slide) : ?>
                            <!-- Content banner search: Hiển thị phần search và kết quả tìm kiếm đối với slide 1 -->
                            <div class="banner-swiper-slide__content banner-swiper-slide__content--search">
                                <h1 class="banner-swiper-slide__content__title">
                                    <?= $title ?>
                                </h1>
                                <div id="banner-search-container"  class="banner-swiper-slide__content__search">
                                    <div class="banner-swiper-slide__content__search-input">
                                        <input id="banner-search-input" type="text" name="swiper-search-input" placeholder="<?= IS_MOBILE ? 'Enter search content' : 'Search destinations, experiences or hotels......'?>"/>
                                        <button id="banner-search-clear" class="banner-swiper-slide__content__search-input__icon banner-swiper-slide__content__search-input__icon--close">
                                            <?= wp_get_attachment_image($icon_close_id, 'full', false, array( 'class' => '')) ?>
                                        </button>
                                        <label for="banner-search-input" class="banner-swiper-slide__content__search-input__icon banner-swiper-slide__content__search-input__icon--search">
                                            <?= wp_get_attachment_image($icon_search_id, 'full', false, array( 'class' => '')) ?>
                                        </label>
                                    </div>
                                    <ul id="banner-search-result" class="banner-swiper-slide__content__search-result">                                  
                                    </ul>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Content banner link: Hiển thị content và nút link đến trang khác -->
                            <div class="banner-swiper-slide__content banner-swiper-slide__content--link">
                                <p class="banner-swiper-slide__content__title"><?= $title ?></p>
                                <p class="banner-swiper-slide__content__description"><?= $description ?></p>
                                <?php if(!empty($link) && !empty($link_url)) :?>
                                    <a class="banner-swiper-slide__content__link" href="<?= $link_url ?>" target="<?= $link_target ?>">
                                        <span class="banner-swiper-slide__content__link-text"><?= $link_title ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination banner-swiper-pagination"></div>

        <!-- Navigation buttons -->
        <button class="banner-swiper-button-navigation banner-swiper-button-prev">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M13.332 15.834L7.08183 9.43542L13.332 3.33398" stroke="currentColor" stroke-width="2"/>
            </svg>
        </button>
        <button class="banner-swiper-button-navigation banner-swiper-button-next">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M6.66797 15.834L12.9182 9.43542L6.66797 3.33398" stroke="currentColor" stroke-width="2"/>
            </svg>
        </button>
    </div>
</section>

<template id="banner-search-result-item">
    <li class="banner-swiper-slide__content__search-result__item">
        <span class="banner-swiper-slide__content__search-result__item__text">
        </span>
    </li>
</template>