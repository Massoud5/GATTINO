// showCatégorie.html.twig | add/Increment PRODUCTS IN CART
const catProdTitres = document.querySelectorAll('.produitCat-li-info h5')
const catAddLinks = document.querySelectorAll("a.add-js-cat");
if (catAddLinks) {
    catAddLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            // this est un des éléments de catAddLinks qui représent les autres
            const url = this.href;

            axios.get(url).then(function (response) {

                Object.values(catProdTitres).forEach(function (titre) {
                    if (titre.textContent === response.data.produitTitre) {
                        // affichage quantité
                        const prodQuantityEl = titre.closest('.produitCat-li-info').querySelector('span');
                        prodQuantityEl.textContent = response.data.quantity;

                        // affichage icon panier
                        const panNullIcon = titre.closest('.produitCat-li-info').querySelector('.prod-panier-box .cat-prod-ajouter-btn');
                        const panIcons = titre.closest('.produitCat-li-info').querySelector('.prod-panier-box .cat-prod-plus-moins');
                        if (response.data.quantity == 0) {
                            panIcons.style.display = "none"
                            panNullIcon.style.display = "block"
                        } else {
                            panIcons.style.display = "block"
                            panNullIcon.style.display = "none"
                        }

                        // mise à jour quantité de panier nav
                        const navPanQuantEl = document.querySelector('.panier-quantity');
                        navPanQuantEl.textContent = response.data.totalPanier;
                    }
                });
            })
        });
    })
}
// | delete/decrement PRODUCTS IN CART
const catDelLinks = document.querySelectorAll("a.delete-js-cat");
if (catDelLinks) {
    catDelLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            // this est un des éléments de catDelLinks qui représent les autres
            const url = this.href;

            axios.get(url).then(function (response) {

                Object.values(catProdTitres).forEach(function (titre) {
                    if (titre.textContent === response.data.produitTitre) {
                        // affichage quantité
                        const prodQuantityEl = titre.closest('.produitCat-li-info').querySelector('span');

                        prodQuantityEl.textContent = response.data.quantity;

                        // affichage icon panier
                        const panNullIcon = titre.closest('.produitCat-li-info').querySelector('.prod-panier-box .cat-prod-ajouter-btn');
                        const panIcons = titre.closest('.produitCat-li-info').querySelector('.prod-panier-box .cat-prod-plus-moins');

                        if (response.data.quantity == 0) {
                            panIcons.style.display = "none"
                            panNullIcon.style.display = "block"
                        } else {
                            panIcons.style.display = "block"
                            panNullIcon.style.display = "none"
                        }



                        // mise à jour quantité de panier nav
                        const navPanQuantEl = document.querySelector('.panier-quantity');
                        navPanQuantEl.textContent = response.data.totalPanier;
                    }
                });
            })
        });
    })
}