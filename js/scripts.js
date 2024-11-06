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








