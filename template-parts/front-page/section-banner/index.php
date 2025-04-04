<?php
$_SEARCH_ICON = 32;
?>

<section class="banner">
    <div class="banner__video">
        <video id="banner-video"
            src="/wp-content/uploads/2025/03/9548bc3033110ad3b5d674b22c49ab76630eb282.mp4"
            data-src="/wp-content/uploads/2025/03/9548bc3033110ad3b5d674b22c49ab76630eb282.mp4"
            autoplay loop muted playsinline preload="auto"
            poster="/wp-content/uploads/2025/03/IMG.png"></video>
        <div class="banner__overlay"></div>
        <div class="banner__content">
            <h1>
                <p>Votre Meilleure Agence de Voyage</p>
                <p>Francophone au Vietnam</p>
            </h1>
        </div>
    </div>
    <div class="banner-search">
        <form action="">
            <input type="text" placeholder="Recherchez des visites, Thème, tout">
            <button type="submit">
                <?= wp_get_attachment_image($_SEARCH_ICON, 'full') ?>
                <span class="pc-button-16-b">Recherche</span>
            </button>
        </form>
    </div>
</section>