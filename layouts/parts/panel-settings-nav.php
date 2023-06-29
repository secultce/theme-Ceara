<?php $this->applyTemplateHook('settings-nav','before'); ?>
<nav id="panel-settings-nav" class="sidebar-panel">
    <?php $this->applyTemplateHook('settings-nav','begin'); ?>
    
    <?php $this->applyTemplateHook('settings-nav','end'); ?>

    <?php if ($app->config['maintenance.enabled']): ?>
    <div class="maintenance-message" style="border-radius: 5px;box-shadow: 7px 7px 13px 0px rgba(50, 50, 50, 0.22);padding:5px;margin: 10px;width: 320px;text-align:justify; background-color:#DC143C;color:white">
        <b><?php echo $app->config['maintenance.message']; ?></b>
    </div>
<?php endif; ?>
<br>
</nav>
<?php $this->applyTemplateHook('settings-nav','after'); ?>