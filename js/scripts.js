
// Optimisation : Ajout d'un "DOMContentLoaded" pour garantir que le DOM est complètement chargé avant l'exécution du code.
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("modalContact");
    const openModalButton = document.getElementById("menu-item-55");

    // Ouvrir la modale en cliquant sur le bouton
    openModalButton.addEventListener("click", function(e) {
        e.preventDefault(); // Empêche l'action par défaut du lien (évite le rechargement de la page)
        modal.classList.remove('hide'); // Supprime la classe 'hide' pour rendre la modale visible
        modal.style.display = "flex"; // Applique un affichage flexible pour centrer la modale
        setTimeout(() => {
            modal.classList.add("show"); // Ajoute la classe 'show' pour activer l'animation d'apparition
        }, 10); // Délai très court pour permettre l'affichage de la modale avant d'ajouter la classe 'show'
    });

    // Fermer la modale en cliquant sur le fond
    modal.addEventListener("click", function(e) {
        if (e.target === modal) { // Vérifie si l'utilisateur a cliqué sur le fond de la modale
            modal.classList.add('hide'); // Ajoute la classe 'hide' pour démarrer l'animation de fermeture
            setTimeout(() => {
                modal.classList.remove('show', 'hide'); // Retire les classes pour réinitialiser l'état
                modal.style.display = 'none'; // Cache la modale
            }, 300); // Délai pour attendre que l'animation de fermeture soit terminée
        }
    });
});

// Code jQuery pour gérer l'affichage d'une autre modal
jQuery(document).ready(function($) {
    const modal = $("#modalContact");
    const openModalButton = $("#contactButton");

    // Ouvrir la modal au clic sur le bouton
    openModalButton.on("click", function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du bouton
        const refPhoto = $(this).data('ref'); // Récupère la référence de la photo depuis l'attribut data-ref
        $('#ref-photo').val(refPhoto); // Remplir le champ de référence dans la modal
        modal.removeClass('hide'); // S'assure que la modal n'a pas la classe 'hide'
        modal.css("display", "flex"); // Affiche la modal en flex
        setTimeout(() => {
            modal.addClass("show"); // Ajoute la classe 'show' pour activer l'animation d'apparition
        }, 10);
    });

    // Fermer la modal en cliquant sur le fond
    modal.on("click", function(e) {
        if ($(e.target).is(modal)) { // Vérifie si l'utilisateur a cliqué sur le fond
            modal.addClass('hide'); // Ajoute la classe 'hide' pour démarrer l'animation de fermeture
            setTimeout(() => {
                modal.removeClass('show hide'); // Retire les classes pour réinitialiser l'état
                modal.css("display", "none"); // Cache la modal
                $('#ref-photo').val(''); // Vide le champ de référence
            }, 300); // Délai pour attendre que l'animation soit terminée
        }
    });
});

// Gérer l'ouverture et fermeture du menu burger
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.main-navigation');

    if (menuToggle && navigation) {
        menuToggle.addEventListener('click', function () {
            console.log('Le bouton burger a été cliqué.');
        });
    } else {
        console.error('Les sélecteurs .menu-toggle ou .main-navigation sont introuvables.');
    }

    menuToggle.addEventListener('click', function() {
        setTimeout(() => {
            menuToggle.classList.toggle('open'); // Active l'animation de transformation du burger en croix
            navigation.classList.toggle('active'); // Affiche ou cache le menu mobile
        }, 10); // Court délai pour laisser le temps à l'animation de se déclencher
    });
});

// Code jQuery pour charger plus de photos avec un bouton
jQuery(document).ready(function($) {
    let page = 2; // Début à la page 2, car la page 1 est déjà chargée

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
                    $('.related-images').append(response); // Ajoute les nouvelles photos à la galerie
                    page++; // Incrémente le numéro de la page pour charger les prochaines photos
                } else {
                    $('#load-more-button').hide(); // Cache le bouton si aucune photo n'est disponible
                }
            },
            error: function() {
                console.log('Erreur de chargement des photos.');
            }
        });
    });
});

// Filtrer et trier les photos avec des sélections
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
            .then(response => response.text()) // Traite la réponse du serveur sous forme de texte
            .then(data => {
                photoContainer.innerHTML = data; // Remplace le contenu des photos filtrées
            })
            .catch(error => console.error("Erreur:", error)); // Gère les erreurs
        });
    });
});



