<?php
// icon → file tĩnh theme (okhub_img)

$services = get_field('services');
$sub_title = $services['subtitle'] ?? '';
$title = $services['title'] ?? '';
$image_desktop = $services['image_desktop'] ?? '';
$image_mobile = $services['image_mobile'] ?? '';
$image = IS_MOBILE ? $image_mobile : $image_desktop;

$args = [
    'post_type'      => 'service',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
];

$services_query = new WP_Query($args);
?>

<section id="about-us-services" class="section-services">

    <?= wp_get_attachment_image($image, 'full', false, ['class' => 'section-services__bg']); ?>

    <div class="section-services__container">

        <!-- heading -->
        <div class="section-services__heading">
            <p class="section-services__description"><?= esc_html($sub_title) ?></p>
            <h2 class="section-services__title"><?= esc_html($title) ?></h2>
        </div>

        <!-- list -->
        <div class="section-services__list">

            <div class="section-services__slider swiper">
                <div class="swiper-wrapper">
                    <?php if ($services_query->have_posts()) : ?>
                        <?php while ($services_query->have_posts()) : $services_query->the_post(); ?>

                            <?php
                            $thumbnail = get_post_thumbnail_id();
                            ?>

                            <div class="swiper-slide section-services__slide">
                                <a href="<?php the_permalink(); ?>" class="section-services__item">

                                    <!-- image -->
                                    <div class="section-services__image-wrap">
                                        <div class="section-services__image-overlay"></div>
                                        <?= wp_get_attachment_image($thumbnail, 'full', false, ['class' => 'section-services__image']); ?>
                                    </div>

                                    <!-- content -->
                                    <div class="section-services__content">
                                        <span class="section-services__content-title">
                                            <?php the_title(); ?>
                                        </span>

                                        <div class="section-services__content-icon">
                                            <?= okhub_img('icons/arrow-right-2'); ?>
                                        </div>
                                    </div>

                                </a>
                            </div>

                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- navigation -->
            <div class="section-services__nav">
                <div class="section-services__nav-btn section-services__nav-next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M16.4414 10.0004L11.0791 15.3617L9.70508 15.3197L11.8555 13.1693C12.5545 12.4713 13.1938 11.8461 13.7744 11.2943L14.5752 10.5326L13.4707 10.5277L4.58398 10.4857L4.63184 9.42419L13.5527 9.46716L14.6758 9.47302L13.8604 8.70056C13.5768 8.43187 13.2779 8.14414 12.9639 7.83728L11.9756 6.85876L9.74707 4.63123L11.0283 4.58923L16.4414 10.0004Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                    </svg>
                </div>
                <div class="section-services__nav-btn section-services__nav-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M3.55859 10.0004L8.9209 15.3617L10.2949 15.3197L8.14453 13.1693C7.44548 12.4713 6.80619 11.8461 6.22559 11.2943L5.4248 10.5326L6.5293 10.5277L15.416 10.4857L15.3682 9.42419L6.44727 9.46716L5.32422 9.47302L6.13965 8.70056C6.42323 8.43187 6.72206 8.14414 7.03613 7.83728L8.02441 6.85876L10.2529 4.63123L8.97168 4.58923L3.55859 10.0004Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                    </svg>
                </div>
            </div>

        </div>

        <!-- controls -->
        <div class="section-services__controls">
            <div class="section-services__nav-btn section-services__nav-next">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.4414 10.0004L11.0791 15.3617L9.70508 15.3197L11.8555 13.1693C12.5545 12.4713 13.1938 11.8461 13.7744 11.2943L14.5752 10.5326L13.4707 10.5277L4.58398 10.4857L4.63184 9.42419L13.5527 9.46716L14.6758 9.47302L13.8604 8.70056C13.5768 8.43187 13.2779 8.14414 12.9639 7.83728L11.9756 6.85876L9.74707 4.63123L11.0283 4.58923L16.4414 10.0004Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                </svg>
            </div>
            <div class="section-services__pagination"></div>
            <div class="section-services__nav-btn section-services__nav-prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M3.55859 10.0004L8.9209 15.3617L10.2949 15.3197L8.14453 13.1693C7.44548 12.4713 6.80619 11.8461 6.22559 11.2943L5.4248 10.5326L6.5293 10.5277L15.416 10.4857L15.3682 9.42419L6.44727 9.46716L5.32422 9.47302L6.13965 8.70056C6.42323 8.43187 6.72206 8.14414 7.03613 7.83728L8.02441 6.85876L10.2529 4.63123L8.97168 4.58923L3.55859 10.0004Z" fill="#680103" stroke="#680103" stroke-width="0.8888" />
                </svg>
            </div>
        </div>

    </div>
</section>