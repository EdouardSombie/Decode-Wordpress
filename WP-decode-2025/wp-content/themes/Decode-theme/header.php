<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    /* déclenchement du hook wp_head */
    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
    <header class="site-header">
        <?php
        wp_nav_menu([
            'theme_location' => 'main-menu',
            'container_class'     => 'main-navigation',
            'container'      => 'nav'
        ]);
        ?>
    </header>