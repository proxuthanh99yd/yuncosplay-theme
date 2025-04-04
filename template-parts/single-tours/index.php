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
                    <a class="tours-navigation__link" href="#">Vue d’ensemble</a>
                </li>
                <li class="tours-navigation__item">
                    <a class="tours-navigation__link" href="#">Vue d’ensemble</a>
                </li>
                <li class="tours-navigation__item">
                    <a class="tours-navigation__link" href="#">Vue d’ensemble</a>
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
        form here
    </aside>
</section>
<?php
get_template_part('template-parts/single-tours/section-related/index');
?>
<section class="customize-tour">
    <div class="customize-tour__title">
        <h2>Vous souhaitez composer votre voyage sur mesure?</h2>
    </div>
    <a class="customize-tour__link" href="#">
        Créer votre voyage sur-mesure
    </a>
</section>