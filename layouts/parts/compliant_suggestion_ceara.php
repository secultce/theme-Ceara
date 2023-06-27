<?php

use MapasCulturais\i;

$app = MapasCulturais\App::i();

$url = 'https://cearatransparente.ce.gov.br/portal-da-transparencia/ouvidoria';

// if(isset($app->config['module.CompliantSuggestion'])) {
//     $compliantUrl = !isset($app->config['module.CompliantSuggestion']['compliantUrl']) ? $app->config['module.CompliantSuggestion']['compliantUrl'] : '';
// }

if ($this->controller->action === 'create')
    return false;
?>

<div class="compliant-suggestion-box-ceara">
    <a class="btn btn-warning" target="_blank" href="<?php echo $url ?>"> <?php i::_e('Denunciar'); ?> </a>
    <button ng-show="!data.showForm" ng-click="data.showForm = 'suggestion'" class="button-form-compliant-suggestion suggestion btn-success"><?php i::_e('Contato'); ?></button>
</div>