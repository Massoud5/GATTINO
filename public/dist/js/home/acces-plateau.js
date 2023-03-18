// Ajax accéder aux plateaux //! à faire
const plateauxNavEl = document.getElementById('plateaux-nav');

if(plateauxNavEl){

    function accessToPlateaux(){
                        
        const servicesH2 = document.querySelector(".services h2");
        servicesH2.scrollIntoView();
        
        const subtitlesNav = document.querySelectorAll(".services-subtitles .subtitle");
        Object.values(subtitlesNav).forEach(function(subtitle){
            if(subtitle.hasAttribute('data-active')){
                subtitle.removeAttribute('data-active');
            }
        })
        
        const slidesNav = document.querySelectorAll(".slide-box .slide");
        Object.values(slidesNav).forEach(function(slide){
            if(slide.hasAttribute('data-active')){
                slide.removeAttribute('data-active');
            }
        })
        
        const paragraphesNav = document.querySelectorAll(".paragraphes .paragraphe");
        Object.values(paragraphesNav).forEach(function(paragraphe){
            if(paragraphe.hasAttribute('data-active')){
                paragraphe.removeAttribute('data-active');
            }
        })
        
        const produitsServNav = document.querySelectorAll(".services-produits-lists .service-produits");
        Object.values(produitsServNav).forEach(function(produits){
            if(produits.hasAttribute('data-active')){
                produits.removeAttribute('data-active');
            }
        })
        
        const subtitlePlateaux = document.getElementById('subtitle-plateaux')
        subtitlePlateaux.setAttribute('data-active','');
        
        const slidePlateaux = document.getElementById('slide-plateaux')
        slidePlateaux.setAttribute('data-active','');
        
        const paragraphePlateaux = document.getElementById('paragraphe-plateaux')
        paragraphePlateaux.setAttribute('data-active','');
        
        const produitsPlateaux = document.getElementById('produits-plateaux')
        produitsPlateaux.setAttribute('data-active','');
            
    }

}