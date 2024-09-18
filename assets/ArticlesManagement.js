//  mon tre les articles d'un langage donné

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

// Sur l'accueil, montre les infos d'un langae donné

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

//  sur l'accueil, montre la description d'un outil donné

// Sélectionner tous les éléments d'outil et les descriptions
const toolItems = document.querySelectorAll('.tool-item');
const toolDescriptions = document.querySelectorAll('.tool-description');

// Fonction pour afficher la description de l'outil et gérer les couleurs
function showToolDescription(toolId) {
    // Cacher toutes les descriptions d'outils
    toolDescriptions.forEach(description => {
        description.classList.add('hidden');
    });

    // Afficher la description correspondant à l'outil cliqué
    const selectedDescription = document.getElementById(`tool-description-${toolId}`);
    if (selectedDescription) {
        selectedDescription.classList.remove('hidden');
    }

    // Mettre à jour les couleurs des outils
    toolItems.forEach(tool => {
        const currentToolId = tool.getAttribute('data-tool-id');
        if (currentToolId === String(toolId)) {
            tool.classList.add('text-col1', 'color-shadow-xl');
            tool.classList.remove('text-col2');
        } else {
            tool.classList.add('text-col2');
            tool.classList.remove('text-col1', 'color-shadow-xl');
        }
    });
}

// Ajouter des événements de clic à chaque outil
toolItems.forEach(tool => {
    tool.addEventListener('click', () => {
        const toolId = tool.getAttribute('data-tool-id');
        showToolDescription(toolId);
    });
});

// Afficher par défaut la description du premier outil (id = 1)
showToolDescription(1);

