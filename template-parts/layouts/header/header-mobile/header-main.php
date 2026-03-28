<?php
// Field Header (fallback to avoid PHP warnings when ACF option is empty)
$header = function_exists('get_field') ? get_field('header', 'option') : [];
$header = is_array( $header ) ? $header : [];
$header_logo = $header['logo_image'] ?? null;
?>

<div class="header-main">
    <div class="header-main-left">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-main__logo">
            <?php if ( ! empty( $header_logo ) ) : ?>
                <?php echo wp_get_attachment_image( $header_logo, 'full', false, array( 'class' => 'header-main__logo-image' ) ); ?>
            <?php endif; ?>
        </a>
    </div>
    <div class="header-main-right">
        <div class="header-main__search-input-wrapper">
            <input class="header-main__search-input" type="text" placeholder="Nhập từ khoá tìm kiếm"/>
            <button class="header-main__search-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M7.66665 13.9999C11.1644 13.9999 14 11.1644 14 7.66659C14 4.16878 11.1644 1.33325 7.66665 1.33325C4.16884 1.33325 1.33331 4.16878 1.33331 7.66659C1.33331 11.1644 4.16884 13.9999 7.66665 13.9999Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14.6666 14.6666L13.3333 13.3333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <!-- <div class="header-main__right-divider"></div> -->
        <button class="header-main__menu-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
            <path d="M20.4635 9.69508C22.0486 9.69508 23.3335 8.41014 23.3335 6.82508C23.3335 5.24002 22.0486 3.95508 20.4635 3.95508C18.8784 3.95508 17.5935 5.24002 17.5935 6.82508C17.5935 8.41014 18.8784 9.69508 20.4635 9.69508Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M7.53676 9.69508C9.12181 9.69508 10.4067 8.41014 10.4067 6.82508C10.4067 5.24002 9.12181 3.95508 7.53676 3.95508C5.9517 3.95508 4.66675 5.24002 4.66675 6.82508C4.66675 8.41014 5.9517 9.69508 7.53676 9.69508Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M20.4635 24.0447C22.0486 24.0447 23.3335 22.7597 23.3335 21.1747C23.3335 19.5896 22.0486 18.3047 20.4635 18.3047C18.8784 18.3047 17.5935 19.5896 17.5935 21.1747C17.5935 22.7597 18.8784 24.0447 20.4635 24.0447Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M7.53676 24.0447C9.12181 24.0447 10.4067 22.7597 10.4067 21.1747C10.4067 19.5896 9.12181 18.3047 7.53676 18.3047C5.9517 18.3047 4.66675 19.5896 4.66675 21.1747C4.66675 22.7597 5.9517 24.0447 7.53676 24.0447Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</div>