<?php

// Highlight Section Shortcode (supports images selected from Media Library)
function highlight_section_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(array(
        'title' => 'Tiêu đề nổi bật',
        'subtitle' => '',
        'content' => '',
        'background' => '#f8f9fa',
        'text_color' => '#333',
        // legacy shortcode keys
        'button_text' => '',
        'button_url' => '',
        'button_color' => '#007cba',
        // new shortcode keys (snake_case)
        'link_title' => '',
        'link_url' => '',
        'link_color' => '#007cba',
        // block attributes (camelCase) - include so shortcode_atts keeps them
        'linkTitle' => '',
        'linkUrl' => '',
        'linkColor' => '#007cba',
        'layout' => 'center', // center, left, right
        'class' => '',
        // images can be attachment IDs or URLs
        'image1' => '',
        'image2' => '',
        'image3' => '',
    ), $atts);

    // Backwards compatibility for image_1 / image1 variants
    if (empty($atts['image1']) && !empty($atts['image_1'])) {
        $atts['image1'] = $atts['image_1'];
    }
    if (empty($atts['image2']) && !empty($atts['image_2'])) {
        $atts['image2'] = $atts['image_2'];
    }
    if (empty($atts['image3']) && !empty($atts['image_3'])) {
        $atts['image3'] = $atts['image_3'];
    }

    // Sanitize inputs
    $title = esc_html($atts['title']);
    $subtitle = esc_html($atts['subtitle']);
    $content = wp_kses_post($atts['content']);
    $background = esc_attr($atts['background']);
    $text_color = esc_attr($atts['text_color']);
    // Normalize link/button values (support legacy and block attribute names)
    $link_title = '';
    if (!empty($atts['linkTitle'])) {
        $link_title = $atts['linkTitle'];
    } elseif (!empty($atts['link_title'])) {
        $link_title = $atts['link_title'];
    } elseif (!empty($atts['button_text'])) {
        $link_title = $atts['button_text'];
    }
    $link_url = '';
    if (!empty($atts['linkUrl'])) {
        $link_url = $atts['linkUrl'];
    } elseif (!empty($atts['link_url'])) {
        $link_url = $atts['link_url'];
    } elseif (!empty($atts['button_url'])) {
        $link_url = $atts['button_url'];
    }
    $link_color = '#007cba';
    if (!empty($atts['linkColor'])) {
        $link_color = $atts['linkColor'];
    } elseif (!empty($atts['link_color'])) {
        $link_color = $atts['link_color'];
    } elseif (!empty($atts['button_color'])) {
        $link_color = $atts['button_color'];
    }

    $button_text = esc_html($link_title);
    $button_url = esc_url($link_url);
    $button_color = esc_attr($link_color);
    $layout = esc_attr($atts['layout']);
    $class = esc_attr($atts['class']);

    // Image inputs (can be attachment IDs or URLs)
    $image1 = $atts['image1'];
    $image2 = $atts['image2'];
    $image3 = $atts['image3'];

    // Helper to render image from ID or URL
    $render_image = function ($id_or_url, $size = 'large', $img_class = '') {
        if (empty($id_or_url)) return '';
        if (is_numeric($id_or_url)) {
            $id = intval($id_or_url);
            return wp_get_attachment_image($id, $size, false, array('class' => $img_class));
        }
        $url = esc_url($id_or_url);
        return '<img src="' . $url . '" class="' . esc_attr($img_class) . '" alt="" />';
    };

    // Build CSS variables
    $inline_styles = sprintf('
        --highlight-bg: %s;
        --highlight-text: %s;
        --highlight-button: %s;
    ', $background, $text_color, $button_color);

    // Build classes
    $classes = 'highlight-section ' . $layout;
    if (!empty($class)) {
        $classes .= ' ' . $class;
    }

    ob_start();
?>
    <section class="<?php echo $classes; ?>" style="<?php echo $inline_styles; ?>">
        <div class="highlight-container">
            <div class="highlight-grid">
                <div class="highlight-col highlight-image-left">
                    <?php echo $render_image($image1, 'large', 'highlight-image'); ?>
                </div>

                <div class="highlight-col highlight-image-mid">
                    <?php echo $render_image($image2, 'large', 'highlight-image'); ?>
                </div>
                <?php if (!IS_MOBILE): ?>
                    <div class="highlight-col highlight-content-col">
                        <?php if (!empty($title)): ?>
                            <p class="highlight-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                    <path
                                        d="M0 9.49999C4.22222 8.44442 8.44444 4.22222 9.5 0C10.5556 4.22222 14.7778 8.44442 19 9.49999C14.7778 10.5555 10.5556 14.7777 9.5 19C8.44444 14.7777 4.22222 10.5555 0 9.49999Z"
                                        fill="#630F3F" />
                                </svg>
                                <?php echo $title; ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($content)): ?>
                            <div class="highlight-content">
                                <?php echo $content; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($button_text) && !empty($button_url)): ?>
                            <div class="highlight-actions">
                                <a href="<?php echo $button_url; ?>" class="compound-avian-button highlight-button">
                                    <div class="compound-avian-button__content">
                                        <span class="compound-avian-button__content-text header-info__item-link--contact-text">
                                            <?php echo $button_text; ?>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="highlight-col highlight-image-right">
                    <?php echo $render_image($image3, 'large', 'highlight-image'); ?>
                </div>
            </div>
            <?php if (IS_MOBILE): ?>
                <div class="highlight-col highlight-content-col">
                    <?php if (!empty($title)): ?>
                        <p class="highlight-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                <path
                                    d="M0 9.49999C4.22222 8.44442 8.44444 4.22222 9.5 0C10.5556 4.22222 14.7778 8.44442 19 9.49999C14.7778 10.5555 10.5556 14.7777 9.5 19C8.44444 14.7777 4.22222 10.5555 0 9.49999Z"
                                    fill="#630F3F" />
                            </svg>
                            <?php echo $title; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($content)): ?>
                        <div class="highlight-content">
                            <?php echo $content; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($button_text) && !empty($button_url)): ?>
                        <div class="highlight-actions">
                            <a href="<?php echo $button_url; ?>" class="compound-avian-button highlight-button">
                                <div class="compound-avian-button__content">
                                    <span class="compound-avian-button__content-text header-info__item-link--contact-text">
                                        <?php echo $button_text; ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php
    return ob_get_clean();
}
add_shortcode('highlight_section', 'highlight_section_shortcode');

