<?php if(isset($entityRevision->escolaridade) && $entityRevision->userCanView): ?>
<p class="privado">
	<span class="icon icon-private-info"></span> <span class="label">Escolaridade:</span>
	<span class="js-editable" data-edit="escolaridade"
		data-original-title="Escolaridade">
		<?php echo $entityRevision->escolaridade; ?>
	</span>
</p>
<?php endif;?>

 <?php if(isset($entityRevision->estadoCivil) && $entityRevision->userCanView): ?>
<p class="privado">
	<span class="icon icon-private-info"></span> <span class="label">Estado
		Civil:</span> <span class="js-editable" data-edit="estadoCivil"
		data-original-title="Estado Civil"><?php echo $entityRevision->estadoCivil; ?>
	</span>
</p>
<?php endif;?>

<?php if(isset($entityRevision->identidade) && $entityRevision->userCanView): ?>
    <p class="privado">
        <span class="icon icon-private-info"></span>
        <span class="label">Identidade (RG):</span>
        <span class="js-editable" data-edit="identidade" data-original-title="Número da Identidade (RG)">
            <?php echo $entityRevision->identidade; ?>
        </span>
    </p>
    <p class="privado">
        <span class="icon icon-private-info"></span>
        <span class="label">Data de Expedição (RG):</span>
        <span class="js-editable" data-edit="expedicaoIdentidade" data-original-title="Data de Expedição (RG)">
            <?php echo $entityRevision->expedicaoIdentidade; ?>
	</span>
    </p>
    <p class="privado">
        <span class="icon icon-private-info"></span>
        <span class="label">Órgão Expedidor (RG):</span>
        <span class="js-editable" data-edit="expedidorIdentidade" data-original-title="Órgão Expedidor (RG)">
            <?php echo $entityRevision->expedidorIdentidade; ?>
	</span>
    </p>
<?php endif;?>