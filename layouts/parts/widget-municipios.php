<?php
$entityClass = $entity->getClassName();
$entityName = strtolower(array_slice(explode('\\', $entityClass),-1)[0]);
$municipios = array_values($app->getRegisteredTaxonomy($entityClass, 'municipio')->restrictedTerms);
sort($municipios);
$selected = $entity->terms['municipio'];
?>
<?php if($this->isEditable() || !empty($selected)): ?>
    <div class="widget">
        <h3><?php echo "Municípios Contemplados" ?></h3>
        <?php if($this->isEditable()): ?>
            <span id="term-municipio" class="js-editable-taxonomy" data-original-title="<?php echo "Municípios Contemplados" ?>" data-emptytext="<?php echo "Informe, pelo menos, um município." ?>" data-restrict="true" data-taxonomy="municipio"><?php echo implode('; ', $selected)?></span>
        <?php else: ?>
            <?php foreach($municipios as $i => $t): if(in_array($t, $selected)): ?>
                <span class="tag tag-<?php echo $this->controller->id ?>">
                <?php echo $t ?>
            </span>
            <?php endif; endforeach; ?>
        <?php endif;?>
    </div>
<?php endif; ?>