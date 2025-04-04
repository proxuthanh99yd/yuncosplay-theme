<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php if (!wp_is_mobile()): ?>
        <?php get_template_part('template-parts/header/header'); ?>
    <?php else: ?>
        <?php get_template_part('template-parts/header/header-mobile'); ?>
    <?php endif; ?>
    <main id="main" class="main">