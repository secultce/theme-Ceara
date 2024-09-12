<?php

use MapasCulturais\Entities\Registration as R;
use MapasCulturais\Entities\Agent;
use MapasCulturais\i;
use MapasCulturais\Utils;

ini_set('memory_limit', '256M');
ini_set('max_execution_time', '200');

$app->disableAccessControl();


function echoStatus($registration)
{
    switch ($registration->status) {
        case R::STATUS_APPROVED:
            i::_e('selecionada');
            break;

        case R::STATUS_NOTAPPROVED:
            i::_e('não selecionada');
            break;

        case R::STATUS_WAITLIST:
            i::_e('suplente');
            break;

        case R::STATUS_INVALID:
            i::_e('inválida');
            break;

        case R::STATUS_SENT:
            i::_e('pendente');
            break;
    }
}

function showIfField($hasField, $showField)
{
    if ($hasField)
        echo "<th>" . $showField . "</th>";
}

$_properties = $app->config['registration.propertiesToExport'];
$custom_fields = [];
foreach ($entity->registrationFieldConfigurations as $field) :
    $custom_fields[$field->displayOrder] = [
        'title' => $field->title,
        'field_name' => $field->getFieldName()
    ];
endforeach;

ksort($custom_fields);

$metas_individual = [];
$metas_coletivo = [];

$metas = $app->getRegisteredMetadata('MapasCulturais\Entities\Agent', 1);
foreach ($metas as $metadata) {
    $metas_individual[] = $metadata->key;
}

$metas = $app->getRegisteredMetadata('MapasCulturais\Entities\Agent', 2);
foreach ($metas as $metadata) {
    $metas_coletivo[] = $metadata->key;
}

?>
<style>
    tbody td,
    table th {
        text-align: left !important;
        border: 1px solid black !important;
    }
</style>

<table>
    <thead>
        <tr>
            <th> <?php i::_e("Número") ?> </th>

            <?php showIfField($entity->projectName, i::__("Nome do projeto")); ?>

            <th> <?php i::_e(Utils::getTermsByOpportunity("Avaliação", $entity)) ?> </th>
            <th><?php i::_e("Status") ?></th>
            <th><?php i::_e("Inscrição - Data de envio") ?></th>
            <th><?php i::_e("Inscrição - Hora de envio") ?></th>
            <?php showIfField($entity->registrationCategories, $entity->registrationCategTitle); ?>

            <?php
            foreach ($custom_fields as $field)
                echo "<th>" . $field['title'] . "</th>";
            ?>

            <th><?php i::_e('Anexos') ?></th>
            <?php foreach ($entity->getUsedAgentRelations() as $def) : ?>
                <th><?php echo $def->label; ?> - <?php i::_e("Código") ?></th>

                <th><?php echo $def->label; ?> - <?php i::_e("Nome") ?></th>

                <th><?php echo $def->label; ?> - <?php i::_e("Área de Atuação") ?></th>
                <?php $mdata = ($def->type == 1) ? $metas_individual : $metas_coletivo; ?>
                <?php foreach ($_properties as $prop) : ?>
                    <?php
                    if ($prop === 'name') continue;
                    if (!in_array($prop, $mdata)) continue;
                    ?>
                    <th><?php echo $def->label; ?> - <?php echo Agent::getPropertyLabel($prop); ?></th>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entity->sentRegistrations as $r) : ?>
            <tr>
                <td><a href="<?php echo $r->singleUrl; ?>" target="_blank"><?php echo $r->number; ?></a></td>
                <?php if ($entity->projectName) : ?>
                    <td><?php echo $r->projectName ?></td>
                <?php endif; ?>
                <td><?php echo $r->getEvaluationResultString(); ?></td>
                <td><?php echoStatus($r); ?></td>
                <?php $dataHoraEnvio = $r->sentTimestamp; ?>
                <td><?php echo (!is_null($dataHoraEnvio)) ? $dataHoraEnvio->format('d-m-Y') : ''; ?></td>
                <td><?php echo (!is_null($dataHoraEnvio)) ? $dataHoraEnvio->format('H:i:s') : ''; ?></td>

                <?php showIfField($entity->registrationCategories, $r->category); ?>

                <?php
                foreach ($custom_fields as $field) :
                    try {
                        $_field_val = (isset($field["field_name"])) ? $r->{$field["field_name"]} : "";

                        if (is_array($_field_val) && isset($_field_val[0]) && $_field_val[0] instanceof stdClass) {
                            $_field_val = (array)$_field_val[0];
                        }
                        echo "<th>";
                        echo (is_array($_field_val)) ? implode(", ", $_field_val) : $_field_val;
                        echo "</th>";
                    } catch (Exception $e) {
                        // echo "<th>";
                        // echo (is_array($_field_val) && is_array($field)) ? implode(", ", $_field_val) && implode(", ", $field) : $_field_val;
                        // echo "</th>";
                        //var_dump($_field_val);
                        //echo $_field_val;

                        // if ($_field_val["Endereço Completo"] || $_field_val[27]) {
                        //     continue;
                        // }
                        //print_r($_field_val);
                    }
                endforeach;
                ?>
                <td>
                    <?php if (key_exists('zipArchive', $r->files)) : ?>
                        <a href="<?php echo $r->files['zipArchive']->url; ?>"><?php i::_e("zip"); ?></a>
                    <?php endif; ?>
                </td>

                <!--ajuste--->
                <?php
                foreach ($r->_getDefinitionsWithAgents() as $def) :
                    if ($def->use == 'dontUse') continue;
                    $agent = $def->agent;
                    $agentsData = $r->getAgentsData();
                    $mdata = ($def->type == 1) ? $metas_individual : $metas_coletivo;
                    $agentsDataGroup = [];
                    if (!empty($agent) && !empty($agentsData)) {
                        $agentsDataGroup = (isset($agentsData[$def->agentRelationGroupName])) ? $agentsData[$def->agentRelationGroupName] : [];
                    }
                ?>
                    <?php if ($agent) : ?>
                        <td><?php echo $agent->id; ?></td>
                        <td><a href="<?php echo $agent->singleUrl; ?>" target="_blank"><?php echo $agent->name; ?></a></td>

                        <td><?php echo implode(', ', $agent->terms['area']); ?></td>

                        <?php
                        foreach ($_properties as $prop) :
                            if ($prop === 'name') continue;
                            if (!in_array($prop, $mdata)) continue;
                            $val = $agent->$prop;
                        ?>
                            <td>
                                <?php
                                if ($prop === 'location')
                                    echo (isset($val['latitude']) && isset($val['longitude'])) ? "{$val['latitude']},{$val['longitude']}" : '';
                                else
                                    echo $val;
                                ?>
                            </td>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php
                        echo str_repeat('<td></td>', 3);
                        foreach ($_properties as $prop) {
                            if ($prop === 'name') continue;
                            if (!in_array($prop, $mdata)) continue;
                            echo '<td></td>';
                        }
                        ?>
                    <?php endif; ?>
                <?php endforeach;  ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $app->enableAccessControl(); ?>
