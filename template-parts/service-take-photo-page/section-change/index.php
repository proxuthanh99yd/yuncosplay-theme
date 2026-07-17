<?php
$backgound_pc = 10513;
$backgound_mb = 10515;
$change_acf = get_field("change");
$title = $change_acf['title'];
$subtitle = $change_acf['subtitle'];
$steps = $change_acf['step'];
$image = $change_acf['image'];
$link = $change_acf['link'];
$link = $change_acf['link'] ?? [];
$url    = !empty($link['url']) ? esc_url($link['url']) : '#';
$title_cta  = !empty($link['title']) ? esc_html($link['title']) : '';


$section_customer_gallery_items= $change_acf['gallery_items'] ?? [];
if (!empty($section_customer_gallery_items)) {
  $section_customer_gallery_items = array_merge($section_customer_gallery_items, $section_customer_gallery_items, $section_customer_gallery_items);
};

?>


<section class="section-change">
    <?= wp_get_attachment_image($backgound_pc, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'backgound-pc')) ?>
    <?= wp_get_attachment_image($backgound_mb, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'class' => 'backgound-mb')) ?>
    <div class="header-container">
        <div>
            <h3><?php echo $title?></h3>
            <h2><?php echo $subtitle?></h2>
        </div>
        <a href="<?php echo $url; ?>" target="<?php echo $target; ?>" class="button-contact">
            <?php 
            get_template_part(
                'template-parts/components/animated-button/index',
                null,
                array(
                    'text' => $title_cta
                )
            ); 
            ?>
        </a>
    </div>
    <div class="grid-container">
        <div class="grid-item grid-item__deco"></div>
        <div class="grid-item grid-item__deco"></div>
        <div class="grid-item grid-item__deco"></div>
        <div class="grid-item grid-item__deco"></div>
        <?php for ($index = 0; $index < 4; $index++): 
            $step     = $steps[$index] ?? null;
            $image    = $step['image'] ?? '';
            $name     = $step['name'] ?? '';
            $contents = $step['content'] ?? [];
        ?>
        <div class="grid-item">
            <div class="grid-item__step">Bước <?php echo $index + 1; ?></div>

            <?php if (!empty($step)): ?>
            <div class="grid-item__content">
                <div class="grid-item__heading">
                    <?php 
                    if (!empty($image)) {
                        echo wp_get_attachment_image($image, 'full', false, array(
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                            'class'    => 'grid-item__content-image',
                        ));
                    }else{
                        echo '<div class="grid-item__content-image"></div>';
                    }
                    ?>

                    <?php if (!empty($name)): ?>
                    <h3><?php echo esc_html($name); ?></h3>
                    <?php endif; ?>
                </div>

                <?php if (!empty($contents) && is_array($contents)): ?>
                <?php foreach ($contents as $content): 
                        $text = $content['text'] ?? '';
                    ?>
                <?php if (!empty($text)): ?>
                <div class="grid-item__desc">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path
                            d="M6.26918 0.199326L7.17133 3.15802C7.29149 3.55211 7.50672 3.91061 7.79806 4.20195C8.08939 4.49328 8.44789 4.70851 8.84198 4.82867L11.8007 5.73082C12.0664 5.81192 12.0664 6.18808 11.8007 6.26918L8.84198 7.17133C8.44789 7.29149 8.08939 7.50672 7.79806 7.79806C7.50672 8.08939 7.29149 8.44789 7.17133 8.84198L6.26905 11.8007C6.18796 12.0664 5.81179 12.0664 5.73069 11.8007L4.82855 8.84198C4.70838 8.44789 4.49315 8.08939 4.20182 7.79806C3.91049 7.50672 3.55199 7.29149 3.1579 7.17133L0.199326 6.26905C-0.0664421 6.18796 -0.0664421 5.81179 0.199326 5.73069L3.15802 4.82855C3.55211 4.70838 3.91061 4.49315 4.20195 4.20182C4.49328 3.91049 4.70851 3.55199 4.82867 3.1579L5.73095 0.199326C5.81204 -0.0664421 6.18808 -0.0664421 6.26918 0.199326Z"
                            fill="#CB5140" />
                    </svg>

                    <p><?php echo esc_html($text); ?></p>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endfor; ?>
    </div>
    <div class="button-contact__mb">
        <a href="<?php echo $url; ?>" target="<?php echo $target; ?>">
            <?php 
            get_template_part(
                'template-parts/components/animated-button/index',
                null,
                array(
                    'text' => $title_cta
                )
            ); 
            ?>
        </a>
    </div>
    <div class="gallery__container">
        <?php if (!empty($section_customer_gallery_items)) : ?>
        <?php get_template_part('template-parts/components/marquee/index', null, [
            'image_ids' => $section_customer_gallery_items,
        ]);?>
        <?php endif; ?>
    </div>
</section>