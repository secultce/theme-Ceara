<?php $status = $entity->status; ?>

<?php if($status != '10'):?>
    <div class="registration-fieldset">
        <h4>MOTIVO(S):</h4>                    
        <ul>
            <?php
                foreach($evaluations as $e) {
                    $data = (array) $e->evaluationData;
                    if( (isset($data['obs']) && !empty($data['obs'])) && $data['status'] != '10' ) print "<li>{$data['obs']}</li>";
                }
            ?>
        </ul>
    </div>
<?php endif; ?>  