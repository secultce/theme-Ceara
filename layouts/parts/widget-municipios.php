<?php
$entityClass = $entity->getClassName();
$entityName = strtolower(array_slice(explode('\\', $entityClass),-1)[0]);
$municipios = array_values($app->getRegisteredTaxonomy($entityClass, 'municipio')->restrictedTerms);
sort($municipios);
?>
<?php if($this->isEditable() || !empty($municipios)): ?>
<div class="widget">
    <h3><?php echo "Municípios Contemplados" ?></h3>
    <?php if($this->isEditable()): ?>
        <span id="term-area" class="js-editable-taxonomy" data-original-title="<?php echo "Municípios Contemplados" ?>" data-emptytext="<?php echo "Informe, pelo menos, um município." ?>" data-restrict="true" data-taxonomy="municipio"><?php echo implode('; ', $entity->terms['municipio'])?></span>
    <?php else: ?>
        <?php
        foreach($municipios as $i => $t): if(in_array($t, $entity->terms['municipio'])): ?>
            <a class="tag tag-<?php echo $this->controller->id ?>" href="<?php echo $app->createUrl('site', 'search') ?>##(<?php echo $entityName ?>:(areas:!(<?php echo $i ?>)),global:(enabled:(<?php echo $entityName ?>:!t),filterEntity:<?php echo $entityName ?>))">
                <?php echo $t ?>
            </a>
        <?php endif; endforeach; ?>
    <?php endif;?>
</div>
<?php endif; ?>