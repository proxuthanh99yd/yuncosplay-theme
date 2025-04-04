<?php
$_BACKGROUND_IMAGE = 33;
if (!wp_is_mobile()):
?>
    <div class="page-background">
        <?= wp_get_attachment_image($_BACKGROUND_IMAGE, 'full') ?>
    </div>
<?php
endif;
get_template_part('template-parts/front-page/section-banner/index');
get_template_part('template-parts/front-page/section-horizon-vietnam/index');
get_template_part('template-parts/front-page/section-about-us/index');
get_template_part('template-parts/front-page/section-customized-trip/index');
get_template_part('template-parts/front-page/section-discover/index');
get_template_part('template-parts/front-page/section-starts-with/index');
get_template_part('template-parts/front-page/section-humanitarian-projects/index');
get_template_part('template-parts/front-page/section-testimonials/index');
get_template_part('template-parts/front-page/section-testimonials-2nd/index');
get_template_part('template-parts/front-page/section-travel-review/index');
get_template_part('template-parts/front-page/section-faqs/index');
