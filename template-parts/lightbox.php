<?php
// Récupérer toutes les photos
$all_photos = get_posts(array(
    'post_type' => 'photo',
    'numberposts' => -1, // Récupérer toutes les photos
    'orderby' => 'date',
    'order' => 'ASC'
));

// Trouver l'index de la photo actuelle
$current_index = 0; // Par défaut à 0
$total_photos = count($all_photos); // Nombre total de photos

foreach ($all_photos as $index => $photo) {
    if ($photo->ID === get_the_ID()) {
        $current_index = $index; // Met à jour l'index actuel
        break; // Sortir de la boucle une fois trouvé
    }
}

// Déterminer l'index de la photo suivante et précédente
$next_index = ($current_index + 1) % $total_photos; // Retour à la première photo si c'est la dernière
$prev_index = ($current_index - 1 + $total_photos) % $total_photos; // Précédent, avec retour à la dernière photo

// Récupérer les détails de la photo actuelle
$current_photo = $all_photos[$current_index];
$current_title = get_the_title($current_photo->ID);
$current_category = get_the_terms($current_photo->ID, 'categorie'); // Récupérer les catégories
$current_reference = get_field('reference', $current_photo->ID); // Récupérer le champ personnalisé "référence"
?>

<!-- Lightbox -->
<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <div class="lightbox-content">
    <!-- Bouton de fermeture -->
    <div class="lightbox-close" onclick="closeLightbox()">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-icon.png" alt="Fermer" class="close-icon">
    </div>

    <!-- Contenu de la lightbox avec navigation et image -->
    <div class="lightbox-inner">
        <!-- Bouton Précédent -->
        <button id="prev-btn" class="nav-btn" 
            data-media-id="<?php echo get_post_thumbnail_id($all_photos[$prev_index]->ID); ?>" 
            onclick="handleLightbox(<?php echo get_post_thumbnail_id($all_photos[$prev_index]->ID); ?>, event, 'prev')">
            &larr; Précédente
        </button>

        <!-- Image et informations -->
        <div class="lightbox-image-container">
            <img id="lightbox-image" src="<?php echo get_the_post_thumbnail_url($current_photo->ID, 'large'); ?>" alt="Photo" class="lightbox-image">
            <div class="lightbox-info">
                <div id="lightbox-category" class="info-category">
                    Catégorie : <?php
                    if ($current_category) {
                        foreach ($current_category as $category) {
                            echo esc_html($category->name) . ' ';
                        }
                    }
                    ?>
                </div>
                <div id="lightbox-reference" class="info-reference">Référence : <?php echo esc_html($current_reference); ?></div>
            </div>
        </div>

        <!-- Bouton Suivant -->
        <button id="next-btn" class="nav-btn" 
            data-media-id="<?php echo get_post_thumbnail_id($all_photos[$next_index]->ID); ?>" 
            onclick="handleLightbox(<?php echo get_post_thumbnail_id($all_photos[$next_index]->ID); ?>, event, 'next')">
            Suivante &rarr;
        </button>
    </div>
  </div>
</div>
