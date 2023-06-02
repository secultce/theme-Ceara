<?php

use MapasCulturais\App;
use MapasCulturais\i;

$route = App::i()->createUrl('opportunity', 'reportResultEvaluationsDocumental', [$entity->id]);
$route_btn_antigo = App::i()->createUrl('opportunity', 'reportOld', [$entity->id]);
?>

<!-- <a class="btn btn-default download btn-report-evaluation-documental"  ng-click="editbox.open('report-evaluation-documental-options', $event)" rel="noopener noreferrer">Imprimir Resultado</a> -->

<!--Botão antigo-->
<a class="btn btn-default download" href="<?php echo $route_btn_antigo ?>"><?php i::_e("Baixar inscritos"); ?>(Botão antigo)</a>


<!-- Formulário -->
<!-- <edit-box id="report-evaluation-documental-options" position="top" title="<?php i::esc_attr_e('Imprimir Resultado') ?>" cancel-label="Cancelar" close-on-cancel="true">
    <form class="form-report-evaluation-documental-options" action="<?= $route ?>" method="POST">

        <label for="publishDate">Data publicação</label>
        <input type="date" name="publishDate" id="publishDate">

        <label for="from">Formato</label>
        <select name="fileFormat" id="fileFormat">
            <option value="pdf" selected >PDF</option>
            <option value="xls">XLS</option>
            <option value="doc">DOC</option>
        </select>

        <button class="btn btn-primary download" type="submit">Imprimir Resultado</button>
    </form>
</edit-box> -->