$(document).ready(() => {
    const evaluationInfoElement = document.createElement('div')
    evaluationInfoElement.classList.add('alert', 'info')
    evaluationInfoElement.innerHTML = "<p><strong>Atenção!</strong></p>" +
        "Ao excluir um avaliador, todas as <strong>avaliações</strong> dessa oportunidade realizadas por esse avaliador também <strong>serão excluídas</strong>."

    $('.registration-fieldset #status-info')[0].after(evaluationInfoElement)
})
