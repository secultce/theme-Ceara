$(document).ready(() => {
    const btnSubmitFields = $('form[name=impotFields] [type=submit]')


    btnSubmitFields.on('click', e => {
        e.preventDefault();

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
                        html: 'Campos já estão preenchidos nesta oportunidade. Se prosseguir, será feita a duplicação de quaisquer campos já existentes. <strong>Gostaria de continuar?</strong>',
                        confirmButtonText: 'Sim',
                        denyButtonText: 'Cancelar',
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