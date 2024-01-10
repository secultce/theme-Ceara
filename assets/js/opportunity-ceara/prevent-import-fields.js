$(document).ready(() => {
    const btnSubmitFields = $('form[name=impotFields] [type=submit]')

    btnSubmitFields.on('click', e => {
        e.preventDefault()

        if(!$('form[name=impotFields] [name=fieldsFile]').val()) {
            MapasCulturais.Messages.alert('Preencha o campo com o arquivo que deseja importar!')
            return;
        }

        fetch(MapasCulturais.baseURL + 'quantidadecampos?opportunityId=' + MapasCulturais.entity.id)
            .then(response => response.json())
            .then(count => {
                if(count.error) {
                    MapasCulturais.Messages.error(count.data.message)
                } else {
                    if(count <= 0) {
                        $('form[name=impotFields]').submit()
                        return;
                    }

                    Swal.fire({
                        html: 'Campos já estão preenchidos nesta oportunidade. Se prosseguir, será feita a duplicação de quaisquer campos já existentes. <br/>' +
                            '<strong>Gostaria de continuar?</strong>',
                        confirmButtonText: 'Sim',
                        denyButtonText: 'Não',
                        showDenyButton: true,
                        reverseButtons: true,
                    })
                        .then(result => {
                            if(result.isConfirmed) $('form[name=impotFields]').submit()

                            $('form[name=impotFields]')[0].reset()
                        })
                }
            })
    })
})