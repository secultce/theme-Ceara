<h1 id="brand-logo">
    <?php if($app->config['app.mode'] == 'development') : ?>
    <a href="<?php echo $app->getBaseUrl() ?>"><img src="<?php $this->asset('img/logoHomolog.jpg'); ?>" /></a>
    <?php endif ?>
    <?php if($app->config['app.mode'] == 'production') : ?>
    <a href="<?php echo $app->getBaseUrl() ?>"><img src="<?php $this->asset('img/logo-ceara.png'); ?>" /></a>
    <?php endif ?>
    
</h1>
