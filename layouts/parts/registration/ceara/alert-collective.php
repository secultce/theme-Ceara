<div ng-repeat="def in data.entity.registrationAgents" ng-if="def.use != 'dontUse'">
    <div ng-if="def.relationStatus == 1">
    <script>
            //todos os relacionamentos de agentes
            let agentRelation = MapasCulturais.entity.registrationAgents;
            let collectiveId = 0;//iniciando com 0
            agentRelation.forEach(element => {
                //se tiver um grupo com o nome coletivo, busca o ai do agente
                if (element.agentRelationGroupName == 'coletivo') {
                    collectiveId = element.agent.id;//alterando o valor inicial
                }
            }); 
            //requisição para api buscando o pai do coletivo          
            $.ajax({
                type: "GET",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                url: MapasCulturais.baseURL + "api/agent/find?@select=id,name,parent&id=EQ(" + collectiveId + ")",
                success: function(res) {
                    //Para preencher o valor do pai do coletivo
                    let parentCollective = 0
                    res.forEach(element => {
                        parentCollective = element.parent
                    });
                    //se o id do agente é o mesmo do pai do coletivo, verifica algumas situações
                    if (MapasCulturais.userProfile.id == parentCollective) {
                        
                        let fields = MapasCulturais.entity.registrationFieldConfigurations
                        fields.forEach(element => {
                           //se tem um campo do tipo agente coletivo que seja de endereço e que estava sem valor
                            if(
                                element.fieldType == 'agent-collective-field' && 
                                element.config.entityField == '@location' &&
                                element.unchangedFieldJSON == "null"              
                            ){
                                //Alerta para usuário e recarregando a página
                                new PNotify({
                                    title: 'Aguarde!',
                                    text: 'Estamos buscando os dados do seu Coletivo',
                                    icon: 'error',
                                    type: 'info',
                                    shadow: true,
                                    stack: {"dir1": "down", "dir2": "right", "push": "bottom", "spacing1": 25, "spacing2": 25, "context": $("body"), "modal": true}
                                });
                                let url = MapasCulturais.createUrl('../inscricao',MapasCulturais.entity.id)
                                window.location.href = url;
                            }
                        });

                    }
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