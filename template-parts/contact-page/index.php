<?php
/**
 * Contact Page — Section Orchestrator
 * Loads breadcrumb + 2-column layout (contact-info | contact-form)
 */

get_template_part('template-parts/contact-page/section-breadcrumb/index');
?>

<section class="contact-page">
    <div class="contact-page__container">
        <div class="contact-page__col contact-page__col--left">
            <?php get_template_part('template-parts/contact-page/section-contact-info/index'); ?>
        </div>
        <div class="contact-page__col contact-page__col--right">
            <?php get_template_part('template-parts/contact-page/section-contact-form/index'); ?>
        </div>
    </div>
</section>
