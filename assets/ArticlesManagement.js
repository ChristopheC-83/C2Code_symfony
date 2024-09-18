let articleInArray = document.querySelectorAll(".articleInArray");

function justOneLanguage(languageId) {
    articleInArray.forEach((article) => {
        if (article.classList.contains(languageId)) {
            article.style.display = "table-row";
        } else {
            article.style.display = "none";
        }
    });
}

const btnLanguages = document.querySelectorAll(".btnLanguages");

btnLanguages.forEach((btn) => {
    btn.addEventListener("click", () => {
        const languageId = btn.id;
        if (languageId === "all") {
            articleInArray.forEach((article) => {
                article.style.display = "table-row";
            });
            return;
        }
        justOneLanguage(languageId);
    });
});

// Sélectionne toutes les icônes et descriptions
const icoFontawesome = document.querySelectorAll('.icoFontawesome');
const descriptions = document.querySelectorAll('.description');

// Fonction pour afficher la description correspondant à l'ID de la langue et mettre à jour les couleurs des icônes
function showDescription(languageId) {
    // Cacher toutes les descriptions
    descriptions.forEach(desc => desc.classList.add('hidden'));

    // Afficher la description correspondant à l'ID
    const description = document.querySelector(`#description-${languageId}`);
    if (description) {
        description.classList.remove('hidden');
    }

    // Mettre à jour les couleurs des icônes
    icoFontawesome.forEach(ico => {
        if (ico.getAttribute('data-id') === String(languageId)) {
            ico.classList.add('text-col1');
            ico.classList.remove('text-col2');
        } else {
            ico.classList.add('text-col2');
            ico.classList.remove('text-col1');
        }
    });
}

// Ajouter des événements de clic aux icônes
icoFontawesome.forEach(ico => {
    ico.addEventListener('click', () => {
        const languageId = ico.getAttribute('data-id');
        showDescription(languageId);
    });
});

// Afficher la description pour l'ID 1 au chargement de la page
showDescription(1);

