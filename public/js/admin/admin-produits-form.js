
// ---------------------CATEGORIE/SERVICE---------------------------------------
const categorie = document.getElementById('produit_form_categorie');
const service = document.getElementById('produit_form_service');
const phrase = document.getElementById('cat-serv-p-form');

if(categorie.value || service.value){
    phrase.style.display = "none"
}

categorie.onchange = function(){
    phrase.style.display = "none"

    if(categorie.value && service.value || !categorie.value && !service.value){
        phrase.style.display = "block"
    }else{
        phrase.style.display = "none"
    }
}
service.onchange = function(){
    phrase.style.display = "none"
    if(categorie.value && service.value || !categorie.value && !service.value){
        phrase.style.display = "block"
    }else{
        phrase.style.display = "none"
    }
}

// -------------------------PRODUT------------------------------------

// en cas de ajout insertion image est obligatoire
const nomProduit = document.getElementById('produit_form_nomProduit');
const imageProduit = document.getElementById('produit_form_image');

if(!nomProduit.value){
    imageProduit.setAttribute('required', 'true');
}

// si la référence est vide, mettre un under-line
const reference = document.getElementById('produit_form_reference');
const description = document.getElementById('produit_form_description');

if(description.value){
    if(!reference.value){
        reference.setAttribute('value', '_');
    }
}

// si l'unité de mesure est défini le poids/volume, prix au kilo et la taxe sont obligatoire
const uniteMesure = document.getElementById('produit_form_uniteMesure');
const poidsVolumeUnitaire = document.getElementById('produit_form_poidsVolumeUnitaire');
const prixAuKiloLitre = document.getElementById('produit_form_prixAuKiloLitre');
const taxe = document.getElementById('produit_form_taxe');

if(uniteMesure){
    uniteMesure.oninput = () => {
      if(uniteMesure.value){
        poidsVolumeUnitaire.setAttribute('required', 'true');
        prixAuKiloLitre.setAttribute('required', 'true');
        taxe.setAttribute('required', 'true');
      }else{
        poidsVolumeUnitaire.removeAttribute('required');
        prixAuKiloLitre.removeAttribute('required');
        taxe.removeAttribute('required');
      }
    }
}
