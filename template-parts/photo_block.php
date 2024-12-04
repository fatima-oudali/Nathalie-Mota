<div class="related-photo">
    <?php 
    if ( has_post_thumbnail() ) {
        the_post_thumbnail('large', [
            'class' => 'photo-thumbnail', 
            'onclick' => 'handleLightbox(' . esc_js(get_post_thumbnail_id(get_the_ID())) . ', event)'
        ]); 
    }
    ?>
    <div class="photo-overlay">
        <!-- Icône "œil" au centre -->
        <div class="icon-center" onclick="goToSinglePage(<?php echo get_the_ID(); ?>, event)">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon_eye.png" alt="Voir la photo" class="icon-eye">
        </div>

        <!-- Icône "plein écran" en haut à droite -->
        <div class="icon-top-right" onclick="handleLightbox(<?php echo esc_js(get_post_thumbnail_id(get_the_ID())); ?>, event)">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon_fullscreen.png" alt="Voir en plein écran" class="icon-fullscreen">
</div>


        <!-- Informations supplémentaires -->
        <div class="info-bottom-right">
            <?php
            $categories = get_the_terms(get_the_ID(), 'categorie');
            if ($categories && !is_wp_error($categories)) {
                echo esc_html($categories[0]->name); // Affiche la première catégorie
            } else {
                echo 'Non classé';
            }
            ?>
        </div>
        <div class="info-bottom-left">
            <?php the_title(); ?>
        </div>
    </div>
</div>
