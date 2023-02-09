let joursFeries2023 = [
    new Date(2022,1,01).toDateString(), // 01/01/2023 Le jour de l’an*

    new Date(2023,3,7).toDateString(), // 07/04/2023 Vendredi Saint (Alsace)
    new Date(2023,3,10).toDateString(), // 10/04/2023 Le lundi de Pâques

    new Date(2023,4,1).toDateString(), // 01/05/2023 La fête du Travail*
    new Date(2023,4,8).toDateString(), // 08/05/2023 La victoire de 1945*

    new Date(2023,4,18).toDateString(), // 18/05/2023 Le jeudi de l’Ascension
    new Date(2023,4,29).toDateString(), // 29/05/2023 Le lundi de Pentecôte

    new Date(2023,6,14).toDateString(), // 14/07/2023 La fête nationale*
    new Date(2023,7,15).toDateString(), // 15/08/2023 L’Assomption*
    new Date(2023,10,1).toDateString(), // 01/11/2023 La Toussaint*
    new Date(2023,10,11).toDateString(), // 11/11/2023 L’Armistice*
    new Date(2023,11,25).toDateString(), // 25/12/2023 Noël*
    new Date(2023,11,26).toDateString(), // 26/12/2023 2e jour de Noël (Alsace)* 
];
    // ------------------------------------------------------------
let joursFeries2024 = [
    new Date(2024,0,01).toDateString(), // 01/01/2024 Le jour de l’an*

    new Date(2024,2,29).toDateString(), // 29/03/2024 Vendredi Saint (Alsace)
    new Date(2024,3,01).toDateString(), // 01/04/2024 Le lundi de Pâques

    new Date(2024,4,1).toDateString(), // 01/05/2024 La fête du Travail*
    new Date(2024,4,8).toDateString(), // 08/05/2024 La victoire de 1945*

    new Date(2024,4,9).toDateString(), // 09/05/2024 Le jeudi de l’Ascension
    new Date(2024,4,20).toDateString(), // 20/05/2024 Le lundi de Pentecôte
    
    new Date(2024,6,14).toDateString(), // 14/07/2024 La fête nationale*
    new Date(2024,7,15).toDateString(), // 15/08/2024 L’Assomption*
    new Date(2024,10,01).toDateString(), // 01/11/2024 La Toussaint*
    new Date(2024,10,11).toDateString(), // 11/11/2024 L’Armistice*
    new Date(2024,11,25).toDateString(), // 25/12/2024 Noël*
    new Date(2024,11,26).toDateString(), // 26/12/2024 2e jour de Noël (Alsace)*
];

// function getLocaleDateString() {const format = {"fr-FR": "dd/MM/yyyy",}}

function joursFeries(date){

    year = date.getFullYear();

    if (year == 2023){
        if(joursFeries2023.includes(date.toDateString())){
            return true;
        }else{
            return false;
        }
    }
    if (year == 2024){
        if(joursFeries2024.includes(date.toDateString())){
            return true;
        }else{
            return false;
        }
    }

}