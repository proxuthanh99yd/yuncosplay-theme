<?php
/**
 * Contact Page - Breadcrumb Section
 */
?>

<nav class="ct-breadcrumb" aria-label="Breadcrumb">
    <ol class="ct-breadcrumb__list">
        <li class="ct-breadcrumb__item">
            <a href="<?= esc_url(home_url('/')); ?>" class="ct-breadcrumb__link">Trang chủ</a>
        </li>

        <li class="ct-breadcrumb__separator" aria-hidden="true"></li>

        <li class="ct-breadcrumb__item">
            <span class="ct-breadcrumb__current"><?= esc_html(get_the_title()); ?></span>
        </li>
    </ol>
</nav>
