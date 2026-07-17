<?php
$image_ids = $args['image_ids'] ?? [];

if (empty($image_ids) || !is_array($image_ids)) {
    return;
}

?>



<div class="marquee-component marquee-images" data-speed="60">
    <div class="marquee-images__track">
        <?php foreach ($image_ids as $image_id) : ?>
        <?php
            $image_id = absint($image_id);

            if (!$image_id) {
                continue;
            }

            echo wp_get_attachment_image($image_id, 'large', false, [
                'class'    => 'marquee-images__img',
                'loading'  => 'lazy',
                'decoding' => 'async',
            ]);
        ?>
        <?php endforeach; ?>
    </div>
</div>