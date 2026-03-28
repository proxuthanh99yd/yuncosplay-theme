<?php
$is_mobile = function_exists('isMobileDevice') ? isMobileDevice() : wp_is_mobile();
if (! $is_mobile) {
	get_template_part('template-parts/layouts/header/header-desktop/index');
} else {
	get_template_part('template-parts/layouts/header/header-mobile/index');
}
?>