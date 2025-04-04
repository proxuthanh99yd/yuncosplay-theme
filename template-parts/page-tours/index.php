<?php
$_BACKGROUND_IMAGE = 33;
if (!wp_is_mobile()):
?>
<div class="page-background">
    <?= wp_get_attachment_image($_BACKGROUND_IMAGE, 'full') ?>
</div>
<?php
endif;
get_template_part('template-parts/page-tours/section-list-tours-1/index');
get_template_part('template-parts/page-tours/section-list-tours-2/index');
get_template_part('template-parts/page-tours/section-faqs/index');