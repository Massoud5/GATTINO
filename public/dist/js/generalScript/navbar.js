
// Responsive Navbar
const target = document.querySelector('.responsive-nav-icon');
const navBox = document.querySelector('.nav-box');
const searchIcon = document.querySelector('.search-icon');

if(target){ 
    target.addEventListener('click', function() {
        navBox.classList.toggle('toggle');
    })
    
    // Search bar NavResponsive
    searchIcon.addEventListener('click', function() {
        navBox.classList.toggle('toggle');
    })

    // to close the menu everywhere clicking
    
}


// Search bar
const closeSearchBoxIcon = document.querySelector('.close-search-box');
const searchBox = document.querySelector('.search-bar-box');
const searchInput = document.querySelector('#search_produit_mots');
// pour mieux gérer l'affichage de search box en cas de rafraîchissement de la page
const searchBarDisplay = document.querySelector('.search-results-box h3');

if (searchIcon){

    if(searchBarDisplay.textContent=== 'Résultat(s) de recherche: ""'){
        searchBox.style.display = "none";
    }
    
    searchIcon.addEventListener('click', function() {
        if(searchBox.style.display === "none"){
            searchBox.style.display = "block" 
            searchInput.focus()
        }  
    })
    if(searchBox){
        closeSearchBoxIcon.addEventListener('click', function() {
            searchBox.style.display = "none"
            searchInput.value = ''
        })
    }
    // to hide the search box everywhere clicking
    $(document).mouseup(function (e) {
        let box = $(".search-bar-box");
        if (!box.is(e.target) && box.has(e.target).length === 0) 
        {
            box.hide();
        searchInput.value = ''
    }
    });
}

