/*document.addEventListener("DOMContentLoaded", function () {
    // Sélection des éléments de la modale
    const modal = document.getElementById("modalContact");
    const contactLink = document.querySelector(".menu-item-55"); // Lien du menu "Contact"
    
    // Fonction pour ouvrir la modale
    function openModal() {
        modal.style.display = "flex"; // Affiche la modale en mode flex
    }

    // Fonction pour fermer la modale
    function closeModal(event) {
        // Ferme la modale uniquement si on clique en dehors de modal-content
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    // Ouvrir la modale en cliquant sur le lien "Contact"
    contactLink.addEventListener("click", function (event) {
        event.preventDefault(); // Empêche le lien de rediriger
        openModal();
    });

    // Fermer la modale en cliquant sur le fond semi-transparent
    modal.addEventListener("click", closeModal);
});*/


document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("modalContact");
    const openModalButton = document.getElementById("menu-item-55");
    
    // Ouvrir la modale
    openModalButton.addEventListener("click", function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du lien
        modal.classList.remove('hide'); // Supprimez la classe hide si elle existe
        modal.style.display = "flex"; // Montre la modale
        setTimeout(() => {
            modal.classList.add("show");
        },10);
    });

    // Fermer la modale en cliquant sur le fond
    modal.addEventListener("click", function(e) {
        if (e.target === modal) { // Vérifie si l'utilisateur a cliqué sur le fond
            modal.classList.add('hide'); // Ajoutez la classe pour démarrer l'animation de disparition
            setTimeout(() => {
                modal.classList.remove('show', 'hide'); 
                modal.style.display = 'none'; 
            },300);
        }
    });
});


jQuery(document).ready(function($) {
    const modal = $("#modalContact");
    const openModalButton = $("#contactButton");

    // Ouvrir la modal au clic sur le bouton
    openModalButton.on("click", function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du bouton
        const refPhoto = $(this).data('ref'); // Récupérer la référence de la photo depuis l'attribut data-ref
        $('#ref-photo').val(refPhoto); // Pré-remplir le champ de référence dans la modal
        modal.removeClass('hide'); // S'assurer que la modal n'a pas la classe hide
        modal.css("display", "flex"); // Affiche la modal
        setTimeout(() => {
            modal.addClass("show"); // Ajoute la classe show après l'affichage
        }, 10);
    });

    // Fermer la modal en cliquant sur le fond
    modal.on("click", function(e) {
        if ($(e.target).is(modal)) { // Vérifie si l'utilisateur a cliqué sur le fond
            modal.addClass('hide'); // Ajoute la classe hide pour démarrer l'animation de disparition
            setTimeout(() => {
                modal.removeClass('show hide'); // Retire les classes pour réinitialiser
                modal.css("display", "none"); // Cache la modal
                $('#ref-photo').val(''); // Vide le champ de référence
            }, 300); // Délai pour attendre que l'animation se termine
        }
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.main-navigation');

    menuToggle.addEventListener('click', function() {
        setTimeout(() => {
        menuToggle.classList.toggle('open'); // Transforme le burger en croix
        navigation.classList.toggle('active'); // Affiche/Cache le menu mobile
        },10);
    });
});


/*jQuery(document).ready(function($) {
    let page = 2; // On commence à partir de la page 2 pour charger plus d'images
    $('#load-more').click(function() {
        var data = {
            'action': 'load_more_photos', // Action pour l'AJAX
            'page': page, // Page actuelle
        };

        $.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
            if(response) {
                $('.related-images').append(response); // Ajouter les nouvelles photos
                page++; // Incrémenter la page pour la prochaine requête
            }
        });
    });
});*/


/*var current_page = 1;
jQuery('#load-more-button').on('click', function() {
    current_page++; // Incrémentation de la page
    var data = {
        action: 'load_more_photos',
        page: current_page
    };

    console.log('Données envoyées : ', data); // Vérifier les données envoyées

    $.post(ajax_object.ajax_url, data, function(response) {
        console.log('Réponse reçue : ', response); // Vérifier la réponse du serveur
        $('.related-images').append(response); // Ajoute les photos retournées
    });
});*/



jQuery(document).ready(function($) {
    let page = 2; // Commence à 2 car la page 1 est déjà chargée

    $('#load-more-button').on('click', function() {
        $.ajax({
            url: wp_data.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_photos',
                page: page,
            },
            success: function(response) {
                if(response) {
                    $('.related-images').append(response); // Ajoute les nouvelles photos
                    page++; // Passe à la page suivante
                } else {
                    $('#load-more-button').hide(); // Cache le bouton si plus de photos
                }
            },
            error: function() {
                console.log('Erreur de chargement.');
            }
        });
    });
});













  








