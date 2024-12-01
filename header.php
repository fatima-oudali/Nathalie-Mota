<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="container">
        <!-- Logo -->
        <div class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php 
                if ( has_custom_logo() ) {
                    the_custom_logo(); // Affiche le logo personnalisé
                } else {
                    echo '<img src="' . get_template_directory_uri() . '/assets/images/Logo.png" alt="Logo de Nathalie Mota">'; // Affiche le logo par défaut
                }
                ?>
            </a>
        </div>
    
        <!-- Bouton hamburger pour menu mobile -->

        <!-- Menu de navigation -->
        <nav>
            <?php wp_nav_menu( array( 
                'theme_location' => 'primary', 
                'menu_class' => 'main-navigation' 
            )); ?>
            <a class="menu-toggle">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </a>
        </nav>


</header>
