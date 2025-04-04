<?php
$_CALL_ICON_ID = 17;
$_ARROW_DOWN_ICON_ID = 16;
$_MESSAGE_ICON_ID = 19;
$_CLOSE_CIRCLE_ID = 18;
$_DECO_ICON_ID = 24;
$_STAR_ICON_ID = 25;
$_CLOCK_ICON_ID = 26;
?>
<header id="header" class="header">
    <div class="header__top">
        <div class="header__top-container">
            <div class="header__contact">
                <a href="tel:+84 888 789 346" class="header__contact-item">
                    <?= wp_get_attachment_image($_CALL_ICON_ID, 'full') ?> +84 888 789 346 (Whatsapp)
                </a>
                <a href="mailto:helloasia@horizonvietnam.com" class="header__contact-item">
                    <?= wp_get_attachment_image($_MESSAGE_ICON_ID, 'full') ?> helloasia@horizonvietnam.com
                </a>
            </div>
        </div>
    </div>

    <div class="header__main">
        <div class="header__main-container">
            <a href="<?= home_url() ?>" class="header__logo">
                <img class="header__logo--white" src="/wp-content/uploads/2025/03/header-logo-white.png"
                    alt="Horizon Vietnam Travel">
                <img class="header__logo--color" src="/wp-content/uploads/2025/03/header-logo-color.png"
                    alt="Horizon Vietnam Travel">
            </a>
            <nav class="header__menu">
                <ul class="header__menu-list">
                    <li class="header__menu-item">
                        <a class="pc-button-14-b-in header__menu-link" href="#">
                            Circuit
                            <?= wp_get_attachment_image($_ARROW_DOWN_ICON_ID, 'full') ?>
                        </a>
                        <div class="header__submenu">
                            <div class="header__submenu-container">
                                <?php
                                for ($i = 1; $i <= 4; $i++):
                                ?>
                                <a class="header__submenu-card">
                                    <img class="header__submenu-card-background"
                                        src="/wp-content/uploads/2025/03/image.png" alt="">
                                    <img class="header__submenu-card-foreground"
                                        src="/wp-content/uploads/2025/03/Component-1062.png" alt="">
                                    <span class="header__submenu-card-gradient"></span>
                                    <span class="header__submenu-card-title pc-h4-32semi">Vietnam</span>
                                </a>
                                <?php endfor; ?>
                                <button class="header__submenu-close">
                                    <?= wp_get_attachment_image($_CLOSE_CIRCLE_ID, 'full') ?>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="header__menu-item">
                        <a class="pc-button-14-b-in header__menu-link" href="#">
                            Destination
                            <?= wp_get_attachment_image($_ARROW_DOWN_ICON_ID, 'full') ?>
                        </a>
                        <div class="header__submenu header__submenu--second">
                            <div class="header__submenu-container">
                                <div class="header__submenu-left">
                                    <a class="pc-button-16-b header__submenu-left-link active" href="">Aventure</a>
                                    <a class="pc-button-16-b header__submenu-left-link" href="">Lune De Miel Voyage</a>
                                    <a class="pc-button-16-b header__submenu-left-link" href="">Voyage Culturel</a>
                                    <a class="pc-button-16-b header__submenu-left-link" href="">Voyage De Charité</a>
                                    <a class="pc-button-16-b header__submenu-left-link" href="">Parcourez tous les
                                        circuits</a>
                                </div>
                                <div class="scroll-bar-hide header__submenu-right">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <div class="header__submenu-item">
                                        <img class="header__submenu-card-background skeleton"
                                            src="/wp-content/uploads/2025/03/IMG.png" alt="">
                                        <span class="header__submenu-card-gradient"></span>
                                        <div class="header__submenu-item-header">
                                            <div class="header__submenu-item-header-left">
                                                <?= wp_get_attachment_image($_CLOCK_ICON_ID, 'full') ?>
                                                <span>Aventure</span>
                                            </div>
                                            <div class="header__submenu-item-header-right">
                                                <span>5.0</span>
                                                <?= wp_get_attachment_image($_STAR_ICON_ID, 'full') ?>
                                            </div>
                                        </div>
                                        <div class="header__submenu-item-content">
                                            <p class="header__submenu-item-title">Pure Évasion Visite</p>
                                            <p class="header__submenu-item-date"><span class="date-day">13</span>
                                                jours- <span class="date-night">10</span> nuits</p>
                                            <p class="header__submenu-item-location">Hanoi, Ha Giang, Cat Ba
                                                Island</p>
                                            <p class="header__submenu-item-deco">
                                                <span> Explorez maintenant</span>
                                                <?= wp_get_attachment_image($_DECO_ICON_ID, 'full') ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <button class="header__submenu-close">
                                <?= wp_get_attachment_image($_CLOSE_CIRCLE_ID, 'full') ?>
                            </button>
                        </div>
                    </li>
                    <li class="header__menu-item"><a class="pc-button-14-b-in header__menu-link" href="#">Info
                            Pratique</a></li>
                    <li class="header__menu-item"><a class="pc-button-14-b-in header__menu-link" href="#">Culture</a>
                    </li>
                    <li class="header__menu-item"><a class="pc-button-14-b-in header__menu-link" href="#">Qui
                            Sommes-Nous</a></li>
                </ul>
            </nav>
            <div class="header__button">
                <a href="#" class="pc-button-16-b header__button--primary">Devis sur-mesure</a>
            </div>
        </div>
    </div>
</header>