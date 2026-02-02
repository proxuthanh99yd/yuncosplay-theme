<?php
get_header();
?>
<section id="error-page">
    <div class="error_page__container">
        <?= wp_get_attachment_image(2018, 'full', false, array( 'class' => 'error-page__image')) ?>
        <?= wp_get_attachment_image(2016, 'full', false, array( 'class' => 'error-page__image')) ?>
        <?= wp_get_attachment_image(2019, 'full', false, array( 'class' => 'error-page__image-mobile')) ?>
        <div class="error-page__content">
            <p class="error-page__text">ERROR</p>
            <h1 class="error-page__title">404</h1>
            <h2 class="error-page__subtitle">Page Not Found</h2>
            <p class="error-page__description">The page you’re looking for may have been removed, renamed, or is temporarily unavailable</p>
            <a href="<?= home_url() ?>" class="error-page__link compound-avian-button"><p>Back to homepage</p></a>
        </div>
    </div>
</section>

<?php
get_footer('404');
?>