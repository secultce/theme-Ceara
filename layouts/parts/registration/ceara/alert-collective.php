<div ng-repeat="def in data.entity.registrationAgents" ng-if="def.use != 'dontUse'">
    <div ng-if="def.relationStatus == 1">
        <script>
            //Todos os campos da oportunidade
            let fields = MapasCulturais.entity.registrationFieldConfigurations
            //Loop para fazer verificação
            //se for campos do tipo agente coletivo, se for campo do tipo endereço e se nao tiver nada preenchido
            fields.forEach(element => {
                if (
                    element.fieldType == 'agent-collective-field' &&
                    element.config.entityField == '@location' &&
                    element.unchangedFieldJSON == "null"
                ) {
                    //Vai recarregar a página, mas com um aviso ao usuário
                    new PNotify({
                        title: 'Aguarde!',
                        text: 'Estamos buscando os dados do seu Coletivo',
                        icon: 'error',
                        type: 'info',
                        shadow: true,
                        stack: {
                            "dir1": "down",
                            "dir2": "right",
                            "push": "bottom",
                            "spacing1": 25,
                            "spacing2": 25,
                            "context": $("body"),
                            "modal": true
                        }
                    });
                    //Redirecionando a página
                    let url = MapasCulturais.createUrl('../inscricao', MapasCulturais.entity.id)
                    window.location.href = url;
                }
            });
        </script>
    </div>

    <div ng-if="def.relationStatus < 0" class="js-registration-agent registration-agent" ng-class="{pending: def.relationStatus < 0}">

        <div class="registration-fieldset alert info" style="border-radius: 15px;">
            <h5 ng-if="def.relationStatus < 0" id="" style="margin-left: 10px;">
                Você não é administrador/a deste Coletivo. Entre em contato com o/a responsável para a Liberação do mesmo, pois somente
                o envio da inscrição será realizado mediante essa ação.
            </h5>
        </div>
    </div>
</div>