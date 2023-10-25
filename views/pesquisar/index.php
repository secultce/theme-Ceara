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
        <div  ng-app="agent-search">
            <div  ng-controller="AgentSearchController">            
                <div class="panel panel-default">
                    <div class="panel-heading">Pesquisa avançada de agentes</div>
                    <div class="panel-body">
                        <div id="tabs-1">
                            <div class="form-group">
                                <label for="">Pesquisar por </label>
                                <select name="selectSearch" class="form-control" id="" ng-model="selectedSearch" >
                                    <option value="null">--Selecione--</option>
                                    <option value="email">E-mail</option>
                                    <option value="cpf">CPF</option>
                                    <option value="dataDeNascimento" selected>Data de nascimento</option>
                                    <option value="cnpj">CNPJ</option>
                                </select>
                                <small>Select: {{selectedItem}}</small>
                            </div>
                            <div class="form-group">
                                <input type="text" value="27.950.673/0001-69" ng-model="inputSearch" class="form-control">
                            </div>
                            <div class="forn-group">
                                <button class="btn btn-success" ng-click="searchForm()">Pesquisar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Resultado da pesquisa</div>
                    <div class="panel-body">
                        <div class="bs-example" data-example-id="hoverable-table">
                            <div class="list-group" ng-repeat="(key, value) in result">
                               
                                <a href="#" class="list-group-item">
                                    <div style="display: flex; justify-content: space-between;">
                                    <h4 class="list-group-item-heading">Joao Belo Junior</h4>
                                        <button class="btn btn-info" ng-click="search($event)">Informação</button>
                                    </div>
                                        <p class="list-group-item-text sub-title-search" ng-repeat="(key2, value2) in value">
                                        {{value2.owner}}
                                        </p>    
                                </a>
                               
                            </div>
                            <!-- <div ng-controller="AgentSearchController">
                                <h1>Lista de itens</h1>
                                <ul>
                                    <li ng-repeat="item in data.items">{{item.title}} - <a>remover</a></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>