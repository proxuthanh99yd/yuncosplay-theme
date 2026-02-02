<?php
$term = get_queried_object();
$year = get_field('destination_year', $term);
$title = isset($year['title']) ? $year['title'] : '';
$desc = isset($year['desc']) ? $year['desc'] : '';
$list = isset($year['list']) ? $year['list'] : [];

$datas = [];

foreach ($list as $item) {
    $img_src = wp_get_attachment_image_src($item['image'], 'full')[0] ?? '';
    $datas[] = [
        'month'     => $item['month'],
        'content'   => $item['content'],
        'image'     => $img_src
    ];
}

$first = $list[0];
$first_content = isset($first['content']) ? $first['content'] : '';
$first_img = isset($first['image']) ? $first['image'] : '';

$deco_id = 1410;
?>


<section id="throughout-the-year" class="destination-year">
    <?= wp_get_attachment_image($deco_id, 'full', false, array( 'class' => 'destination-year_deco')) ?>
    <div class="destination-year_container">
        <div class="destination-year_header">
            <h2 class="destination-year_title"><?= $title ?></h2>
            <p class="destination-year_desc"><?= $desc ?></p>
        </div>
        <div class="destination-year_tabs">
            <?php foreach($datas as $index => $data): ?>
            <?php $month = isset($data['month']) ? $data['month'] : '' ?>
                <button type="button" class="destination-year_tab <?= $index === 0 ? 'active' : '' ?>" data-month="<?= esc_attr($month) ?>">
                    <?= esc_html($month) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="destination-year_body">
            <div class="destination-year_content">
                <div class="destination-year_content-html" data-lenis-prevent>
                    <?= $first_content ?>
                </div>
                <div class="destination-year_content-overlay"></div>
            </div>
            <div class="destination-year_img-wrapper">
                <?= wp_get_attachment_image($first_img, 'full', false, array( 'class' => 'destination-year_img')) ?>
            </div>
        </div>
    </div>
</section>


<script>
    const years = <?= json_encode($datas) ?>;
</script>