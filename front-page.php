<?php
/* Template Name: Page d'Accueil Personnalisée */

get_header(); 
?>

<main class="home-page">
    <!-- Section Hero -->
    <section class="hero">
        <?php
        // Récupérer une photo aléatoire du catalogue
        $images = get_posts([
            'post_type' => 'photo', // Assurez-vous que le type est correct
            'posts_per_page' => -1,
        ]);

        if (!empty($images)) {
            // Sélectionner une image aléatoire
            $random_image = $images[array_rand($images)];
            
            // Récupérer l'URL de l'image en taille d'origine
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($random_image->ID), 'full');
            
            if ($image_data) {
                $image_url = $image_data[0];
                // Afficher l'image en arrière-plan
                echo '<div class="hero-background" style="background-image: url(' . esc_url($image_url) . ');"></div>';
            } else {
                echo '<p>Aucune image mise en avant trouvée pour cette photo.</p>';
            }
        } else {
            echo '<p>Aucune image disponible dans le catalogue.</p>';
        }
        ?>
        
        <!--<h1>Bienvenue sur notre site</h1>-->
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/titre-header.png" alt="Titre photographe event">
    </section>
    <section class="related-photos">
        <div class="related-images">
        
            <?php
            // Requête pour récupérer toutes les photos du type de contenu personnalisé 'photo'
            $args = array(
                'post_type' => 'photo',
                'posts_per_page' => 8, // Limiter à 8 photos par page
                'paged' => $paged, // Ajouter la gestion de la pagination
            );

            $photo_query = new WP_Query($args);

            if ($photo_query->have_posts()) :
                while ($photo_query->have_posts()) : $photo_query->the_post(); ?>
                    <!-- Inclure le template part pour chaque photo -->
                    <?php get_template_part('template-parts/photo_block');?>              
                <?php endwhile;
                wp_reset_postdata(); // Réinitialiser les données de la requête
            else : ?>
                <p>Aucune photo disponible.</p>
            <?php endif; ?>                
        </div>
        <div class="load-more">
            <button id="load-more-button">Charger plus</button>
        </div>
</section>

</main>

<?php get_footer(); ?>
