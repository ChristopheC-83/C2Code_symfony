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