<div ng-repeat="def in data.entity.registrationAgents" ng-if="def.use != 'dontUse'">
    <div ng-if="def.relationStatus < 0"  class="js-registration-agent registration-agent" ng-class="{pending: def.relationStatus < 0}">
        <div class="registration-fieldset alert info" style="border-radius: 15px;">
            <h5 ng-if="def.relationStatus < 0" id="" style="margin-left: 10px;"> 
                Você não é administrador/a deste Coletivo. Entre em contato com o/a responsável para a Liberação do mesmo, pois somente
                o envio da inscrição será realizado mediante essa ação.
            </h5>
        </div>
    </div>
</div>