// Fonction pour ouvrir la lightbox avec les détails d'une image
function handleLightbox(mediaId, event = null) {
    // Si un événement est passé, on l'empêche de se propager (par exemple, éviter le comportement par défaut des liens)
    if (event) event.preventDefault();
    console.log("Event:", event); // Affiche l'événement dans la console pour le débogage

    // On récupère les éléments de la lightbox (l'endroit où l'image et ses informations seront affichées)
    const lightbox = document.getElementById('lightbox');
    if (!lightbox) {
        console.log('La lightbox n\'existe pas.'); // Si la lightbox n'existe pas, on affiche un message d'erreur
        return; // On arrête l'exécution de la fonction
    }

    // On récupère les éléments spécifiques où on mettra les informations de l'image
    const lightboxImage = document.getElementById('lightbox-image');
    const lightboxCategory = document.getElementById('lightbox-category');
    const lightboxReference = document.getElementById('lightbox-reference');

    // On fait une requête pour récupérer les informations sur le média en utilisant l'ID du média
    fetch(`${window.location.origin}/wp-json/wp/v2/media/${mediaId}`)
        .then(response => {
            if (!response.ok) {
                console.log('Erreur HTTP lors de la récupération de l\'image:', response.status);  // Affiche l'erreur si la réponse est mauvaise
                throw new Error(`Erreur HTTP ${response.status}`);
            }
            return response.json(); // Si la réponse est correcte, on transforme le JSON en objet
        })
        .then(media => {
            console.log('Média récupéré:', media.post);  // Affiche les données récupérées du média
            const postId = media.post;
            if (!postId) throw new Error('Aucun post associé à ce média.'); // Si aucun post n'est trouvé, on déclenche une erreur

            // On récupère ensuite le post associé au média pour avoir plus de détails
            return fetch(`${window.location.origin}/wp-json/wp/v2/photo/${postId}`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur lors de la récupération du post ${postId}`); // Si la requête échoue, on affiche une erreur
            }
            return response.json(); // On transforme la réponse en JSON
        })
        .then(post => {
            // Si le post contient une image en vedette (featured_media), on la charge
            if (post.featured_media) {
                fetch(`${window.location.origin}/wp-json/wp/v2/media/${post.featured_media}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erreur lors de la récupération du média ${post.featured_media}`);
                        }
                        return response.json();
                    })
                    .then(mediaDetails => {
                        lightboxImage.src = mediaDetails.source_url || 'fallback-image.jpg'; // On met à jour l'image dans la lightbox
                    });
            }

            // Si le post a une catégorie, on la récupère et on l'affiche dans la lightbox
            if (post.categorie && post.categorie.length) {
                fetch(`${window.location.origin}/wp-json/wp/v2/categorie/${post.categorie[0]}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erreur lors de la récupération de la catégorie ${post.categorie[0]}`);
                        }
                        return response.json();
                    })
                    .then(category => {
                        lightboxCategory.innerHTML = 'Catégorie : ' + category.name; // On affiche le nom de la catégorie
                    });
            } else {
                lightboxCategory.innerHTML = 'Catégorie inconnue'; // Si pas de catégorie, on affiche 'Catégorie inconnue'
            }

            // On récupère la référence de l'image (champ ACF)
            const reference = post.acf && post.acf.reference ? post.acf.reference : 'Non définie';
            lightboxReference.innerHTML = 'Référence : ' + reference;

            // On met à jour les boutons de navigation (suivant et précédent) pour la lightbox
            updateNavButtons(mediaId);

            // On active la lightbox pour qu'elle soit visible
            lightbox.classList.add('active');
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données :', error); // Si une erreur survient, on la logue
            alert('Une erreur est survenue. Veuillez réessayer.'); // On affiche une alerte utilisateur
        });
}

// Fonction pour obtenir l'ID de l'image suivante
function getNextMediaId(currentMediaId) {
    return fetch(`${window.location.origin}/wp-json/wp/v2/media`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération de la liste des médias');
            }
            return response.json(); // On récupère tous les médias sous forme de JSON
        })
        .then(mediaList => {
            const index = mediaList.findIndex(media => media.id === currentMediaId); // On trouve l'index de l'image actuelle
            if (index === -1) return null; // Si l'image n'est pas trouvée, on retourne null
            const nextIndex = (index + 1) % mediaList.length; // On calcule l'index de l'image suivante (en boucle)
            return mediaList[nextIndex].id; // On retourne l'ID de l'image suivante
        });
}

// Fonction pour obtenir l'ID de l'image précédente
function getPrevMediaId(currentMediaId) {
    return fetch(`${window.location.origin}/wp-json/wp/v2/media`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération de la liste des médias');
            }
            return response.json(); // On récupère tous les médias sous forme de JSON
        })
        .then(mediaList => {
            const index = mediaList.findIndex(media => media.id === currentMediaId); // On trouve l'index de l'image actuelle
            if (index === -1) return null; // Si l'image n'est pas trouvée, on retourne null
            const prevIndex = (index - 1 + mediaList.length) % mediaList.length; // On calcule l'index de l'image précédente (en boucle)
            return mediaList[prevIndex].id; // On retourne l'ID de l'image précédente
        });
}

// Mise à jour des boutons de navigation (suivant et précédent) avec les nouveaux IDs
function updateNavButtons(currentMediaId) {
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    
    // Mise à jour du bouton suivant
    getNextMediaId(currentMediaId).then(nextMediaId => {
        if (nextBtn && nextMediaId) {
            nextBtn.setAttribute('data-media-id', nextMediaId); // On met à jour l'ID de l'image suivante dans le bouton
            nextBtn.onclick = (e) => handleLightbox(nextMediaId, e); // On lie le bouton suivant à l'image suivante
        }
    });

    // Mise à jour du bouton précédent
    getPrevMediaId(currentMediaId).then(prevMediaId => {
        if (prevBtn && prevMediaId) {
            prevBtn.setAttribute('data-media-id', prevMediaId); // On met à jour l'ID de l'image précédente dans le bouton
            prevBtn.onclick = (e) => handleLightbox(prevMediaId, e); // On lie le bouton précédent à l'image précédente
        }
    });
}

// Fonction pour fermer la lightbox
function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox) return; // Si la lightbox n'existe pas, on arrête la fonction
    lightbox.classList.remove('active'); // On enlève la classe 'active' pour cacher la lightbox
}

// Fonction pour rediriger vers la page du post lié à l'image
function goToSinglePage(postId) {
    window.location.href = `${window.location.origin}/?p=${postId}`; // On redirige vers la page du post en utilisant son ID
}
