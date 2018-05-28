<?php
$entityClass = $entity->getClassName();
$entityName = strtolower(array_slice(explode('\\', $entityClass),-1)[0]);
$areas = array_values($app->getRegisteredTaxonomy($entityClass, 'publico')->restrictedTerms);
sort($areas);
?>
<div class="widget">
    <h3><?php echo "Público-Alvo" ?></h3>
    <?php if($this->isEditable()): ?>
        <span id="term-area" class="js-editable-taxonomy" data-original-title="<?php echo "Público-Alvo" ?>" data-emptytext="<?php echo "Selecione pelo menos um Público-Alvo" ?>" data-restrict="true" data-taxonomy="publico"><?php echo implode('; ', $entity->terms['publico'])?></span>
    <?php else: ?>
        <?php
        foreach($areas as $i => $t): if(in_array($t, $entity->terms['publico'])): ?>
            <a class="tag tag-<?php echo $this->controller->id ?>" href="<?php echo $app->createUrl('site', 'search') ?>##(<?php echo $entityName ?>:(areas:!(<?php echo $i ?>)),global:(enabled:(<?php echo $entityName ?>:!t),filterEntity:<?php echo $entityName ?>))">
                <?php echo $t ?>
            </a>
        <?php endif; endforeach; ?>
    <?php endif;?>
</div>