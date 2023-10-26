<?php

use MapasCulturais\App;

$this->layout = 'panel';
if (!$app->user->is('admin'))
    $app->pass();
?>

<div class="panel-list panel-main-content">
    <div class="box user-managerment">
        <header class="panel-header clearfix">
            <h2>
                <a class="icon icon-return" href="<?php echo $app->createUrl('panel', 'userManagement') ?>"> </a>
                <a href="<?php echo $app->createUrl('panel', 'userManagement') ?>">
                    <?php \MapasCulturais\i::_e("Voltar"); ?>
                </a>
            </h2>
        </header>
        <div ng-app="agent-search">
            <div ng-controller="AgentSearchController">
                <div class="panel panel-default">
                    <div class="panel-heading">Pesquisa avançada de agentes</div>
                    <div class="panel-body">
                        <div id="tabs-1">
                            <div class="form-group">
                                <label for="">Pesquisar por </label>
                                <select name="selectSearch" class="form-control" id="" ng-model="selectedSearch" ng-change="clearInput()">
                                    <option value="email">E-mail</option>
                                    <option value="cpf">CPF</option>
                                    <option value="dataDeNascimento">Data de nascimento</option>
                                    <option value="cnpj">CNPJ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Valor da pesquisa</label>
                                <input type="text" ng-model="inputSearch" class="form-control" placeholder="Preencher com mascara ou formato de Data">

                                <div class="forn-group">
                                    <button class="btn btn-success btn-search-all" ng-click="searchForm()">Pesquisar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Resultado da pesquisa</div>
                        <div class="panel-body">
                            <div class="bs-example" data-example-id="hoverable-table">
                                <div ng-if="result[0].length == 0">
                                    <h5 for="">Busca sem resultado.</h5>
                                </div>
                                <div ng-repeat="(key, value) in result" ng-if="selectedSearch !== 'email' && result">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item" ng-repeat="(key2, value2) in value">
                                            <div style="display: flex; justify-content: space-between;">
                                                <h4 class="list-group-item-heading"> {{value2.name}}</h4>
                                                <button class="btn btn-info" ng-click="searchItem(value2.id)">Informação</button>
                                            </div>
                                            <p class="list-group-item-text sub-title-search">
                                                <strong>Descrição:</strong> {{value2.longDescription}}
                                            </p>
                                        </a>
                                    </div>
                                </div>

                                <div class="list-group" ng-if="selectedSearch == 'email' && result">
                                    <a href="#" class="list-group-item" ng-repeat="(key, value) in result">
                                        <div style="display: flex; justify-content: space-between;">
                                            <h4 class="list-group-item-heading"> {{value.name}}</h4>
                                            <button class="btn btn-info" ng-click="searchItem(value.id)">Informação</button>
                                        </div>
                                        <p class="list-group-item-text sub-title-search">
                                            <strong>Descrição:</strong> {{value.longDescription}}
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>