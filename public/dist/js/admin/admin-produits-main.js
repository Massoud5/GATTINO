// Dropdown Services et Catégories
var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
  return new bootstrap.Dropdown(dropdownToggleEl)
})


const prodQuantFormTd = document.querySelector('.prod-quant-form-td');
if(prodQuantFormTd){
  const AdminInputQuntity = document.querySelectorAll('.prod-quant-form input[type=number]');
  // transformer l'objet en array
  Object.values(AdminInputQuntity).forEach(function(input) {
    if(input.value == 0){
      input.style.background = "black"
      input.style.color = "white"
    }
    else if(input.value < 10){
      input.style.background = "red"
    }
    else if(input.value >= 10 && input.value < 15){
      input.style.background = "yellow"
    }
  });
}

