// FORMULAIRE DE COMMANDE
// message d'erreur pour civilité si ce n'est pas séléctionné
const civiliteErreur = document.getElementById('civilite-error')
const prenom = document.getElementById('formulaire_form_prenom')
const civilite = document.querySelectorAll('input[type="radio"]')
let nb = 0
if (prenom){
  
  civilite.forEach((radio) => {
    if(!radio.checked) {
      nb++
    }
    radio.addEventListener('click', () => {
      civiliteErreur.style.display = "none";
      nb = 0
    })
  })
  prenom.onfocus = () => {
    if(nb == 2) {
      civiliteErreur.innerHTML = "Veuillez cocher une civilité";
      civiliteErreur.style.display = "block";
    }
  }
}

const paiementBtn = document.querySelector('#submit-paiement-btn');
if(!paiementBtn){
    
    // si c'est une entreprise qui commande, l'adresse, code postale et ville sera obligatoire
    const raisonSociale = document.querySelector('#formulaire_form_raisonSociale');
    const adresse = document.querySelector('#formulaire_form_adresse');
    const cp = document.querySelector('#formulaire_form_cp');
    const ville = document.querySelector('#formulaire_form_ville');
    
    if(raisonSociale){
      raisonSociale.oninput = (event) => {
        if(raisonSociale.value){
          adresse.setAttribute('required', 'true');
          cp.setAttribute('required', 'true');
          ville.setAttribute('required', 'true');
        }else{
          adresse.removeAttribute('required');
          cp.removeAttribute('required');
          ville.removeAttribute('required');
        }
      }
    }
    
    // variables inputs produits
    const dateRetrait = document.querySelector('#formulaire_form_dateRetrait'); 
    const dateRetraitErreur = document.querySelector('#checkout-dateRetrait-erreur');
    const tempsRetrait = document.querySelector('#formulaire_form_tempsRetrait');
    const optionMatin= document.querySelector('#formulaire_form_tempsRetrait option[value="Matin"]')
    const optionApresMidi= document.querySelector('#formulaire_form_tempsRetrait option[value="Après-midi"]')
    const now = new Date();
    if(dateRetrait){
      
      dateRetrait.oninput = (event) => {
        let date = new Date(dateRetrait.value);
        let ferie = joursFeries(date);
        const hour = now.getHours();
        let options = [optionMatin,optionApresMidi];
        
        if (date.getDate() === now.getDate()){
          options.forEach(function(option) {
            tempsRetrait.remove(option);
          })
          
          options = [optionApresMidi];
          options.forEach(function(option) {
            tempsRetrait.add(option);
          })
          
          if(hour >= 10 && hour <= 12){
            document.getElementById('message-commande').style.display = "block";
          }
          
        }else{
          options.forEach(function(option) {
            tempsRetrait.remove(option);
          })
          
          options = [optionMatin,optionApresMidi];
          options.forEach(function(option) {
            tempsRetrait.add(option);
          })
        optionMatin.setAttribute('checked', 'true')
        document.getElementById('message-commande').style.display = "none";
      }
      
      // les jours feriés sont identifiés par la fonction joursFeries() du fichier "feries.js"
      if (date.getDay() === 1 || ferie == true) {
        
        dateRetraitErreur.style.display = "block";
        dateRetraitErreur.innerHTML = "Nous ne travaillons pas les lundis et les jours fériés.";
        dateRetrait.value = '';
      }else{
        dateRetraitErreur.style.display = "none";
      } 
    }
  }
   
  
  
  // variables inputs plateau et/ou Assortiment cadeau
  const dateRetraitPA = document.querySelector('#formulaire_form_dateRetraitPA');
  const dateRetraitPAErreur = document.querySelector('#checkout-dateRetraitPA-erreur');
  const tempsRetraitPA = document.querySelector('#formulaire_form_tempsRetraitPA');
  const optionMatinPA= document.querySelector('#formulaire_form_tempsRetraitPA option[value="Matin"]')
  const optionApresMidiPA= document.querySelector('#formulaire_form_tempsRetraitPA option[value="Après-midi"]')
  
  if(dateRetraitPA){
    dateRetraitPA.oninput = (event) => {
      let date = new Date(dateRetraitPA.value);
      let ferie = joursFeries(date);
      let options = [optionMatinPA,optionApresMidiPA];
      
      if (date.getDay() === 1 || ferie == true) {
        dateRetraitPAErreur.style.display = "block";
        dateRetraitPAErreur.innerHTML = "Nous ne travaillons pas les lundis et les jours fériés.";
        dateRetraitPA.value = '';
      }else{
        dateRetraitPAErreur.style.display = "none";
      }

      if (date.getDate() === now.getDate()+1){
        options.forEach(function(option) {
          tempsRetraitPA.remove(option);
        })
        
        options = [optionApresMidiPA];
        options.forEach(function(option) {
          tempsRetraitPA.add(option);
        })
        
      }else{
        options.forEach(function(option) {
          tempsRetraitPA.remove(option);
        })
        
        options = [optionMatinPA,optionApresMidiPA];
        options.forEach(function(option) {
          tempsRetraitPA.add(option);
        })
     }
    }
  }
}
  
  // --------------------------------------------------------------------------------------------------------

// PROCEDER AU PAIEMENT
const card = document.querySelector('.card');
const paiementMessage = document.querySelector('#paiement-message');

if (paiementBtn) {
  paiementBtn.addEventListener('click', attendre);

  function attendre(){
    paiementBtn.style.display = "none"
    paiementMessage.innerHTML = "Veuillez patienter quelques secondes.";
    paiementMessage.style.color = "black";
    paiementMessage.style.marginBottom = "250px";
  }
}