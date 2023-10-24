<?php

use MapasCulturais\App;

$this->layout = 'panel';
if (!$app->user->is('admin'))
    $app->pass();
?>
<script>
    $(function() {
        $("#tabs").tabs();
    });
</script>
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

        <div class="user-managerment-search clearfix">
        <div class="panel panel-default">
  <div class="panel-heading">Pesquisa avançada de agentes</div>
  <div class="panel-body">
  <div id="tabs-1">                   
                    <div class="form-group">
                        <label for="">Pesquisar por </label>
                        <select name="" class="form-control" id="">
                            <option value="">--Selecione--</option>
                            <option value="">E-mail</option>
                            <option value="">CPF</option>
                            <option value="">Data de nascimento</option>
                            <option value="">CNPJ</option>
                        </select>
                    </div>
                    <div class="form-group">                        
                        <input type="text" class="form-control"  >
                    </div>
                    <div class="forn-group">
                        <button class="btn btn-success">Pesquisar</button>
                    </div>
                </div>
  </div>
</div>
            
        </div>
    </div>
</div>