// Register Gutenberg Block
function highlight_section_register_block()
{
    // Register block editor script (include server-side render dependency)
    wp_register_script(
        'highlight-section-block',
        get_template_directory_uri() . '/assets/js/blocks/highlight-section.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-server-side-render'),
        '1.0.0',
        true
    );

    // Register block
    register_block_type('okhub/highlight-section', array(
        'editor_script' => 'highlight-section-block',
        'render_callback' => 'highlight_section_shortcode',
        'supports' => array(
            'align' => true,
            'customClassName' => true,
        ),
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Tiêu đề nổi bật'),
            'subtitle' => array('type' => 'string', 'default' => ''),
            'content' => array('type' => 'string', 'default' => ''),
            'background' => array('type' => 'string', 'default' => '#f8f9fa'),
            'textColor' => array('type' => 'string', 'default' => '#333'),
            'linkTitle' => array('type' => 'string', 'default' => ''),
            'linkUrl' => array('type' => 'string', 'default' => ''),
            'linkColor' => array('type' => 'string', 'default' => '#007cba'),
            'layout' => array('type' => 'string', 'default' => 'center', 'enum' => array('center', 'left', 'right')),
            'className' => array('type' => 'string', 'default' => ''),
            'image1' => array('type' => 'number', 'default' => 0),
            'image1Url' => array('type' => 'string', 'default' => ''),
            'image2' => array('type' => 'number', 'default' => 0),
            'image2Url' => array('type' => 'string', 'default' => ''),
            'image3' => array('type' => 'number', 'default' => 0),
            'image3Url' => array('type' => 'string', 'default' => '')
        )
    ));

    // Register block style variations
    if (function_exists('register_block_style')) {
        register_block_style('okhub/highlight-section', array(
            'name' => 'compact',
            'label' => 'Compact'
        ));
        register_block_style('okhub/highlight-section', array(
            'name' => 'full-width',
            'label' => 'Full Width'
        ));
    }
}
add_action('init', 'highlight_section_register_block');

