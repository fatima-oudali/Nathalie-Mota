<?php

// Fonction pour charger les styles et scripts nécessaires au site
function nathalie_mota_enqueue_scripts() {
    // Chargement du fichier CSS principal depuis le dossier 'assets/scss'
    wp_enqueue_style('nathalie-mota-style', get_template_directory_uri() . '/assets/scss/main.css');
    
    // Chargement de jQuery (librairie JavaScript incluse avec WordPress)
    wp_enqueue_script('jquery');

    // Chargement du fichier JavaScript principal (inclut l'URL Ajax pour les requêtes AJAX)
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);

    // Localiser le script pour inclure l'URL Ajax dans le JavaScript
    wp_localize_script('custom-js', 'wp_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts');

// Fonction pour activer certaines fonctionnalités de WordPress (comme le logo personnalisé)
function nathalie_mota_setup() {
    // Ajout du support pour un logo personnalisable
    add_theme_support( 'custom-logo', array(
        'flex-height' => true, // Permet de redimensionner la hauteur du logo
        'flex-width'  => true, // Permet de redimensionner la largeur du logo
    ) );
}
add_action( 'after_setup_theme', 'nathalie_mota_setup' );

// Fonction pour enregistrer les emplacements des menus
function nathalie_mota_register_menus() {
    // Enregistrement des emplacements de menu dans le thème
    register_nav_menus( array(
        'primary' => __( 'Menu Principal' ), // Menu principal pour la navigation principale
        'footer'  => __( 'Menu Footer' ),    // Menu pour le pied de page
    ) );
}
add_action( 'init', 'nathalie_mota_register_menus' );

// Fonction pour charger plus de photos via Ajax (utilisé pour la pagination)
function load_more_photos() {
    // Vérifie si une page est spécifiée et obtient sa valeur, sinon utilise 1
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Paramètres pour la requête des photos
    $args = array(
        'post_type' => 'photo',  // Type de contenu à récupérer (photos)
        'posts_per_page' => 8,   // Nombre de photos à afficher par page
        'paged' => $page,        // Paramètre de pagination
    );

    $photo_query = new WP_Query($args); // Exécution de la requête pour obtenir les photos

    if ($photo_query->have_posts()) :
        // Si des photos sont trouvées, on les affiche
        while ($photo_query->have_posts()) : $photo_query->the_post();
            get_template_part('template-parts/photo_block'); // Inclusion d'un template pour chaque photo
        endwhile;
    endif;

    wp_die(); // Arrêt de l'exécution après la requête Ajax
}
// Action pour exécuter cette fonction pour les utilisateurs connectés et non connectés
add_action('wp_ajax_load_more_photos', 'load_more_photos'); // Utilisateurs connectés
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos'); // Utilisateurs non connectés


// Fonction pour filtrer et trier les photos selon les critères définis par l'utilisateur (catégorie, format, etc.)
function filter_and_sort_photos() {
    // Récupère les valeurs de filtre envoyées par l'utilisateur (par exemple, catégorie, format, tri)
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'desc'; // Par défaut, tri décroissant

    // Paramètres pour la requête de filtrage des photos
    $args = array(
        'post_type' => 'photo',  // Type de contenu (photos)
        'posts_per_page' => 8,   // Nombre de photos par page
        'orderby' => 'date',     // Tri par date
        'order' => $sort,        // Ordre de tri (croissant ou décroissant)
    );

    // Si une catégorie est spécifiée, on l'ajoute à la requête
    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie', // Taxonomie 'categorie'
            'field' => 'slug',         // Rechercher par 'slug' de la catégorie
            'terms' => $category,      // Catégorie à filtrer
        );
    }

    // Si un format est spécifié, on l'ajoute à la requête
    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',   // Taxonomie 'format'
            'field' => 'slug',        // Rechercher par 'slug' du format
            'terms' => $format,       // Format à filtrer
        );
    }

    // Exécution de la requête avec les arguments définis
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        // Si des photos sont trouvées, on les affiche
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/photo_block'); // Chargement du template pour chaque photo
        endwhile;
    else :
        // Si aucune photo n'est trouvée, afficher un message
        echo '<p>Aucune photo trouvée.</p>';
    endif;

    wp_die(); // Fin de l'exécution pour la requête Ajax
}
// Action pour exécuter cette fonction pour les utilisateurs connectés et non connectés
add_action('wp_ajax_filter_and_sort_photos', 'filter_and_sort_photos'); // Utilisateurs connectés
add_action('wp_ajax_nopriv_filter_and_sort_photos', 'filter_and_sort_photos'); // Utilisateurs non connectés

// Fonction pour ajouter des termes par défaut dans les taxonomies (catégories et formats) si ils n'existent pas
function ajouter_termes_par_defaut_si_absents() {
    // Catégories par défaut à insérer
    $categories = array('Réception', 'Mariage', 'Concert', 'Télévision');
    foreach ($categories as $categorie) {
        // Vérifie si la catégorie existe déjà, sinon l'ajoute
        if (!term_exists($categorie, 'categories')) {
            wp_insert_term($categorie, 'categories'); // Ajout d'une nouvelle catégorie
        }
    }

    // Formats par défaut à insérer
    $formats = array('paysage', 'portrait');
    foreach ($formats as $format) {
        // Vérifie si le format existe déjà, sinon l'ajoute
        if (!term_exists($format, 'formats')) {
            wp_insert_term($format, 'formats'); // Ajout d'un nouveau format
        }
    }
}
add_action('init', 'ajouter_termes_par_defaut_si_absents'); // Exécution lors de l'initialisation du site

// Filtre pour ajouter l'ID du post parent aux pièces jointes via l'API REST
add_filter('rest_prepare_attachment', function ($response, $post, $request) {
    // Récupère l'ID du post parent de l'attachement
    $parent_id = $post->post_parent;

    // Ajoute l'ID du post parent dans la réponse de l'API
    if ($parent_id) {
        $response->data['post'] = $parent_id;
    } else {
        $response->data['post'] = null;
    }

    return $response; // Retourne la réponse modifiée
}, 10, 3); // Priorité et nombre d'arguments

// Action qui s'exécute lors de l'enregistrement d'un post
add_action('save_post', function ($post_id) {
    // Vérifie si une image mise en avant est définie pour le post
    $thumbnail_id = get_post_thumbnail_id($post_id);

    if ($thumbnail_id) {
        // Met à jour la relation entre l'image mise en avant et son post parent
        wp_update_post(array(
            'ID' => $thumbnail_id,
            'post_parent' => $post_id,
        ));
    }
});
