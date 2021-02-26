<?php
$site_name = $this->dict('site: name', false);
$title = isset($entity) ? $this->getTitle($entity) : $this->getTitle()
?>
<!DOCTYPE html>
<html lang="<?php echo $app->getCurrentLCode(); ?>" dir="ltr">
    <head>
        <meta charset="UTF-8" />
        <title><?php echo $title == $site_name ? $title : "{$site_name} - {$title}"; ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="shortcut icon" href="<?php $this->asset('img/favicon.ico') ?>" />
        <?php $this->head(isset($entity) ? $entity : null); ?>
        <!--[if lt IE 9]>
        <script src="<?php $this->asset('js/html5.js'); ?>" type="text/javascript"></script>
        <![endif]-->
    </head>
                      
    <body <?php $this->bodyProperties() ?> >
    
       <?php $this->bodyBegin(); ?> 
        <header id="main-header" class="clearfix"  ng-class="{'sombra':data.global.viewMode !== 'list'}">
            <?php $this->part('header-logo') ?>
            <?php $this->part('header-about-nav') ?>
            <?php $this->part('header-main-nav') ?> 
        <!-- <div>
        <script src='https://kit.fontawesome.com/a076d05399.js'></script> 
        
                <a class="elementor-icon elementor-social-icon elementor-social-icon-font elementor-repeater-item-802b411" target="_blank">
					<span class="elementor-screen-only"></span>
                    <i class="fas fa-font"></i></a>

                <a class="elementor-icon elementor-social-icon elementor-social-icon-plus-circle elementor-repeater-item-0667efd" target="_blank">
					<span class="elementor-screen-only"></span>
                    <i class="fas fa-plus-circle"></i></a>

                <a class="elementor-icon elementor-social-icon elementor-social-icon-minus-circle elementor-repeater-item-a427cc1" target="_blank">
					<span class="elementor-screen-only"></span>
                    <i class="fas fa-minus-circle"></i></a> 

                <a class="elementor-icon elementor-social-icon elementor-social-icon-adjust elementor-repeater-item-439ab46" target="_blank">
                    <span class="elementor-screen-only"></span>
                    <i class="fas fa-adjust"></i></a>                 
        </div> -->
        </header>
        <section id="main-section" class="clearfix">
            <?php if ($this->isEditable()): ?>
                <div id="ajax-response-errors" class="js-dialog" title="<?php \MapasCulturais\i::esc_attr_e("Corrija os erros abaixo e tente novamente.");?>">
                    <div class="js-dialog-content"></div>                
                </div>
            <?php endif; ?>
