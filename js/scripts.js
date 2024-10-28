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
        modal.style.display = "flex"; // Montre la modale
    });

    // Fermer la modale en cliquant sur le fond
    modal.addEventListener("click", function(e) {
        if (e.target === modal) { // Vérifie si l'utilisateur a cliqué sur le fond
            modal.style.display = "none"; // Cache la modale
        }
    });
});
