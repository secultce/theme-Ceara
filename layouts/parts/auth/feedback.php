<style>
    /* ESTILIZANDO PARA SUBISTITUIR O LAYOUT DO PLUGIN PARA ADD A MENSAGEM DO TEMA */
    .login-area > .alerta.sucesso{
        display: none
    }
    .login-area > .alerta.sucesso.feedback-auth {
        display: block
    }
    .close-feedback {
        margin-top: 13%;
    }
    #main-section {
        position: relative;
        background-color: #eee;
        margin-top: 0px !important;
    }
</style>

<div class="login-area">
 <?php if ($feedback_msg) : ?>
    <p class="close-feedback">
        <div class="alerta <?php echo $feedback_success ? 'sucesso' : 'erro'; ?> feedback-auth">
            <?php echo htmlentities($feedback_msg); ?>
            <span class="closebtn btn btn-default" onclick="this.parentElement.style.display='none';" style="font-size: 15px;
                border-radius: 15%;">&times;</span>
        </div>
        </p>
    <?php endif; ?>
</div>
