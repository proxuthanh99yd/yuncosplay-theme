<section class="good-know" id="good-know">
    <div class="good-know__container">
        <div class="good-know__header">
            <h2 class="pc-h5-22b good-know__title">Bon à savoir</h2>
        </div>
        <div class="good-know__body">
            <div class="good-know__body-content">
                <div class="faqs__content">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="faqs__item">
                            <div class="faqs__item-title">
                                <h3 class="faqs__item-title-text">
                                    <?= $i + 1 ?>.Climat au Vietnam
                                </h3>
                            </div>
                            <div class="faqs__item-content">
                                <div class="faqs__item-content-text">
                                    <?= get_full_content(1) ?>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>