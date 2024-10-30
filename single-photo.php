<?php
/**
 * Template pour afficher tous les articles individuels de type "photo"
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Votre_Theme
 * @since Votre_Theme 1.0
 */

// Inclut le header de votre site
get_header(); 

// Début de la boucle principale
while ( have_posts() ) :
    the_post(); // Prépare l'article actuel pour l'affichage

    // Affiche le titre de l'article
    echo '<h1>' . get_the_title() . '</h1>';

    // Affiche les métadonnées de l'article (catégories, format, date, etc.)
    echo '<div class="meta-info">';
    echo '<span>Catégorie : ' . get_the_category_list(', ') . '</span>'; // Liste des catégories
    echo '<span>Format : ' . get_post_format() . '</span>'; // Format de l'article
    echo '<span>Année : ' . get_the_date('Y') . '</span>'; // Date de publication
    echo '</div>';


endwhile; // Fin de la boucle principale

// Inclut le footer de votre site
get_footer(); 