// Enqueue block editor assets (kept for compatibility)
function highlight_section_enqueue_block_editor_assets()
{
    wp_enqueue_script(
        'highlight-section-block',
        get_template_directory_uri() . '/assets/js/blocks/highlight-section.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
        '1.0.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'highlight_section_enqueue_block_editor_assets');

// Enqueue editor CSS and optionally load in admin post edit screens
function highlight_section_enqueue_admin_style($hook = '')
{
    $css_relative = '/template-parts/single/assets/highlight-section.css';
    $css_path = get_theme_file_path($css_relative);
    $css_uri = get_theme_file_uri($css_relative);
    $ver = null;
    if (file_exists($css_path)) {
        $ver = filemtime($css_path);
    }

    // Enqueue for block editor (also called via enqueue_block_editor_assets) to ensure styles in editor
    if (function_exists('is_block_editor')) {
        // only enqueue when editing posts/pages
        if (did_action('enqueue_block_editor_assets')) {
            wp_enqueue_style('highlight-section-editor-css', $css_uri, array(), $ver);
        }
    }

    // Additionally enqueue on admin post edit screens so Classic Editor or meta boxes also see styles
    if (in_array($hook, array('post.php', 'post-new.php'), true)) {
        wp_enqueue_style('highlight-section-admin-css', $css_uri, array(), $ver);
    }
}
add_action('admin_enqueue_scripts', 'highlight_section_enqueue_admin_style', 20);

/* ==================================================
 * Fullscreen Image Shortcode + Gutenberg Block
 * shortcode: [fullscreen_image image="ID or URL" overlay_text="..." link_url="..." class=""]
 * Block: okhub/fullscreen-image
 * ================================================== */

function fullscreen_image_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(array(
        'image' => '',
        'overlay_text' => '',
        'link_url' => '',
        'alt' => '',
        'caption' => '',
        'class' => '',
        'object_fit' => 'cover', // cover or contain
    ), $atts);

    $image = $atts['image'];
    $overlay_text = wp_kses_post($atts['overlay_text']);
    $link_url = esc_url($atts['link_url']);
    $alt_text = esc_attr($atts['alt']);
    $caption = wp_kses_post($atts['caption']);
    $class = esc_attr($atts['class']);
    $object_fit = in_array($atts['object_fit'], array('cover', 'contain')) ? $atts['object_fit'] : 'cover';

    if (empty($image)) return '';

    // Render image HTML (ID or URL)
    if (is_numeric($image)) {
        $img_html = wp_get_attachment_image(intval($image), 'full', false, array('class' => 'fullscreen-image__img', 'alt' => $alt_text));
    } else {
        $img_html = '<img src="' . esc_url($image) . '" class="fullscreen-image__img" alt="' . esc_attr($alt_text) . '" />';
    }

    ob_start();
?>
    <div class="fullscreen-image <?php echo $class; ?>" style="--fullscreen-fit: <?php echo esc_attr($object_fit); ?>;">
        <?php if (!empty($link_url)): ?><a class="fullscreen-image__link" href="<?php echo $link_url; ?>"><?php endif; ?>
            <div class="fullscreen-image__media"><?php echo $img_html; ?></div>
            <?php if (!empty($overlay_text)): ?>
                <div class="fullscreen-image__overlay">
                    <div class="fullscreen-image__overlay-inner"><?php echo $overlay_text; ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($caption)): ?>
                <figcaption class="fullscreen-image__caption"><?php echo $caption; ?></figcaption>
            <?php endif; ?>
            <?php if (!empty($link_url)): ?>
            </a><?php endif; ?>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('fullscreen_image', 'fullscreen_image_shortcode');

function fullscreen_image_register_block()
{
    wp_register_script(
        'fullscreen-image-block',
        get_template_directory_uri() . '/assets/js/blocks/fullscreen-image.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        '1.0.0',
        true
    );

    register_block_type('okhub/fullscreen-image', array(
        'editor_script' => 'fullscreen-image-block',
        'render_callback' => 'fullscreen_image_shortcode',
        'attributes' => array(
            'image' => array('type' => 'number', 'default' => 0),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'overlayText' => array('type' => 'string', 'default' => ''),
            'linkUrl' => array('type' => 'string', 'default' => ''),
            'className' => array('type' => 'string', 'default' => ''),
            'objectFit' => array('type' => 'string', 'default' => 'cover'),
            'alt' => array('type' => 'string', 'default' => ''),
            'caption' => array('type' => 'string', 'default' => ''),
        )
    ));

    // enqueue editor CSS for this block
    $css_rel = '/template-parts/single/assets/fullscreen-image.css';
    $css_path = get_theme_file_path($css_rel);
    $css_uri = get_theme_file_uri($css_rel);
    $ver = file_exists($css_path) ? filemtime($css_path) : null;
    wp_register_style('fullscreen-image-editor-css', $css_uri, array(), $ver);
    register_block_style('okhub/fullscreen-image', array('name' => 'default', 'label' => 'Default'));
}
add_action('init', 'fullscreen_image_register_block');

function fullscreen_image_enqueue_editor_assets()
{
    // enqueue block editor CSS when editing
    wp_enqueue_style('fullscreen-image-editor-css');
}
add_action('enqueue_block_editor_assets', 'fullscreen_image_enqueue_editor_assets');

// Also load fullscreen-image CSS on admin post edit screens (classic / meta boxes)
function fullscreen_image_enqueue_admin_style($hook = '')
{
    $css_rel = '/template-parts/single/assets/fullscreen-image.css';
    $css_path = get_theme_file_path($css_rel);
    $css_uri = get_theme_file_uri($css_rel);
    $ver = file_exists($css_path) ? filemtime($css_path) : null;

    if (in_array($hook, array('post.php', 'post-new.php'), true)) {
        wp_enqueue_style('fullscreen-image-admin-css', $css_uri, array(), $ver);
    }
}
add_action('admin_enqueue_scripts', 'fullscreen_image_enqueue_admin_style', 20);

/* ==================================================
 * Explore Tour Shortcode + Block (matches provided design)
 * shortcode: [explore_tour image="ID or URL" title="..." content="..." button_text="Explore" button_url="..." background="#f3efee" text_color="#6b0b3a"]
 * Block: okhub/explore-tour
 * ================================================== */

function explore_tour_shortcode($atts, $content = null)
{
    $raw_atts = $atts;
    $atts = shortcode_atts(array(
        'image' => '',
        'title' => 'Explore the tour in Cao Bang',
        'content' => 'Sign up for weekly travel inspiration straight to your inbox – all lovingly packed by our team of Travel Experts.',
        'button_text' => 'Explore',
        'button_url' => '',
        'background' => '#f3efee',
        'text_color' => '#6b0b3a',
        'class' => '',
        'alt' => '',
    ), $atts);

    // Normalize camelCase block attributes (block passes buttonText/buttonUrl)
    // Prefer values passed by the block (camelCase) over shortcode defaults
    if (!empty($raw_atts['buttonText'])) {
        $atts['button_text'] = $raw_atts['buttonText'];
    }
    if (!empty($raw_atts['buttonUrl'])) {
        $atts['button_url'] = $raw_atts['buttonUrl'];
    }

    $image = $atts['image'];
    $title = esc_html($atts['title']);
    $content_html = wp_kses_post($atts['content']);
    $button_text = esc_html($atts['button_text']);
    $button_url = esc_url($atts['button_url']);
    $background = esc_attr($atts['background']);
    $text_color = esc_attr($atts['text_color']);
    $class = esc_attr($atts['class']);
    $alt = esc_attr($atts['alt']);

    // Render image (ID or URL)
    if (!empty($image)) {
        if (is_numeric($image)) {
            $img_html = wp_get_attachment_image(intval($image), 'large', false, array('class' => 'explore-image', 'alt' => $alt));
        } else {
            $img_html = '<img src="' . esc_url($image) . '" class="explore-image" alt="' . esc_attr($alt) . '" />';
        }
    } else {
        $img_html = '';
    }

    $inline = sprintf('--explore-bg:%s;--explore-text:%s', $background, $text_color);

    ob_start();
?>
    <section class="explore-tour <?php echo $class; ?>" style="<?php echo $inline; ?>">
        <div class="explore-container">
            <div class="explore-grid">
                <div class="explore-left">
                    <?php if (!empty($title)): ?>
                        <p class="explore-title"><?php echo $title; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($content_html)): ?>
                        <div class="explore-desc"><?php echo $content_html; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($button_text) && !empty($button_url)): ?>
                        <div class="explore-cta">
                            <a class="compound-avian-button explore-button" href="<?php echo $button_url; ?>">
                                <div class="compound-avian-button__content">
                                    <span class="compound-avian-button__content-text"><?php echo $button_text; ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="explore-right">
                    <?php echo $img_html; ?>
                </div>
            </div>
        </div>
    </section>
