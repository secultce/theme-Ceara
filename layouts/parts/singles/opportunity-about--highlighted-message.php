<div class="highlighted-message clearfix" id="opportunity-main-info">
    <?php $this->applyTemplateHook('tab-about--highlighted-message', 'begin'); ?>

    <?php if ($this->isEditable() || $entity->registrationFrom || $entity->registrationTo) : ?>
        <?php $this->part('singles/opportunity-about--registration-dates', ['entity' => $entity]) ?>
    <?php endif; ?>

    <!-- Mensagem não aparecerá para os agentes com permissão de editar a oportunidade -->
    <?php if ($entity->registrationLimit && !$entity->canUser('@control')) : ?>
        <div style="margin-top: 32px;">
            <?php \MapasCulturais\i::_e("Número máximo de vagas (inscrições): "); ?><b><?php echo $entity->registrationLimit; ?></b>
        </div>
    <?php endif; ?>

    <?php $this->applyTemplateHook('tab-about--highlighted-message', 'end'); ?>
</div>
