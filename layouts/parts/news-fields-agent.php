<?php if($this->isEditable()): ?>
<p class="privado">
	<span class="icon icon-private-info"></span><span class="label"><?php \MapasCulturais\i::_e("Escolaridade");?>:</span>
	<span
		class="js-editable <?php echo ($entity->isPropertyRequired($entity,"escolaridade") && $editEntity? 'required': '');?>"
		data-edit="escolaridade"
		data-original-title="<?php \MapasCulturais\i::esc_attr_e("Escolaridade");?>"
		data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Selecione sua escolaridade");?>"><?php echo $entity->escolaridade; ?></span>
</p>
<?php endif; ?>
