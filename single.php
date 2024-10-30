<?php
/**
 * Template pour afficher tous les articles individuels
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Nathalie_Mota
 * @since Nathalie Mota 1.0
 */

get_header(); // Inclut le header du site

/* Début de la boucle principale */
while ( have_posts() ) :
    the_post(); // Prépare l'article actuel pour l'affichage

    // Charge le template pour le contenu de l'article
    get_template_part( 'template-parts/content/content-single' );

    // Affiche la navigation vers l'article parent si l'article actuel est une pièce jointe
    if ( is_attachment() ) {
        the_post_navigation(
            array(
                'prev_text' => sprintf(
                    __( '<span class="meta-nav">Publié dans</span><span class="post-title">%s</span>', 'nathalie_mota' ),
                    '%title'
                ),
            )
        );
    }

    // Affiche la section des commentaires si les commentaires sont ouverts ou s'il y a au moins un commentaire
    if ( comments_open() || get_comments_number() ) {
        comments_template(); // Charge le template des commentaires
    }

    // Définit les icônes et libellés pour la navigation "article précédent / suivant"
    $next_icon = is_rtl() ? nathalie_mota_get_icon_svg( 'ui', 'arrow_left' ) : nathalie_mota_get_icon_svg( 'ui', 'arrow_right' );
    $prev_icon = is_rtl() ? nathalie_mota_get_icon_svg( 'ui', 'arrow_right' ) : nathalie_mota_get_icon_svg( 'ui', 'arrow_left' );

    $next_label     = esc_html__( 'Article suivant', 'nathalie_mota' );
    $previous_label = esc_html__( 'Article précédent', 'nathalie_mota' );

    // Affiche la navigation entre les articles
    the_post_navigation(
        array(
            'next_text' => '<p class="meta-nav">' . $next_label . $next_icon . '</p><p class="post-title">%title</p>',
            'prev_text' => '<p class="meta-nav">' . $prev_icon . $previous_label . '</p><p class="post-title">%title</p>',
        )
    );
endwhile; // Fin de la boucle principale

get_footer(); // Inclut le footer du site
