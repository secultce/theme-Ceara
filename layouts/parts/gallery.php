<?php

use MapasCulturais\App;
//Chamada da função da paginação do Theme.php
$results = $this->addPagination();
if ($this->controller->action === 'create')
    return;

if (!is_object($entity)) : ?>
    <div class="alert info"><?php MapasCulturais\i::__("Nenhuma imagem disponível"); ?></div>
    <?php return; ?>
<?php endif; ?>

<?php $gallery = $entity->getFiles('gallery'); ?>
<?php if (is_array($gallery) && count($gallery) <= 0 && $this->controller == 'registration') : ?>
    <div class="alert info"><?php i::__("Nenhuma imagem disponível"); ?></div>
<?php endif; ?>

<?php
// $url = $_SERVER['HTTP_HOST'];
// $profile = $this->data->entity->id;
// $word = explode("/", $_SERVER['REQUEST_URI']);
// $sub = $word[1];

// if (!empty($_SERVER['HTTPS'])) 
//     $http = 'https';
// else
//     $http = 'http';
$app = App::i();

        $url = $app->config['base.url'];
        $afterBar = $app->auth->opauth->env['request_uri'];
        $profile = $this->data->entity->id;
        $word = explode("/", $afterBar);
        $sub = $word[1];        

?>
<?php if ($this->isEditable() || $gallery) : ?>
    <h3><?php \MapasCulturais\i::_e("Galeria"); ?></h3>
    
    <?php 
    $currentPage = (isset($_GET['page']));
    if($currentPage != false) { ?>
    <div class="clearfix js-gallery" id="gallery-img-agent">
    <?php }?>
        <?php if ($gallery && isset($results)) :
            foreach ($results['results'] as $key => $img) : ?>
                <div id="file-<?php echo $img['id']; ?>" class="image-gallery-item">
                    
                    <?php if ($sub == 'evento'  || $sub == 'eventos' ) { ?>
                        <a href="<?php echo $url . '/files/event/' . $profile . '/' . $img['name']; ?>"> <img src="<?php echo $url . '/files/event/' . $profile . '/' . $img['name']; ?>" class="image-gallery-item" /> </a>
                        <?php if ($this->isEditable()) : ?>
                            <a data-href="<?php echo $url . '/arquivos/apaga/' . $img['id'] ?>" data-target="#file-<?php echo $img['id'] ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir"); ?>"></a>
                        <?php endif; ?>
                    <?php } ?>

                    <?php if ($sub == 'agente' || $sub == 'agentes' ) { ?>
                        <a href="<?php echo $results['url'] . $img['name']; ?>"> <img src="<?php echo $results['url'] . $img['name']; ?>" class="image-gallery-item" /> </a>
                        <?php if ($this->isEditable()) : ?>
                            <a data-href="<?php echo $url . '/arquivos/apaga/' . $img['id'] ?>" data-target="#file-<?php echo $img['id'] ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir"); ?>"></a>
                        <?php endif; ?>
                    <?php } ?>

                    <?php if ($sub == 'espaco' || $sub == 'espacos') { ?>
                        <a href="<?php echo $url . '/files/space/' . $profile . '/' . $img['name']; ?>"> <img src="<?php echo $url . '/files/space/' . $profile . '/' . $img['name']; ?>" class="image-gallery-item" /> </a>
                        <?php if ($this->isEditable()) : ?>
                            <a data-href="<?php echo $url . '/arquivos/apaga/' . $img['id'] ?>" data-target="#file-<?php echo $img['id'] ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir"); ?>"></a>
                        <?php endif; ?>
                    <?php } ?>

                    <?php if ($sub == 'projeto' || $sub == 'projetos') { ?>
                        <a href="<?php echo $url . '/files/project/' . $profile . '/' . $img['name']; ?>"> <img src="<?php echo $url . '/files/project/' . $profile . '/' . $img['name']; ?>" class="image-gallery-item" /> </a>
                        <?php if ($this->isEditable()) : ?>
                            <a data-href="<?php echo $url . '/arquivos/apaga/' . $img['id'] ?>" data-target="#file-<?php echo $img['id'] ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir"); ?>"></a>
                        <?php endif; ?>
                    <?php } ?>

                    <?php if ($sub == 'oportunidade' || $sub == 'oportunidades') { ?>
                        <a href="<?php echo $url . '/files/opportunity/' . $profile . '/' . $img['name']; ?>"> <img src="<?php echo $url . '/files/opportunity/' . $profile . '/' . $img['name']; ?>" class="image-gallery-item" /> </a>
                        <?php if ($this->isEditable()) : ?>
                            <a data-href="<?php echo $url . '/arquivos/apaga/' . $img['id'] ?>" data-target="#file-<?php echo $img['id'] ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir"); ?>"></a>
                        <?php endif; ?>
                    <?php } ?>
                </div>
        <?php endforeach;
        endif; ?>
    </div>

    <?php $currentPage = isset($results['currentPage']) ? $results['currentPage'] : 1;
    // Chamada da função dos botões paginação da Theme.php
    $this->seeButtons($currentPage); ?>
    <?php
    if ($this->isEditable()) : ?>
        <p class="gallery-footer">
            <a class="btn btn-default add js-open-editbox" data-target="#editbox-gallery-image" href="#"><?php \MapasCulturais\i::_e("Adicionar imagem"); ?></a>
        <div id="editbox-gallery-image" class="js-editbox mc-top" title="<?php \MapasCulturais\i::esc_attr_e("Adicionar Imagem na Galeria"); ?>">
            <?php $this->ajaxUploader($entity, 'gallery', 'append', 'div.js-gallery', '<div id="file-{{id}}" class="image-gallery-item" ><a href="{{url}}"><img src="{{files.galleryThumb.url}}" /></a> <a data-href="{{deleteUrl}}" data-target="#file-{{id}}" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title=' . \MapasCulturais\i::__("Excluir") . '></a></div>', 'galleryThumb', true) ?>
        </div>
        </p>
    <?php endif; ?>
<?php endif; ?>