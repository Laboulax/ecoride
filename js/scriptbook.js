

document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("nbPersonnes");
    const total = document.getElementById("total");
    const prix = parseFloat(document.getElementById("prixTrajet")?.dataset?.prix || 0);

    if (!input || !total || isNaN(prix)) {
        console.warn("JS: éléments manquants ou prix invalide");
        return;
    }

    input.addEventListener("input", () => {
        const nb = parseInt(input.value) || 1;
        total.textContent = (nb * prix).toFixed(2);
    });
});
