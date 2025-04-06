<?php
get_template_part('template-parts/single-tours/section-banner/index');
?>
<section class="tours-container">
    <div class="tours-container__content">
        <nav class="tours-navigation">
            <ul class="tours-navigation__list scroll-bar-hide">
                <li class="tours-navigation__item">
                    <a class="tours-navigation__link active" href="#overview">Vue d’ensemble</a>
                </li>
                <li class="tours-navigation__item">
                    <a class="tours-navigation__link" href="#program">Programme</a>
                </li>
                <li class="tours-navigation__item">
                    <a class="tours-navigation__link" href="#good-know">Bon à savoir</a>
                </li>
                <li class="tours-navigation__item">
                    <a class="tours-navigation__link" href="#benefit">Prestation</a>
                </li>
            </ul>
        </nav>
        <?php
        get_template_part('template-parts/single-tours/section-overview/index');
        get_template_part('template-parts/single-tours/section-program/index');
        get_template_part('template-parts/single-tours/section-good-know/index');
        get_template_part('template-parts/single-tours/section-benefit/index');
        ?>
    </div>
    <aside class="tours-container__sidebar">
        <div class="tours-container__sidebar-content">
            <div class="tours-container__sidebar-header">
                <h2 class="tours-container__sidebar-title">Circuit privé et personnalisé</h2>
            </div>
            <div class="tours-container__sidebar-body">
                <div class="tours-container__sidebar-destination">
                    Hanoï - Ninh Binh - Baie d'Ha Long - Da Nang - Hoi An - Hué - Saïgon - Delta du Mékong
                </div>
                <span class="tours-container__sidebar-tag">Départ : Tous les jours</span>
                <a href="#" class="pc-button-16-b tours-container__sidebar-checkout">Devis gratuit</a>
                <span class="tours-container__sidebar-tag--2">ou contactez notre expert en voyages</span>
                <a href="tel:+123" class="pc-button-16-b tours-container__sidebar-whatsapp">
                    <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/whatsapp-icon.png"
                        alt="">
                    Whatsapp</a>
            </div>
            <div class="tours-container__sidebar-footer">
                <a href="" class="tours-container__sidebar-footer-link">
                    <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/document-download.svg"
                        alt="">
                    Télécharger la version PDF
                </a>
            </div>
        </div>
    </aside>
</section>
<?php
get_template_part('template-parts/single-tours/section-related/index');
?>
<section class="customize-tour">
    <div class="customize-tour__title">
        <h2>Vous souhaitez composer votre <strong>voyage</strong> sur mesure?</h2>
    </div>
    <a class="pc-button-16-b customize-tour__link" href="#">
        Créer votre voyage sur-mesure
    </a>
</section>
<?php
get_template_part('template-parts/front-page/section-faqs/index');
get_template_part('template-parts/about-us/section-pourquo-garanties/index');
?>