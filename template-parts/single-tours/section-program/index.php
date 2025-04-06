<?php
$_LOCATION_ICON = wp_is_mobile() ? 'https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Program-Icon.svg' : 'https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Program-Icon-2.svg';
?>

<section class="program" id="program">
    <div class="program__container">
        <div class="program__header">
            <h2 class="pc-h5-22b program__title">Programme</h2>
            <button class="program__header-btn active">
                <span>Masquer tout</span>
                <img class="icon icon-minus"
                    src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/minus.svg" alt="">
                <img class="icon icon-plus"
                    src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Add.svg" alt="">
            </button>
        </div>
        <div class="program__body">
            <div class="program__body-content">
                <div class="faqs__content">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="faqs__item">
                            <div class="faqs__item-title">
                                <h3 class="faqs__item-title-text">
                                    <img src="<?= $_LOCATION_ICON ?>" alt="">
                                    <strong>JOUR 0<?= $i + 1 ?>:</strong> Hanoi – Arrivée
                                </h3>
                            </div>
                            <div class="faqs__item-content">
                                <div class="faqs__item-content-text">
                                    <?= get_full_content(166) ?>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>