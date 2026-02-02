<?php
get_template_part('template-parts/single/section-banner/index');

$post_url = get_permalink();
$post_title = get_the_title();
$categories = get_the_category();
$primary_category = !empty($categories) ? $categories[0] : null;
?>

<article class="post" itemscope>
    <header class="post__header">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= home_url() ?>" class="breadcrumb__link">Home</a>
            <span class="breadcrumb__separator">/</span>
            <?php if ($primary_category): ?>
                <a href="<?= esc_url(get_category_link($primary_category)) ?>" class="breadcrumb__link"><?= esc_html($primary_category->name) ?></a>
                <span class="breadcrumb__separator">/</span>
            <?php endif; ?>
            <span class="breadcrumb__link last-link" aria-current="page"><?= esc_html($post_title) ?></span>
        </nav>

        <div class="socials__container--top">
            <div class="socials" aria-label="Share">
                <?php get_template_part('template-parts/single/component-socials/index'); ?>
            </div>
        </div>

        <h1 class="post-title" itemprop="headline"><?= esc_html($post_title) ?></h1>
    </header>
    <div class="line-wrapper">
        <div class="line"></div>
    </div>
    

    <main class="post__content">
        <section class="block-content">
            <div class="gutenberg-editor" itemprop="articleBody">
                <?php the_content(); ?>
            </div>
        </section>
    </main>

    <div class="line-wrapper">
        <div class="line"></div>
    </div>

    <footer class="post__footer">
        <div class="bottom">
            <div class="socials__container--bottom">
                <?php get_template_part('template-parts/single/component-socials/index'); ?>
            </div>
            <div class="post__category">
                <span class="post__category-label">Category: </span>
                <?php if ($primary_category): ?>
                    <a href="<?= esc_url(get_category_link($primary_category)) ?>" class="post__category-link"><?= esc_html($primary_category->name) ?></a>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</article>

<?php
get_template_part('template-parts/single/section-related-tour/index');
get_template_part('template-parts/single/section-related-article/index');
get_template_part('template-parts/components/section-subscribe/index');
?>