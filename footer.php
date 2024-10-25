<footer>
    <?php
    wp_nav_menu(array(
        'theme_location' => 'footer', // Utiliser 'footer' pour l'emplacement du menu
        'menu_class'     => 'footer-menu-class', // Classe CSS pour le menu
        'container'      => false // Ne pas envelopper le menu dans un conteneur
    ));
    ?>
    <?php wp_footer(); ?> <!-- Appel à wp_footer pour inclure les scripts et styles nécessaires -->

    <!-- Inclure la modale de contact -->
    <!-- get_template_part('templates_part/modal', 'contact');  -->
</footer>
</body>
</html>

