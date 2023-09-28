<?php

use MapasCulturais\App;
//Chamada da função da paginação do Theme.php
$results = $this->addPagination();
//chamada da função de busca da url
$galleryUrl = $this->getGalleryUrl();
            
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

<?php if ($this->isEditable() || $gallery) : ?>
    <h3><?php \MapasCulturais\i::_e("Galeria");?></h3>
    <!-- chamada da função de ancoragem do Theme.php-->
    <?php $ancora = $this->scroll(); ?>    
    <div class="clearfix js-gallery" id="<?php echo $ancora?>">
        <?php if ($gallery && isset($results)) :
            foreach ($results['images'] as $key => $img) : ?>
                <div id="file-<?php echo $img->id; ?>" class="image-gallery-item">
                    <?php if (isset($galleryUrl['className'])) { ?>
                        <a 
                            href="<?php echo $galleryUrl['url'] . 'files/' . $galleryUrl['className'] . '/' . $galleryUrl['profile'] . '/' . $img->name; ?>"
                            title="<?php echo htmlspecialchars($img->description, ENT_QUOTES); ?> - Cadastrado em <?php echo $img->createTimestamp->format('d/m/Y á\s H:i:s')?>"
                        > 
                            <img src="<?php echo $galleryUrl['url'] . 'files/' . $galleryUrl['className'] . '/' . $galleryUrl['profile'] . '/' . $img->name; ?>" class="image-gallery-item" />
                        </a>
                        <?php if ($this->isEditable()) : ?>
                            <a data-href="<?php echo $galleryUrl['url'] . '/arquivos/apaga/' . $img->id ?>" data-target="#file-<?php echo $img->id ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir"); ?>"></a>
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