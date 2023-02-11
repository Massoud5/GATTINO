// Services

const btns = document.querySelectorAll('[data-carousel-button]');

btns.forEach(button => {
    button.addEventListener("click", () =>{
        // ---CAROUSEL---
        const offset= button.dataset.carouselButton === "next" ? 1 : -1; // si le next est clické la valeu de offset est 1 sinon c'est -1
                                // parcours jusqu'au parent et la racine de l'élément pour trouver le selector dans l'argument
        const slides = button.closest("[data-carousel]").querySelector("[data-slides]"); // pour cibler le ul de carousel
        const activeSlide = slides.querySelector("[data-active]"); // pour cibler l'élément active de ul
        let newIndex = [...slides.children].indexOf(activeSlide) + offset; // l'index de l'enfant active de ul +|- 1 
        
        if (newIndex < 0) newIndex = slides.children.length - 1; // si le nombre devient négatif(on vient en arrière au début), il sera assigné au total des enfants moins 1(le dernier élément sera affiché)
        if (newIndex >= slides.children.length) newIndex = 0; // si le nombre est plus grand que le nombre total des enfants(on passe au prochain au dernier élément), le nombre sera 0
        
        slides.children[newIndex].dataset.active = true; // pour activer la lecture de l'enfant spécifié
        delete activeSlide.dataset.active; // pour supprimer l'attribut "data-active" des autres éléments (désactiver)
        // ------------
        
        // ---SUBTITLE---
        const subtitles = button.closest("[data-carousel]").querySelector("[data-subtitles]");
        const activeSubtitle = subtitles.querySelector("[data-active]");

        subtitles.children[newIndex].dataset.active = true;
        delete activeSubtitle.dataset.active;
        // ------------

        // ---PRAGRAPHE---
        const pragraphes = button.closest("[data-services]").querySelector("[data-paragraphes]");
        const activePargraphe = pragraphes.querySelector("[data-active]");

        pragraphes.children[newIndex].dataset.active = true;
        delete activePargraphe.dataset.active;
        // ------------

        // ---PRODUITS---
        const produits = button.closest("[data-services]").querySelector("[data-produits]");
        const activeProduit = produits.querySelector("[data-active]");

        produits.children[newIndex].dataset.active = true;
        delete activeProduit.dataset.active;
        // ------------  
    })
})
//-------------------------------------------------------------------------

// Go to top in home

// Get the button
let goToTopBtn = document.getElementById("go-to-top");

// When the user scrolls down 200px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    goToTopBtn.style.display = "block";
  } else {
    goToTopBtn.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function goToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
// -----------------------------------------------------------------------