<?php
    return ob_get_clean();
}
add_shortcode('explore_tour', 'explore_tour_shortcode');

function explore_tour_register_block()
{
    wp_register_script(
        'explore-tour-block',
        get_template_directory_uri() . '/assets/js/blocks/explore-tour.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-server-side-render'),
        '1.0.0',
        true
    );

    register_block_type('okhub/explore-tour', array(
        'editor_script' => 'explore-tour-block',
        'render_callback' => 'explore_tour_shortcode',
        'attributes' => array(
            'image' => array('type' => 'number', 'default' => 0),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => 'Explore the tour in Cao Bang'),
            'content' => array('type' => 'string', 'default' => 'Sign up for weekly travel inspiration straight to your inbox – all lovingly packed by our team of Travel Experts.'),
            'buttonText' => array('type' => 'string', 'default' => 'Explore'),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'background' => array('type' => 'string', 'default' => '#f3efee'),
            'textColor' => array('type' => 'string', 'default' => '#6b0b3a'),
            'className' => array('type' => 'string', 'default' => ''),
            'alt' => array('type' => 'string', 'default' => ''),
        )
    ));

    // register editor CSS for block
    $css_rel = '/template-parts/single/assets/explore-tour.css';
    $css_path = get_theme_file_path($css_rel);
    $css_uri = get_theme_file_uri($css_rel);
    $ver = file_exists($css_path) ? filemtime($css_path) : null;
    wp_register_style('explore-tour-editor-css', $css_uri, array(), $ver);
}
add_action('init', 'explore_tour_register_block');

