<?php
$entityClass = $entity->getClassName();
$entityName = strtolower(array_slice(explode('\\', $entityClass),-1)[0]);
$publicos_alvo = array_values($app->getRegisteredTaxonomy($entityClass, 'publico')->restrictedTerms);
sort($publicos_alvo);
$selected = $entity->terms['publico'];
?>
<?php if($this->isEditable() || !empty($selected)): ?>
<div class="widget">
    <h3><?php echo "Público-Alvo" ?></h3>
    <?php if($this->isEditable()): ?>
        <span id="term-area" class="js-editable-taxonomy" data-original-title="<?php echo "Público-Alvo" ?>" data-emptytext="<?php echo "Informe, pelo menos, um Público-Alvo" ?>" data-restrict="true" data-taxonomy="publico"><?php echo implode('; ', $selected)?></span>
    <?php else: ?>
        <?php foreach($publicos_alvo as $i => $t): if(in_array($t, $selected)): ?>
            <a class="tag tag-<?php echo $this->controller->id ?>">
                <?php echo $t ?>
            </a>
        <?php endif; endforeach; ?>
    <?php endif;?>
</div>
<?php endif; ?>