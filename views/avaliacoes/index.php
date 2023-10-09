<?php

$this->layout = 'panel';

?>
<style>
    .form-control {
  display: block;
  width: 100%;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
  -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
  -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
}
.form-control:focus {
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, 0.6);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, 0.6);
}
.form-control::-moz-placeholder {
  color: #999;
  opacity: 1;
}
.form-control:-ms-input-placeholder {
  color: #999;
}
.form-control::-webkit-input-placeholder {
  color: #999;
}
.form-control::-ms-expand {
  background-color: transparent;
  border: 0;
}
.form-control[disabled],
.form-control[readonly],
fieldset[disabled] .form-control {
  background-color: #eeeeee;
  opacity: 1;
}
.form-control[disabled],
fieldset[disabled] .form-control {
  cursor: not-allowed;
}
textarea.form-control {
  height: auto;
}
</style>
<?php $this->applyTemplateHook('evaluations-force','before'); ?>
<div class="panel-list panel-main-content">
<?php $this->applyTemplateHook('evaluations-content','begin'); ?>

<header class="panel-header clearfix">
    <h2>Forças avaliações</h2>
</header>
    <form id="form-force-evaluations">
        <div>
            <label for="">Nº Inscrição</label>
            <input type="text" name="" id="" class="form-control">
            <label for="">Id Usuário</label>
            <input type="text" name="" id="" class="form-control">
            <label for="">Resultado (result)</label>
            <input type="text" name="" id="" class="form-control">
            <label for="">Dados da avaliação</label>
            <input type="text" name="" id="" class="form-control">
            <label for="">Status</label>
            <input type="text" name="" id="" class="form-control">
            <label for="">Data da Criação</label>
            <input type="text" name="" id="" class="form-control">
            <label for="">Data da Atualização</label>
            <input type="text" name="" id="" class="form-control">
            <button class="btn btn-primary" id="btn-submit-evaluations">Cadastrar Avaliação</button>
        </div>
    </form>
<?php $this->applyTemplateHook('evaluations-content','end'); ?>
</div>
<?php $this->applyTemplateHook('evaluations-force','after'); ?>