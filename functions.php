<?php


// Enqueue des styles et scripts
function nathalie_mota_enqueue_scripts() {

    // Enqueue CSS depuis le dossier assets/css
    wp_enqueue_style( 'nathalie-mota-style', get_template_directory_uri() . '/assets/scss/main.css' );

    // Enqueue jQuery
    wp_enqueue_script( 'jquery' ); // S'assurer que jQuery est chargé

    // Enqueue JavaScript
    wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts' ); // Ajout de la fonction à l'action wp_enqueue_scripts



// Fonction pour ajouter le support des logos personnalisés
function nathalie_mota_setup() {
    // Support pour le logo
    add_theme_support( 'custom-logo', array(
        'flex-height' => true, // Permet de redimensionner la hauteur
        'flex-width'  => true, // Permet de redimensionner la largeur
    ) );
}
add_action( 'after_setup_theme', 'nathalie_mota_setup' );

// Enregistrement des emplacements de menus
function nathalie_mota_register_menus() {
    register_nav_menus( array(
        'primary' => __( 'Menu Principal' ), // Ajout de l'emplacement du menu principal
        'footer'  => __( 'Menu Footer' ), // Ajout de l'emplacement du menu footer
    ) );
}
add_action( 'init', 'nathalie_mota_register_menus' );


function nathalie_mota_get_icon_svg( $icon, $size = 24 ) {
    // Remplacez par le SVG de votre choix ou par une logique d'affichage d'icône
    return '<svg width="' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" ...>...</svg>';
}


// Charger le script Ajax et définir l’URL de l’API Ajax de WordPress
function enqueue_ajax_script() {
    wp_enqueue_script('load-more', get_template_directory_uri() . '/scripts.js', array('jquery'), null, true);
    wp_localize_script('load-more', 'wp_data', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_script');




function load_more_photos() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $page,
    );

    $photo_query = new WP_Query($args);

    if ($photo_query->have_posts()) :
        while ($photo_query->have_posts()) : $photo_query->the_post();
            get_template_part('template-parts/photo_block');
        endwhile;
    endif;

    wp_die();
}
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');


