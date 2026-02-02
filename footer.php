</main>
<?php get_template_part('template-parts/components/cta/index'); ?>
<?php get_template_part('template-parts/layouts/footer/index'); ?>
<?php 
$show_reviews = is_front_page() || is_singular('tour') || is_page_template('page-contact.php');
if($show_reviews) {
	get_template_part('template-parts/components/popup-review/index'); 
}
?>
<?php get_template_part('template-parts/components/popup-destination/index'); ?>
<?php wp_footer(); ?>

</body>

</html>