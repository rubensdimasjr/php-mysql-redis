const nsenha = document.querySelector('#nsenha');
const csenha = document.querySelector('#csenha');
const submit = document.querySelector('.submitSenha');

csenha.addEventListener("input", (e) => {
  if(csenha.value !== nsenha.value){
    csenha.classList.remove('is-valid');
    csenha.classList.add('is-invalid');
  }else if(csenha.value === nsenha.value && csenha.classList.contains('is-invalid')){
    csenha.classList.remove('is-invalid');
    csenha.classList.add('is-valid');
  }else{
    csenha.classList.remove('is-invalid');
    csenha.classList.add('is-valid');
  }
});

submit.addEventListener("click", (e) => {
  if(csenha.classList.contains('is-invalid')){
    e.preventDefault()
  }else{
    $(submit).unbind('click').click();
  }
});
