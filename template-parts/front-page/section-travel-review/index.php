<section class="travel-review">
    <h2 class="pc-h4-32semi travel-review__title">Travel Review</h2>
    <div class="travel-review__container">
        <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="travel-review_item">
                <?= wp_get_attachment_image(115, 'full', false, [
                    'class' => 'travel-review_item__image',
                ]) ?>
            </div>
        <?php endfor; ?>
    </div>
</section>