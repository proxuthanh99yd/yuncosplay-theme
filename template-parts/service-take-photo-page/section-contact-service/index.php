<?php
$backgound_pc  = 10244;
$background_mb = 10246;

$contact_acf = get_field('contact');

if (!is_array($contact_acf)) {
    $contact_acf = [];
}

$title       = $contact_acf['title'] ?? '';
$description = $contact_acf['description'] ?? '';
$link_socials = function_exists('get_field') ? get_field('cta', 'option') : [];
$link_zalo  = $link_socials['link_zalo'] ?? null;
$link_mess  = $link_socials['link_messenger'] ?? null;
$link_phone = $link_socials['link_hotline'] ?? null;

$is_valid_link = function ($link) {
    return is_array($link) && !empty($link['url']);
};

$get_link_target = function ($link) {
    return !empty($link['target']) ? $link['target'] : '_self';
};

$get_link_rel = function ($target) {
    return $target === '_blank' ? 'noopener noreferrer' : '';
};
?>

<section class="section-contact-service">
    <div class="container-left">
        <?php if (!empty($title)) : ?>
        <h2><?= wp_kses_post($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($description)) : ?>
        <p><?= wp_kses_post($description); ?></p>
        <?php endif; ?>

        <?php if ($is_valid_link($link_zalo) || $is_valid_link($link_mess) || $is_valid_link($link_phone)) : ?>
        <div class="container-left__social">

            <?php if ($is_valid_link($link_zalo)) : ?>
            <?php
                    $target = $get_link_target($link_zalo);
                    $rel    = $get_link_rel($target);
                    ?>
            <a href="<?= esc_url($link_zalo['url']); ?>" target="<?= esc_attr($target); ?>"
                <?php if ($rel) : ?>rel="<?= esc_attr($rel); ?>" <?php endif; ?> class="container-left__social--item"
                aria-label="Zalo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="10" viewBox="0 0 24 10" fill="none">
                    <path
                        d="M5.15411 1.67127L1.67917 7.36878V7.4954H5.15411V9.01473H0V7.4954L3.49826 1.79788V1.67127H0V0.151933H5.15411V1.67127Z"
                        fill="#680103" />
                    <path
                        d="M5.48362 9.01473V8.73619L7.2794 0.151933L9.98472 0.164594L11.7922 8.73619V9.01473H10.2996L9.84479 6.58379H7.43099L6.97621 9.01473H5.48362ZM7.64088 5.21639H9.62323L8.95856 1.62063H8.31721L7.64088 5.21639Z"
                        fill="#680103" />
                    <path d="M12.5215 0.151933H13.9558V7.59669H16.3579V9.01473H12.5215V0.151933Z" fill="#680103" />
                    <path
                        d="M16.5001 4.58333C16.5001 3.51136 16.6594 2.63774 16.9782 1.96248C17.2969 1.28722 17.7128 0.793431 18.2259 0.481123C18.7467 0.160374 19.3103 0 19.9167 0C20.5231 0 21.0828 0.160374 21.5959 0.481123C22.1167 0.793431 22.5365 1.28722 22.8552 1.96248C23.174 2.63774 23.3333 3.51136 23.3333 4.58333C23.3333 5.65531 23.174 6.52893 22.8552 7.20419C22.5365 7.87945 22.1167 8.37746 21.5959 8.6982C21.0828 9.01051 20.5231 9.16667 19.9167 9.16667C19.3103 9.16667 18.7467 9.01051 18.2259 8.6982C17.7128 8.37746 17.2969 7.87945 16.9782 7.20419C16.6594 6.52893 16.5001 5.65531 16.5001 4.58333ZM17.981 4.58333C17.981 5.27548 18.0665 5.84945 18.2375 6.30525C18.4086 6.76105 18.6379 7.10712 18.9255 7.34346C19.2209 7.57136 19.5474 7.68531 19.905 7.68531C20.2626 7.68531 20.5891 7.57136 20.8845 7.34346C21.1877 7.10712 21.4248 6.76105 21.5959 6.30525C21.7747 5.84945 21.8641 5.27548 21.8641 4.58333C21.8641 3.88275 21.7747 3.30456 21.5959 2.84876C21.4171 2.39296 21.18 2.05111 20.8845 1.8232C20.5891 1.5953 20.2665 1.48135 19.9167 1.48135C19.5513 1.48135 19.2209 1.59952 18.9255 1.83587C18.6379 2.06377 18.4086 2.40562 18.2375 2.86142C18.0665 3.31722 17.981 3.89119 17.981 4.58333Z"
                        fill="#680103" />
                </svg>
            </a>
            <?php endif; ?>

            <?php if ($is_valid_link($link_mess)) : ?>
            <?php
                    $target = $get_link_target($link_mess);
                    $rel    = $get_link_rel($target);
                    ?>
            <a href="<?= esc_url($link_mess['url']); ?>" target="<?= esc_attr($target); ?>"
                <?php if ($rel) : ?>rel="<?= esc_attr($rel); ?>" <?php endif; ?> class="container-left__social--item"
                aria-label="Messenger">
                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33" fill="none">
                    <path
                        d="M16.3333 1.30859C8.03648 1.30859 1.30664 7.58161 1.30664 15.3553C1.30664 19.4718 3.20029 23.3254 6.53331 26.0051V31.7549L12.153 28.8149C13.5235 29.2054 14.8965 29.3356 16.3333 29.3356C24.6301 29.3356 31.36 23.0651 31.36 15.2889C31.36 7.58161 24.6301 1.30859 16.3333 1.30859ZM17.8365 19.9949L14.0466 15.9422L6.99013 19.9286L14.8301 11.6318L18.6863 15.4854L25.5463 11.6318L17.8365 19.9949Z"
                        fill="#680103" />
                </svg>
            </a>
            <?php endif; ?>

            <?php if ($is_valid_link($link_phone)) : ?>
            <?php
                    $target = $get_link_target($link_phone);
                    $rel    = $get_link_rel($target);
                    ?>
            <a href="<?= esc_url($link_phone['url']); ?>" target="<?= esc_attr($target); ?>"
                <?php if ($rel) : ?>rel="<?= esc_attr($rel); ?>" <?php endif; ?> class="container-left__social--item"
                aria-label="Phone">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path
                        d="M23.8818 18.98C23.1717 18.3844 19.0068 15.7471 18.3146 15.8681C17.9896 15.9258 17.7409 16.2029 17.0755 16.9967C16.7678 17.3874 16.4298 17.7533 16.0647 18.0911C15.3959 17.9296 14.7484 17.6896 14.1359 17.3761C11.7335 16.2065 9.79276 14.2652 8.62388 11.8625C8.31044 11.2499 8.07045 10.6025 7.90887 9.93363C8.24665 9.56857 8.61259 9.23061 9.00331 8.92288C9.79631 8.25744 10.0742 8.01044 10.1319 7.68381C10.2529 6.98994 7.61313 2.82669 7.02 2.11656C6.77138 1.82244 6.5455 1.625 6.25625 1.625C5.41775 1.625 1.625 6.31475 1.625 6.9225C1.625 6.97206 1.70625 11.8544 7.87231 18.1277C14.1456 24.2938 19.0279 24.375 19.0775 24.375C19.6852 24.375 24.375 20.5822 24.375 19.7437C24.375 19.4545 24.1776 19.2286 23.8818 18.98ZM18.6875 12.1875H20.3125C20.3106 10.4642 19.6251 8.81201 18.4066 7.59344C17.188 6.37488 15.5358 5.68944 13.8125 5.6875V7.3125C15.105 7.31379 16.3443 7.82782 17.2582 8.74178C18.1722 9.65574 18.6862 10.895 18.6875 12.1875Z"
                        fill="#680103" />
                    <path
                        d="M22.75 12.1875H24.375C24.3718 9.38714 23.2579 6.7024 21.2778 4.72225C19.2976 2.74209 16.6129 1.62823 13.8125 1.625V3.25C16.182 3.2528 18.4537 4.19532 20.1292 5.87082C21.8047 7.54632 22.7472 9.81799 22.75 12.1875Z"
                        fill="#680103" />
                </svg>
            </a>
            <?php endif; ?>

        </div>
        <?php endif; ?>
    </div>

    <?php get_template_part('template-parts/service-take-photo-page/section-contact-form/index'); ?>
</section>