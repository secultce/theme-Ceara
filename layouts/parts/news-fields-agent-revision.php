<?php if(isset($entityRevision->escolaridade) && $entityRevision->userCanView): ?>
<p class="privado">
	<span class="icon icon-private-info"></span> <span class="label">Escolaridade</span>
	<span class="js-editable" data-edit="escolaridade"
		data-original-title="Escolaridade"
		data-emptytext="Selecione seu nível de escolaridade se for pessoa física">
		<?php echo $entityRevision->escolaridade; ?></span>
</p>
<?php endif;?>