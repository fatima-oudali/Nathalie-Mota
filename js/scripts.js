
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


document.addEventListener("DOMContentLoaded", function () {
    const filters = document.querySelectorAll("#filter-categorie, #filter-format, #sort-date");
    const photoContainer = document.querySelector(".related-images");

    filters.forEach(filter => {
        filter.addEventListener("change", function () {
            const category = document.querySelector("#filter-categorie").value;
            const format = document.querySelector("#filter-format").value;
            const sort = document.querySelector("#sort-date").value;

            const formData = new FormData();
            formData.append("action", "filter_and_sort_photos");
            formData.append("category", category);
            formData.append("format", format);
            formData.append("sort", sort);

            fetch(wp_data.ajax_url, {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                photoContainer.innerHTML = data;
            })
            .catch(error => console.error("Erreur:", error));
        });
    });
});














  








