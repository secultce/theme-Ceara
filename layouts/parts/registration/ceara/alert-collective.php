<style>
    .btn-conf-collective {
        color: #fff;
        border: 1px solid #519863 !important;
        border-radius: 3px !important;
        background-color: #076d21 !important;
        cursor: pointer;
    }

    .ui-pnotify-action-bar button:hover {
        color: #fff;
        border: 1px solid #076d21 !important;
        border-radius: 3px !important;
        background-color: #519863 !important;
        cursor: pointer;
    }

    .btn-perfil-collective {
        color: #076d21;
        background-color: #edf4ef !important;
        border-radius: 3px;
        cursor: pointer;
    }
</style>

<div ng-repeat="def in data.entity.registrationAgents" ng-if="def.use != 'dontUse'">

    <div ng-if="def.agentRelationGroupName == 'coletivo' && def.relationStatus == 1">

        <script>
            //Somente para quando for preenchimento da 
            if (MapasCulturais.isEditable) {
                let agentRelation = MapasCulturais.entity.registrationAgents;
                let collectiveId = 0; //iniciando com 0
                agentRelation.forEach(element => {
                    //se tiver um grupo com o nome coletivo, busca o ai do agente
                    if (element.agentRelationGroupName == 'coletivo' && element.agent !== null) {
                        collectiveId = element.agent.id; //alterando o valor inicial
                    }
                });

                //requisição para api buscando o pai do coletivo          
                $.ajax({
                    type: "GET",
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    url: MapasCulturais.baseURL + "api/agent/find?@select=id,name,parent,endereco&id=EQ(" + collectiveId + ")",
                    success: function(res) {
                        //Para preencher o valor do pai do coletivo
                        let parentCollective = 0
                        let address = ""
                        res.forEach(element => {
                            parentCollective = element.parent
                            address = element.endereco
                        });

                        //se o id do agente é o mesmo do pai do coletivo, verifica algumas situações
                        if (MapasCulturais.userProfile.id == parentCollective) {
                            //Todos os campos da oportunidade
                            let fields = MapasCulturais.entity.registrationFieldConfigurations

                            fields.forEach(element => {
                                //se tem um campo do tipo agente coletivo que seja de endereço e que estava sem valor
                                if (
                                    element.fieldType == 'agent-collective-field' &&
                                    element.config.entityField == '@location'
                                ) {
                                    //Se o agente coletivo estiver sem endereço
                                    if (address == null) {
                                        new PNotify({
                                            title: 'Ops, está faltando o endereço!',
                                            text: 'Seu coletivo está sem endereço. Você pode alterar o perfil, ou preenher na inscrição. O que deseja fazer?',
                                            type: 'info',
                                            icon: 'fas fa-circle-info',
                                            hide: false,
                                            confirm: {
                                                confirm: true,
                                                buttons: [{
                                                        text: 'Inserir aqui',
                                                        addClass: 'btn-conf-collective ',
                                                        click: function(notice) {
                                                            notice.remove();
                                                        }
                                                    },
                                                    {
                                                        text: 'Inserir no perfil',
                                                        addClass: 'btn-perfil-collective',
                                                        click: function(notice) {
                                                            let url = MapasCulturais.createUrl('agente', collectiveId)
                                                            window.location.href = url
                                                        }
                                                    },
                                                ]
                                            },
                                            buttons: {
                                                closer: false,
                                                sticker: false
                                            },
                                            history: {
                                                history: false
                                            }
                                        })
                                        return;
                                    } else if (element.unchangedFieldJSON.length > 0) {
                                        //Verificação de existe valores dentro dos campos de endereço
                                        const existAddress = JSON.parse(element.unchangedFieldJSON);

                                        if (existAddress == null) {
                                            //Alerta para usuário e recarregando a página
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
                                            let url = MapasCulturais.createUrl('../inscricao', MapasCulturais.entity.id)
                                            window.location.href = url;
                                        }

                                    }

                                }
                            });

                        }
                    }
                })
            }
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