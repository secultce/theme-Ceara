$(document).ready(() => {
    vex.defaultOptions.className = 'vex-theme-os'

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

                    vex.dialog.buttons.YES.text = 'Sim';
                    vex.dialog.buttons.NO.text = 'Cancelar';
                    vex.dialog.confirm({
                        message: 'Já existem campos nessa oportunidade. Se importar, poderá duplicar quaisquer campos que já existirem. Deseja continuar?',
                        callback: confirmed => confirmed ? $('form[name=impotFields]').submit() : $('form[name=impotFields]')[0].reset(),
                    })
                }
            })
    })
})