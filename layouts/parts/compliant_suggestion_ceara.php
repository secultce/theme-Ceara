<?php

use MapasCulturais\i;

$app = MapasCulturais\App::i();

$url = 'https://cearatransparente.ce.gov.br/portal-da-transparencia/ouvidoria';

// if(isset($app->config['module.CompliantSuggestion'])) {
//     $compliantUrl = !isset($app->config['module.CompliantSuggestion']['compliantUrl']) ? $app->config['module.CompliantSuggestion']['compliantUrl'] : '';
// }

if($this->controller->action === 'create')
    return false;
?>

<div class="compliant-suggestion-box-ceara">
    <a class="btn btn-warning" target="_blank" href="<?php echo $url?>"> <?php i::_e('Denunciar'); ?> </a>
    <a class="btn btn-success" target="_blank" href="<?php echo $url?>"> <?php i::_e('Contato'); ?> </a>
</div>
