$( document ).ready(function() {
    const btnRegister = document.querySelector("#opportunity-registration a.btn")

    btnRegister?.addEventListener('click', e => {
        const inputAgent = document.querySelector('#select-registration-owner-button').innerText

        if(inputAgent !== 'Agente responsável pela inscrição') {
            e.target.setAttribute('style','cursor: not-allowed;pointer-events: none;background:#fff')
            e.target.innerHTML = `<img src="${MapasCulturais.spinnerURL}" />`
        }
    })
});

