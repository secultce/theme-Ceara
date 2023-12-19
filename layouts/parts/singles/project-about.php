<?php
$editEntity = $this->controller->action === 'create' || $this->controller->action === 'edit';
?>

<div id="sobre" class="aba-content">
    <?php $this->applyTemplateHook('tab-about','begin'); ?>

    <?php $this->part('singles/project-about--highlighted-message', ['entity' => $entity]) ?>

    <div class="ficha-spcultura">
        <?php if($this->isEditable() && $entity->shortDescription && strlen($entity->shortDescription) > 900): ?>
            <div class="alert warning">
                <?php \MapasCulturais\i::_e("O limite de caracteres da descrição curta foi diminuido para 900, mas seu texto atual possui");?>
                <?php echo strlen($entity->shortDescription) ?>

                <?php \MapasCulturais\i::_e("caracteres. Você deve alterar seu texto ou este será cortado ao salvar.");?>
            </div>
        <?php endif; ?>

        <p>
            <span
                class="js-editable
                <?php echo ($entity->isPropertyRequired($entity,"shortDescription") && $editEntity? 'required': '');?>"
                data-edit="shortDescription"
                data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descrição Curta");?>"
                data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira uma descrição curta");?>"
                data-tpl='<textarea maxlength="900"></textarea>'><?php echo $this->isEditable() ? $entity->shortDescription : nl2br($entity->shortDescription); ?>
            >
            </span>
        </p>
        <?php $this->applyTemplateHook('tab-about-service','before'); ?>

        <div class="servico">
            <?php $this->applyTemplateHook('tab-about-service','begin'); ?>
            <?php if($this->isEditable() || $entity->site): ?>
                <p>
                    <span class="label <?php echo ($entity->isPropertyRequired($entity,"site") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Site");?>:</span>
                    <span ng-if="data.isEditable" class="js-editable" data-edit="site" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Site");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira a url de seu site");?>"><?php echo $entity->site; ?></span>
                    <a ng-if="!data.isEditable" class="url" href="<?php echo $entity->site; ?>"><?php echo $entity->site; ?></a>
                </p>
            <?php endif; ?>

            <?php if($this->isEditable() || $entity->valor): ?>
                <p>
                    <span class="label"><?php \MapasCulturais\i::_e("Valor (R$)");?>:</span>
                    <span
                        class="js-editable <?php echo ($entity->isPropertyRequired($entity,"valor") && $editEntity? 'required': '');?>"
                        data-edit="valor" data-original-title="<?php \MapasCulturais\i::esc_attr_e("valor do projeto em (R$)");?>"
                        data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Informe o valor do projeto em (R$)");?>">
                        <?php echo $entity->valor; ?>
                    </span>
                </p>
            <?php endif; ?>

            <?php $this->applyTemplateHook('tab-about-service','end'); ?>
        </div>

        <?php $this->applyTemplateHook('tab-about-service','after'); ?>
    </div>

    <?php if ( $this->isEditable() || $entity->longDescription ): ?>
        <h4 class="<?php echo ($entity->isPropertyRequired($entity,"longDescription") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Descrição");?></h4>
        <span class="descricao js-editable" data-edit="longDescription" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descrição do Projeto");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira uma descrição do projeto");?>" ><?php echo $this->isEditable() ? $entity->longDescription : nl2br($entity->longDescription); ?></span>
    <?php endif; ?>

    <?php if ( $this->isEditable() || $entity->contraPartida ): ?>
        <h4 class="<?php echo ($entity->isPropertyRequired($entity,"contraPartida") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("ContraPartida");?></h4>
        <span class="descricao js-editable" data-edit="contraPartida" data-original-title="<?php \MapasCulturais\i::esc_attr_e("ContraPartida");?>"data-emptytext="<?php \MapasCulturais\i::esc_attr_e("O projeto possui ContraPartida ? descreva aqui.");?>" ><?php echo $this->isEditable() ? $entity->contraPartida : nl2br($entity->contraPartida); ?></span>
    <?php endif; ?>

    <!-- Video Gallery BEGIN -->
    <?php $this->part('video-gallery.php', array('entity'=>$entity)); ?>
    <!-- Video Gallery END -->

    <!-- Image Gallery BEGIN -->
    <?php $this->part('gallery.php', array('entity'=>$entity)); ?>
    <!-- Image Gallery END -->

    <?php $this->applyTemplateHook('tab-about','end'); ?>
</div>
<!-- #sobre -->