function explore_tour_enqueue_editor_assets()
{
    wp_enqueue_style('explore-tour-editor-css');
    wp_enqueue_script('explore-tour-block');
}
add_action('enqueue_block_editor_assets', 'explore_tour_enqueue_editor_assets');

// also enqueue on admin edit screens
function explore_tour_enqueue_admin_style($hook = '')
{
    $css_rel = '/template-parts/single/assets/explore-tour.css';
    $css_path = get_theme_file_path($css_rel);
    $css_uri = get_theme_file_uri($css_rel);
    $ver = file_exists($css_path) ? filemtime($css_path) : null;
    if (in_array($hook, array('post.php', 'post-new.php'), true)) {
        wp_enqueue_style('explore-tour-admin-css', $css_uri, array(), $ver);
    }
}
add_action('admin_enqueue_scripts', 'explore_tour_enqueue_admin_style', 20);

// Enqueue admin script to disable block drag in Gutenberg editor (blocks movement only)
function okhub_disable_block_drag_enqueue()
{
    $script_rel = '/assets/js/admin/disable-block-drag.js';
    $script_path = get_theme_file_path($script_rel);
    $script_uri = get_theme_file_uri($script_rel);
    $ver = file_exists($script_path) ? filemtime($script_path) : null;
    wp_register_script('okhub-disable-block-drag', $script_uri, array(), $ver, true);
    wp_enqueue_script('okhub-disable-block-drag');
}
add_action('enqueue_block_editor_assets', 'okhub_disable_block_drag_enqueue');
