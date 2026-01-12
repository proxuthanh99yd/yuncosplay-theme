<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    get_template_part('template-parts/layouts/header/index');
    ?>

    <main id="main" class="main">