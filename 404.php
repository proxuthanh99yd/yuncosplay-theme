<?php
// 404.php — WordPress tự route request is_404() vào đây.
// header.php mở <main>, footer.php đóng lại + render CTA/footer.
get_header();
get_template_part('template-parts/404/index');
get_footer();
