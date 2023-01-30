const buttons = document.querySelectorAll('.btn')
const form = document.querySelector('form')
const inputs = document.querySelectorAll('.campo')
const result = document.querySelector('.input-result')
const feedback = document.querySelectorAll('.invalid-feedback')

//Limpa os campos do form
buttons[2].addEventListener('click', (e) => { 
    e.preventDefault()
    inputs.forEach(element => {
        element.value = ""
        element.classList.remove('is-invalid')
    });
    result.value = "";
})

inputs.forEach((element, i) => {
    element.addEventListener('input', (e) => {
        const val = removeCommas(element)
        if(val == ""){
            feedback[i].innerHTML = "Preencha este campo."
            element.classList.add('is-invalid')
        }else if(isNaN(val)){
            feedback[i].innerHTML = "Valor inválido."
            element.classList.add('is-invalid')
        }else{
            element.classList.remove('is-invalid')
        }
    })
})

function validate(){
    var result = true
    inputs.forEach((element, i) => {
        if(element.value == ""){
            feedback[i].innerHTML = "Preencha este campo."
            element.classList.add('is-invalid')
            result = false;
        }
        if(element.classList.contains('is-invalid')){
            result = false
        }
    })
    return result;
}

function removeCommas(e){
    return e.value.trim().replace(',', '.')
}