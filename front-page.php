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
</main>

<?php get_footer(); ?>
