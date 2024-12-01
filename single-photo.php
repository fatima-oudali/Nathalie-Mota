<?php
/**
 * Template pour afficher un article individuel de type "photo"
 *
 * @package Votre_Theme
 * @since Votre_Theme 1.0
 */

// Inclut le header du thème
get_header(); 

// Boucle principale WordPress pour charger l'article actuel
while ( have_posts() ) :
    the_post();
?>

    <!-- Partie 1 : Zone de contenu -->
        <!-- Détails de l'article -->
        <section class="article-details">
            <div class="article-info">
                <!-- Affichage des informations de l'article (Titre, Référence, Catégorie, etc.) -->
                <h2><?php the_title(); ?></h2>
                <p class="reference description">Référence : <?php the_field('reference'); ?></p>
                <p class="category description">Catégorie : 
                    <?php 
                    $categories = get_the_terms(get_the_ID(), 'categorie');
                    if ($categories) {
                        foreach ($categories as $category) {
                            echo esc_html($category->name) . ' ';
                        }
                    }
                    ?>
                </p>
                <p class="format description">Format : 
                    <?php 
                    $formats = get_the_terms(get_the_ID(), 'format');
                    if ($formats) {
                        foreach ($formats as $format) {
                            echo esc_html($format->name) . ' ';
                        }
                    }
                    ?>
                </p>
                <p class="type description">Type : <?php the_field('type'); ?></p>
                <p class="year description">Année : <?php echo get_the_date('Y'); ?></p>
            </div>
            <div class="article-image">
                <!-- Affichage de l'image principale de l'article -->
                <?php 
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail('large'); 
                }
                ?>
            </div>
        </section>

        <!-- Contact et navigation -->
        <section class="interaction-section">
            <div class="contact-call">
                <p>Cette photo vous intéresse ?</p>
                <button id="contactButton" class="contact-btn" data-ref="<?php echo esc_attr(get_field('reference')); ?>">Contact</button>
            </div>

            <!-- Navigation entre les photos avec une seule image d'aperçu au-dessus des flèches -->
            <div class="photo-navigation">
                <?php
                // Récupère toutes les photos
                $all_photos = get_posts(array(
                    'post_type' => 'photo',
                    'numberposts' => -1, // Récupérer toutes les photos
                    'orderby' => 'date',
                    'order' => 'ASC'
                ));

                // Initialiser les index
                $current_index = 0; // Par défaut à 0
                $total_photos = count($all_photos); // Nombre total de photos

                // Trouver l'index de la photo actuelle
                foreach ($all_photos as $index => $photo) {
                    if ($photo->ID === get_the_ID()) {
                        $current_index = $index; // Met à jour l'index actuel
                        break; // Sortir de la boucle une fois trouvé
                    }
                }

                // Déterminer l'index de la photo suivante
                $next_index = ($current_index + 1) % $total_photos; // Retour à la première photo si c'est la dernière
                $prev_index = ($current_index - 1 + $total_photos) % $total_photos; // Précédent, avec retour à la dernière photo
                ?>

                <!-- Image d'aperçu de la prochaine photo -->
                <div class="next-photo-thumbnail">
                    <?php echo get_the_post_thumbnail($all_photos[$next_index]->ID, 'thumbnail'); ?>
                </div>
                <div class="arrow">
                    <!-- Flèche gauche pour naviguer vers l'article précédent -->
                    <a href="<?php echo get_permalink($all_photos[$prev_index]->ID); ?>" class="prev-photo">
                        <span >←</span>
                    </a>

                    <!-- Flèche droite pour naviguer vers l'article suivant -->
                    <a href="<?php echo get_permalink($all_photos[$next_index]->ID); ?>" class="next-photo">
                        <span>→</span>
                    </a>
                </div>

            </div>
        </section>
    <!-- Partie 2 : Photos apparentées -->
    <section class="related-photos">
        <h3>Vous aimerez AUSSI</h3>
        <div class="related-images">
            <?php
            // Récupérer les catégories de l'article actuel
            $categories = get_the_terms(get_the_ID(), 'categorie');
            $category_ids = [];

            if ($categories && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $category_ids[] = $category->term_id;
                }
            }

            // Requête pour les photos apparentées de la même catégorie
            $related_photos = new WP_Query(array(
                'post_type' => 'photo',
                'posts_per_page' => 2, // Nombre de photos à afficher
                'post__not_in' => array(get_the_ID()), // Exclure l'article actuel
                'tax_query' => array(
                    array(
                        'taxonomy' => 'categorie',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ),
                ),
                'orderby' => 'rand', // Ajoute un ordre aléatoire pour varier les photos
            ));

            // Vérifier si des articles sont trouvés et les afficher
            if ($related_photos->have_posts()) :
                while ($related_photos->have_posts()) : $related_photos->the_post(); ?>
                 <?php get_template_part('template-parts/photo_block');?>
                <?php endwhile;
                wp_reset_postdata(); // Réinitialiser les données de la requête
            else : ?>
                <p>Aucune photo apparentée trouvée.</p>
            <?php endif; ?>
        </div>
    </section>    

<?php
endwhile; // Fin de la boucle principale

// Inclut le footer du thème
get_footer();
