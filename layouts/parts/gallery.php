<?php
use MapasCulturais\App;
if($this->controller->action === 'create')
    return;
$app = App::i();
$profile = $entity->id;

$sql = "select * from public.file where object_id = $profile  AND grp='gallery'";

// Paginação
$currentPage = $_GET['page'] ?? 1;
$itemsPerPage = 24;
$offset = ($currentPage - 1) * $itemsPerPage;
$sql .= ' LIMIT ' . $itemsPerPage . ' OFFSET ' . $offset;

$stmt = $app->em->getConnection()->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(); 
$url= $app->config['base.url'].'files/agent/'.$profile.'/';

if(!is_object($entity)):?>
    <div class="alert info"><?php MapasCulturais\i::__("Nenhuma imagem disponível");?></div>
    <?php return;?>
<?php endif;?>

<?php $gallery = $entity->getFiles('gallery'); ?>
<?php if(is_array($gallery) && count($gallery) <= 0 && $this->controller == 'registration'):?>
    <div class="alert info"><?php i::__("Nenhuma imagem disponível");?></div>
<?php endif;?>

<?php if ($this->isEditable() || $gallery): ?>
    <h3><?php \MapasCulturais\i::_e("Galeria");?></h3>
    <div class="clearfix js-gallery" id="gallery-img-agent">
    <?php if($gallery): 
        $counteImage = 0;
        foreach($results as $key => $img): 
        ?>
            <div id="file-<?php echo $img['id']; ?>" class="image-gallery-item" >        
            <?php $counteImage++ ;?> 
           <a href="<?php echo $url.$img['name'] ;?>"> <img src="<?php echo $url.$img['name']; ?>" /> </a>
                <?php if($this->isEditable()): ?>

                    <a data-href="<?php echo $gallery[$key]->deleteUrl; ?>" data-target="#file-<?php echo $img['id'] ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir");?>"></a>

                <?php endif; ?>
            </div>
        <?php endforeach; 

    endif;?>
    </div>
    
    <?php
        $number =  0;
        if(isset($_GET['page'])) {
            $number =  $_GET['page'];
        }
        if($number > 1 ){
            echo '<a id="prev-page" href="?page=' . ($currentPage - 1) . '#gallery-img-agent" class="btn btn-primary">Página anterior</a>&nbsp&nbsp';
        }
        if( $counteImage != ''){
            echo '<a id="next-page" href="?page=' . ($currentPage + 1) . '#gallery-img-agent" class="btn btn-primary">Próxima página</a>';
        }
        
        if($this->isEditable()): ?>
            <p class="gallery-footer">
                <a class="btn btn-default add js-open-editbox" data-target="#editbox-gallery-image" href="#"><?php \MapasCulturais\i::_e("Adicionar imagem");?></a>
                <div id="editbox-gallery-image" class="js-editbox mc-top" title="<?php \MapasCulturais\i::esc_attr_e("Adicionar Imagem na Galeria");?>">
                    <?php $this->ajaxUploader($entity, 'gallery', 'append', 'div.js-gallery', '<div id="file-{{id}}" class="image-gallery-item" ><a href="{{url}}"><img src="{{files.galleryThumb.url}}" /></a> <a data-href="{{deleteUrl}}" data-target="#file-{{id}}" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title='. \MapasCulturais\i::__("Excluir").'></a></div>', 'galleryThumb', true)?>
                </div>
            </p>
        <?php endif; ?>
<?php endif; ?>
