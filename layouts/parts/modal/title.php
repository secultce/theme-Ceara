<?php if ($title == 'Nome') : ?>
    <label> <?php echo 'Nome Pessoal, Social, Artístico ou Profissional'
            ?></label> </label> <span class='modal-required'>*</span> <br>
<?php else : ?>
    <label> <?php echo $title
            ?></label> </label> <span class='modal-required'>*</span> <br>
<?php endif; ?>