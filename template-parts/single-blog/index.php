<?php
// Contact info
$cta = function_exists('get_field') ? get_field('cta', 'option') : [];
$cta_zalo      = $cta['link_zalo'] ?? null;
$cta_messenger = $cta['link_messenger'] ?? null;
$cta_hotline   = $cta['link_hotline'] ?? null;
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="blog-detail-wrapper">

            <div class="main-layout-wrapper">
                <div class="container main-layout">
    
                    <?php
                    $post_url   = urlencode(get_permalink());
                    $post_title = urlencode(get_the_title());
                    ?>
    
                    <aside class="sticky-share-column">
    
                        <div class="share-label-wrap">
                            <span class="share-label">Chia sẻ</span>
                        </div>
                        <div class="share-socials-list">
                            <a href="javascript:void(0);" id="copy_link" class="share-circle" data-url="<?php the_permalink(); ?>" title="Sao chép liên kết">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                    <g clip-path="url(#clip0_528_6912)">
                                        <path d="M37.1911 15.351C36.6913 14.8503 36.0976 14.4532 35.444 14.1825C34.7904 13.9118 34.0898 13.7728 33.3824 13.7735C32.6755 13.7719 31.9753 13.9104 31.3222 14.1809C30.6691 14.4515 30.0761 14.8487 29.5774 15.3497L22.6261 22.301C21.9507 22.9776 21.4674 23.8216 21.2258 24.7466C20.9841 25.6716 20.9929 26.6442 21.2511 27.5647C21.325 27.8268 21.5 28.0487 21.7376 28.1818C21.9752 28.3148 22.2559 28.348 22.518 28.2741C22.7801 28.2001 23.002 28.0251 23.1351 27.7876C23.2681 27.55 23.3013 27.2693 23.2274 27.0072C23.0674 26.4374 23.0618 25.8354 23.2112 25.2628C23.3605 24.6901 23.6595 24.1675 24.0774 23.7485L31.0286 16.7985C31.6563 16.1917 32.4972 15.8559 33.3701 15.8632C34.2431 15.8705 35.0782 16.2205 35.6956 16.8377C36.313 17.4549 36.6632 18.29 36.6707 19.1629C36.6783 20.0359 36.3427 20.8769 35.7361 21.5047L28.7849 28.4559C28.3293 28.9122 27.7508 29.2258 27.1199 29.3584C26.8527 29.4158 26.6192 29.577 26.4708 29.8065C26.3224 30.0359 26.2713 30.315 26.3286 30.5822C26.386 30.8494 26.5471 31.0829 26.7766 31.2313C27.0061 31.3797 27.2852 31.4308 27.5524 31.3735C28.5718 31.1583 29.5065 30.651 30.2424 29.9135L37.1936 22.9635C37.6942 22.464 38.0914 21.8706 38.3622 21.2174C38.6331 20.5641 38.7725 19.8639 38.7722 19.1567C38.772 18.4495 38.6322 17.7493 38.3609 17.0963C38.0896 16.4432 37.6921 15.8501 37.1911 15.351Z" fill="#CB5140" stroke="#CB5140" stroke-width="0.3125" />
                                        <path d="M27.5363 23.627C27.4997 23.4972 27.4379 23.3759 27.3544 23.2701C27.2709 23.1642 27.1674 23.0758 27.0498 23.0099C26.9321 22.9441 26.8027 22.902 26.6688 22.8862C26.5349 22.8703 26.3992 22.881 26.2694 22.9176C26.1397 22.9542 26.0184 23.016 25.9125 23.0995C25.8066 23.183 25.7182 23.2865 25.6523 23.4041C25.5865 23.5218 25.5444 23.6512 25.5286 23.7851C25.5127 23.919 25.5234 24.0547 25.56 24.1845C25.7182 24.7531 25.7227 25.3535 25.5732 25.9244C25.4236 26.4953 25.1254 27.0164 24.7088 27.4345L17.7575 34.3882C17.1286 34.9882 16.2899 35.3183 15.4207 35.308C14.5515 35.2978 13.7209 34.9479 13.1062 34.3333C12.4916 33.7187 12.1418 32.888 12.1315 32.0188C12.1212 31.1497 12.4513 30.311 13.0513 29.682L20.0013 22.7295C20.4559 22.2735 21.0337 21.9598 21.6638 21.827C21.928 21.7709 22.1594 21.6129 22.3078 21.3873C22.4563 21.1617 22.5099 20.8867 22.4569 20.6219C22.4039 20.357 22.2487 20.1238 22.0249 19.9726C21.8011 19.8215 21.5267 19.7647 21.2613 19.8145H21.2413C20.2217 20.0312 19.287 20.5398 18.5513 21.2782L11.5988 28.2295C10.621 29.2451 10.0807 30.6037 10.094 32.0135C10.1073 33.4232 10.6732 34.7714 11.6699 35.7684C12.6667 36.7653 14.0149 37.3314 15.4246 37.3449C16.8343 37.3585 18.193 36.8184 19.2088 35.8407L26.16 28.8882C26.8352 28.212 27.3185 27.3684 27.5603 26.4439C27.8022 25.5194 27.7939 24.5472 27.5363 23.627Z" fill="#CB5140" stroke="#CB5140" stroke-width="0.3125" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_528_6912">
                                            <rect width="50" height="50" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                            <a href="javascript:void(0);"
                                class="share-circle share-trigger"
                                data-share-url="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                    <path d="M22.4262 36.25V25.625H18.75V22.0833H22.4262V20.1815C22.4262 16.5805 24.2468 15 27.3532 15C28.8411 15 29.6278 15.1062 30 15.1549V18.5417H27.8816C26.5628 18.5417 26.1023 19.2119 26.1023 20.5702V22.0833H29.9669L29.4421 25.625H26.1023V36.25H22.4262Z" fill="#CB5140" />
                                </svg>
                            </a>
                            <a href="javascript:void(0);"
                                class="share-circle share-trigger"
                                data-share-url="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                    <path d="M34.0485 14.4453H15.9485C15.1192 14.4453 14.4453 15.1286 14.4453 15.9674V34.0344C14.4453 34.8731 15.1192 35.5564 15.9485 35.5564H34.0485C34.8779 35.5564 35.5564 34.8731 35.5564 34.0344V15.9674C35.5564 15.1286 34.8779 14.4453 34.0485 14.4453ZM20.8258 32.5406H17.6968V22.4656H20.8305V32.5406H20.8258ZM19.2613 21.0897C18.2576 21.0897 17.447 20.2744 17.447 19.2754C17.447 18.2764 18.2576 17.4612 19.2613 17.4612C20.2603 17.4612 21.0755 18.2764 21.0755 19.2754C21.0755 20.2791 20.265 21.0897 19.2613 21.0897ZM32.5547 32.5406H29.4257V27.6398C29.4257 26.4711 29.4022 24.9679 27.8 24.9679C26.1695 24.9679 25.9198 26.2402 25.9198 27.5549V32.5406H22.7908V22.4656H25.7925V23.8416H25.8349C26.2543 23.05 27.2769 22.2159 28.799 22.2159C31.9657 22.2159 32.5547 24.3034 32.5547 27.0177V32.5406Z" fill="#CB5140" />
                                </svg>
                            </a>
                            <a href="javascript:void(0);"
                                class="share-circle share-trigger"
                                data-share-url="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                    <path d="M26.7961 23.5528L33.828 15.5547H32.1616L26.0559 22.4994L21.1793 15.5547H15.5547L22.9291 26.0563L15.5547 34.4436H17.2211L23.6689 27.1098L28.819 34.4436H34.4436L26.7957 23.5528H26.7961ZM24.5138 26.1488L23.7666 25.1031L17.8215 16.7822H20.381L25.1788 23.4974L25.9259 24.5431L32.1624 33.2719H29.6029L24.5138 26.1492V26.1488Z" fill="#CB5140" />
                                </svg>
                            </a>
                        </div>
                    </aside>
    
                    <article class="main-content">
    
                        <nav class="breadcrumb">
                            <a href="/">Trang chủ</a>
                            <span class="dot"></span>
                            <a href="<?= okhub_page_url('blogs') ?>">Danh sách bài viết</a>
                            <span class="dot active"></span>
                            <span class="current">Chi tiết bài viết</span>
                        </nav>
    
                        <h1 class="entry-title"><?php the_title(); ?></h1>
    
                        <div class="toc-custom-wrapper">
                            <div class="toc-custom-container">
                                <!--<h2 class="toc-custom-title">Tóm tắt nội dung</h2>-->
                                <?php echo do_shortcode('[ez-toc]'); ?>
                            </div>
                        </div>
    
                        <div class="entry-content">
                            <?php
                            the_content();
                            ?>
                        </div>
    
                        <footer class="entry-footer">
                            <?= wp_get_attachment_image(IS_MOBILE ? 10058 : 10056, 'full', false, ['class' => 'footer-top-line']); ?>
                            <div class="meta-row">
                                <div class="author-info">
                                    Viết bởi: <strong><?php the_author(); ?></strong>
                                </div>
                                <div class="meta-right">
                                    <div class="tags-list">
                                        <?php
                                        $tags = get_the_tags();
                                        if ($tags) :
                                            foreach ($tags as $tag) : ?>
                                                <span class="tag-item"><?php echo $tag->name; ?></span>
                                        <?php endforeach;
                                        endif; ?>
                                        <span class="tag-item">
                                            <?php
                                            $categories = get_the_category();
                                            if (! empty($categories)) {
                                                echo esc_html($categories[0]->name);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="post-date">
                                        Viết ngày: <strong><?php echo get_the_date('d/m/Y'); ?></strong>
                                    </div>
                                </div>
                            </div>
    
                            <div class="blog-post-cta">
                                <div class="cta-banner-wrapper">
                                    <?php if (!empty($cta_hotline) && !empty($cta_hotline['url'])) :
                                        $cta_hotline_url    = $cta_hotline['url'];
                                        $cta_hotline_target = $cta_hotline['target'] ? $cta_hotline['target'] : '_self';
                                        $cta_hotline_title  = $cta_hotline['title'];
                                    ?>
                                        <a href="<?php echo esc_url($cta_hotline_url); ?>" target="<?php echo esc_attr($cta_hotline_target); ?>" class="cta-contact-box">
                                            <span class="cta-contact-title">Thuê đồ ngay</span>
                                            <span class="cta-contact-phone">Gọi: <?php echo esc_html($cta_hotline_title); ?></span>
                                        </a>
                                    <?php endif; ?>

                                    <div class="cta-social-wrap">
                                        <span class="cta-social-text">Hoặc liên hệ</span>
                                        <div class="cta-social-icons">
                                            <?php if (!empty($cta_zalo) && !empty($cta_zalo['url'])) :
                                                $cta_zalo_url    = $cta_zalo['url'];
                                                $cta_zalo_target = $cta_zalo['target'] ? $cta_zalo['target'] : '_self';
                                            ?>
                                            <a href="<?php echo esc_url($cta_zalo_url); ?>" target="<?php echo esc_attr($cta_zalo_target); ?>" rel="noopener noreferrer" class="social-icon-item">
                                                <div class="icon-bg-overlay"></div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="13" viewBox="0 0 33 13" fill="none">
                                                    <path d="M7.21575 2.33978L2.35083 10.3163V10.4936H7.21575V12.6206H0V10.4936L4.89757 2.51703V2.33978H0V0.212707H7.21575V2.33978Z" fill="currentColor" />
                                                    <path d="M7.67707 12.6206V12.2307L10.1912 0.212707L13.9786 0.230432L16.509 12.2307V12.6206H14.4194L13.7827 9.21731H10.4034L9.7667 12.6206H7.67707ZM10.6972 7.30295H13.4725L12.542 2.26888H11.6441L10.6972 7.30295Z" fill="currentColor" />
                                                    <path d="M17.5301 0.212707H19.5381V10.6354H22.9011V12.6206H17.5301V0.212707Z" fill="currentColor" />
                                                    <path d="M23.1001 6.41667C23.1001 4.9159 23.3232 3.69283 23.7694 2.74747C24.2156 1.8021 24.7979 1.1108 25.5162 0.673573C26.2454 0.224524 27.0345 0 27.8834 0C28.7323 0 29.5159 0.224524 30.2342 0.673573C30.9634 1.1108 31.5511 1.8021 31.9973 2.74747C32.4436 3.69283 32.6667 4.9159 32.6667 6.41667C32.6667 7.91743 32.4436 9.1405 31.9973 10.0859C31.5511 11.0312 30.9634 11.7284 30.2342 12.1775C29.5159 12.6147 28.7323 12.8333 27.8834 12.8333C27.0345 12.8333 26.2454 12.6147 25.5162 12.1775C24.7979 11.7284 24.2156 11.0312 23.7694 10.0859C23.3232 9.1405 23.1001 7.91743 23.1001 6.41667ZM25.1734 6.41667C25.1734 7.38567 25.2931 8.18923 25.5325 8.82735C25.772 9.46547 26.093 9.94997 26.4957 10.2808C26.9093 10.5999 27.3664 10.7594 27.867 10.7594C28.3677 10.7594 28.8248 10.5999 29.2384 10.2808C29.6628 9.94997 29.9948 9.46547 30.2342 8.82735C30.4845 8.18923 30.6097 7.38567 30.6097 6.41667C30.6097 5.43585 30.4845 4.62638 30.2342 3.98826C29.9839 3.35014 29.6519 2.87155 29.2384 2.55249C28.8248 2.23343 28.3731 2.07389 27.8834 2.07389C27.3718 2.07389 26.9093 2.23933 26.4957 2.57021C26.093 2.88927 25.772 3.36786 25.5325 4.00599C25.2931 4.64411 25.1734 5.44767 25.1734 6.41667Z" fill="currentColor" />
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                            <?php if (!empty($cta_messenger) && !empty($cta_messenger['url'])) :
                                                $cta_messenger_url    = $cta_messenger['url'];
                                                $cta_messenger_target = $cta_messenger['target'] ? $cta_messenger['target'] : '_self';
                                            ?>
                                            <a href="<?php echo esc_url($cta_messenger_url); ?>" target="<?php echo esc_attr($cta_messenger_target); ?>" rel="noopener noreferrer" class="social-icon-item">
                                                <div class="icon-bg-overlay"></div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
                                                    <path d="M15.0267 0C6.72984 0 0 6.27302 0 14.0467C0 18.1632 1.89365 22.0168 5.22667 24.6965V30.4464L10.8464 27.5064C12.2168 27.8968 13.5898 28.027 15.0267 28.027C23.3235 28.027 30.0533 21.7565 30.0533 13.9803C30.0533 6.27302 23.3235 0 15.0267 0ZM16.5298 18.6864L12.74 14.6336L5.68349 18.62L13.5235 10.3232L17.3797 14.1768L24.2397 10.3232L16.5298 18.6864Z" fill="currentColor" />
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </article>
    
                    <aside class="sidebar">
                        <div class="related-products-box">
                            <h3 class="sidebar-title">Sản phẩm liên quan</h3>
                            <?= okhub_img('icons/line-1239-2', array('class' => 'title-divider')) ?>
    
                            <div class="product-list">
                                <?php
                                $tags = wp_get_post_tags(get_the_ID());
                                $tag_ids = array();
                                if ($tags) {
                                    foreach ($tags as $individual_tag) $tag_ids[] = $individual_tag->name;
                                }
    
                                $args = array(
                                    'post_type'      => 'product',
                                    'posts_per_page' => 4,
                                    'orderby'        => 'date',
                                    'order'          => 'DESC',
                                    'post__not_in'   => array(get_the_ID()),
                                );
    
                                if ($tag_ids) {
                                    $args['tax_query'] = array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field'    => 'name',
                                            'terms'    => $tag_ids,
                                            'operator' => 'IN',
                                        ),
                                    );
                                }
    
                                $related_products = new WP_Query($args);
    
                                if (!$related_products->have_posts()) {
                                    unset($args['tax_query']);
                                    $related_products = new WP_Query($args);
                                }
    
                                if ($related_products->have_posts()) :
                                    while ($related_products->have_posts()) : $related_products->the_post();
    
                                        $current_product = wc_get_product(get_the_ID());
                                        $product_id = get_the_ID();
    
                                        $rent_price_raw = $current_product->get_regular_price();
                                        $rent_price = number_format((float)($rent_price_raw ?: 0), 0, '.', '.');
    
                                        $sale_price_raw = get_post_meta($product_id, '_sale_price_custom', true);
                                        $sale_price = number_format((float)($sale_price_raw ?: 0), 0, '.', '.');
    
                                        $terms = get_the_terms($product_id, 'product_cat');
                                        $cat_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Sản phẩm';
                                ?>
                                        <div class="product-item-small">
                                            <a href="<?php the_permalink(); ?>" class="product-img">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('medium'); ?>
                                                <?php else : ?>
                                                    <?= okhub_img('common/placeholder') ?>
                                                <?php endif; ?>
                                            </a>
                                            <div class="product-info">
                                                <span class="product-cat"><?php echo esc_html($cat_name); ?></span>
                                                <h4 class="product-name">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h4>
                                                <div class="product-price">
                                                    <div class="rent-price-row">
                                                        Giá thuê (1 ngày) <span><?php echo $rent_price; ?>đ</span>
                                                    </div>
                                                    <?php if ($sale_price_raw) : ?>
                                                        <span class="sale-price">( Giá bán: <?php echo $sale_price; ?>đ )</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
    
                            <?= okhub_img('icons/line-1239-2', array('class' => 'title-divider')) ?>
                            <a href="<?= okhub_page_url('shop') ?>" class="view-all-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M19.8574 12L13.332 18.5244L11.4385 18.4668L14.165 15.7402C15.0043 14.9022 15.7723 14.1511 16.4697 13.4883L17.2705 12.7275L16.165 12.7217L5.40918 12.6709L5.47461 11.2197L16.2656 11.2725L17.3887 11.2773L16.5732 10.5049C15.8917 9.85915 15.137 9.12137 14.3086 8.29297L11.4893 5.47559L13.2715 5.41699L19.8574 12Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                                </svg>
                                <span>Xem tất cả</span>
                            </a>
                        </div>
                    </aside>
                    <?php get_template_part('template-parts/single-blog/table-content-mobile/index'); ?>
                </div>
            </div>
        </div>
<?php endwhile;
endif; ?>
<?php
get_template_part('template-parts/single-blog/related-posts/index');
