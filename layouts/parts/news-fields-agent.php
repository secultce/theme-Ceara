<?php if($this->isEditable()): ?>
<p class="privado">
	<span class="icon icon-private-info"></span><span class="label"><?php \MapasCulturais\i::_e("Escolaridade");?>:</span>
	<span
		class="js-editable <?php echo ($entity->isPropertyRequired($entity,"escolaridade") && $editEntity? 'required': '');?>"
		data-edit="escolaridade"
		data-original-title="<?php \MapasCulturais\i::esc_attr_e("Escolaridade");?>"
		data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Selecione sua escolaridade");?>"><?php echo $entity->escolaridade; ?></span>
</p>

<p class="privado">
	<span class="icon icon-private-info"></span><span class="label"><?php \MapasCulturais\i::_e("Estado Civil");?>:</span>
	<span
		class="js-editable <?php echo ($entity->isPropertyRequired($entity,"estadoCivil") && $editEntity? 'required': '');?>"
		data-edit="estadoCivil"
		data-original-title="<?php \MapasCulturais\i::esc_attr_e("Estado Civil");?>"
		data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Selecione seu estado civil se for pessoa física");?>"><?php echo $entity->estadoCivil; ?></span>
</p>

<p class="privado">
	<span class="icon icon-private-info"></span><span class="label"><?php \MapasCulturais\i::_e("Identidade (RG)");?>:</span>
	<span
		class="js-editable <?php echo ($entity->isPropertyRequired($entity,"identidade") && $editEntity? 'required': '');?>"
		data-edit="identidade"
		data-original-title="<?php \MapasCulturais\i::esc_attr_e("Número da Identidade (RG)");?>"
		data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira o número de sua identidade (RG) se for pessoa física");?>"><?php echo $entity->identidade; ?></span>
</p>
<?php endif; ?>
