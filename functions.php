<?php


// Enqueue des styles et scripts
/*function nathalie_mota_enqueue_scripts() {

    // Enqueue CSS depuis le dossier assets/css
    wp_enqueue_style( 'nathalie-mota-style', get_template_directory_uri() . '/assets/scss/main.css' );

    // Enqueue jQuery
    wp_enqueue_script( 'jquery' ); // S'assurer que jQuery est chargé

    // Enqueue JavaScript
    wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts' ); // Ajout de la fonction à l'action wp_enqueue_scripts*/



function nathalie_mota_enqueue_scripts() {
    // Enqueue CSS depuis le dossier assets/css
    wp_enqueue_style('nathalie-mota-style', get_template_directory_uri() . '/assets/scss/main.css');

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue JavaScript (inclut l'URL Ajax)
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);

    // Localiser le script avec l'URL Ajax
    wp_localize_script('custom-js', 'wp_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts');

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

function filter_photos() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
    );

    if ($category) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categorie_taxonomie', // Remplacer par ta taxonomie
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }

    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format_taxonomie', // Remplacer par ta taxonomie
            'field' => 'slug',
            'terms' => $format,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/photo_block');
        endwhile;
    else :
        echo '<p>Aucune photo trouvée.</p>';
    endif;

    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');



function filter_and_sort_photos() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'desc';

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => $sort,
    );

    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/photo_block');
        endwhile;
    else :
        echo '<p>Aucune photo trouvée.</p>';
    endif;

    wp_die();
}
add_action('wp_ajax_filter_and_sort_photos', 'filter_and_sort_photos');
add_action('wp_ajax_nopriv_filter_and_sort_photos', 'filter_and_sort_photos');

function ajouter_termes_par_defaut_si_absents() {
    // Catégories par défaut
    $categories = array('Réception', 'Mariage', 'Concert', 'Télévision');
    foreach ($categories as $categorie) {
        if (!term_exists($categorie, 'categories')) {
            wp_insert_term($categorie, 'categories');
        }
    }

    // Formats par défaut
    $formats = array('paysage', 'portrait');
    foreach ($formats as $format) {
        if (!term_exists($format, 'formats')) {
            wp_insert_term($format, 'formats');
        }
    }
}
add_action('init', 'ajouter_termes_par_defaut_si_absents');


add_filter('rest_prepare_attachment', function ($response, $post, $request) {
    // Ajoute l'ID du post parent au champ `post`
    $parent_id = $post->post_parent;

    if ($parent_id) {
        $response->data['post'] = $parent_id;
    } else {
        $response->data['post'] = null;
    }

    return $response;
}, 10, 3);


add_action('save_post', function ($post_id) {
    // Vérifie si une image mise en avant est définie
    $thumbnail_id = get_post_thumbnail_id($post_id);

    if ($thumbnail_id) {
        // Met à jour la relation entre le média et l'article
        wp_update_post(array(
            'ID' => $thumbnail_id,
            'post_parent' => $post_id,
        ));
    }
});



