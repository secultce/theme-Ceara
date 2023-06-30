<?php
if($this->controller->action === 'create')
    return;
?>

<?php 
$sql = "
select * from public.file where object_id = 11854 
";

// Paginação
$currentPage = $_GET['page'] ?? 1;
$itemsPerPage = 50;
$offset = ($currentPage - 1) * $itemsPerPage;
$sql .= ' LIMIT ' . $itemsPerPage . ' OFFSET ' . $offset;
echo $sql;
$stmt = $app->em->getConnection()->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();
    // dump($results);
    // foreach ($results as $image) {
    //     dump($image);
    //     // echo '<div class="clearfix js-gallery">
    //     //     <div class="image-gallery-item" >
    //     //     <a href="#" class="btn btn-primary">'.$image['md5'].'</a>
    //     //     </div>
    
    //     // </div>';

    // }

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
    <div class="clearfix js-gallery">
    <?php if($gallery): 
        foreach($results as $img): ?>
            <div id="file-<?php echo $img['id'] ?>" class="image-gallery-item" >        
               
                <a href="https://mapacultural.secult.ce.gov.br/files/agent/11854/s%C3%A3o_jo%C3%A3o_liter%C3%A1rio_val_paraiso_(1).jpg"> 
                    <img src="https://mapacultural.secult.ce.gov.br/files/agent/11854/s%C3%A3o_jo%C3%A3o_liter%C3%A1rio_val_paraiso_(1).jpg" class/>
            </a>
                <?php if($this->isEditable()): ?>

                    <a data-href="<?php echo $img->deleteUrl?>" data-target="#file-<?php echo $img->id ?>" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title="<?php \MapasCulturais\i::esc_attr_e("Excluir");?>"></a>

                <?php endif; ?>
            </div>
        <?php endforeach; 


echo '<a id="prev-page" href="?page=' . ($currentPage - 1) . '" class="btn btn-primary">Página anterior</a>&nbsp&nbsp';
echo '<a id="next-page" href="?page=' . ($currentPage + 1) . '" class="btn btn-primary">Próxima página</a>';


    endif;?>
    </div>
    <?php if($this->isEditable()): ?>
        <p class="gallery-footer">
            <a class="btn btn-default add js-open-editbox" data-target="#editbox-gallery-image" href="#"><?php \MapasCulturais\i::_e("Adicionar imagem");?></a>
            <div id="editbox-gallery-image" class="js-editbox mc-top" title="<?php \MapasCulturais\i::esc_attr_e("Adicionar Imagem na Galeria");?>">
                <?php $this->ajaxUploader($entity, 'gallery', 'append', 'div.js-gallery', '<div id="file-{{id}}" class="image-gallery-item" ><a href="{{url}}"><img src="{{files.galleryThumb.url}}" /></a> <a data-href="{{deleteUrl}}" data-target="#file-{{id}}" class="btn btn-default delete hltip js-remove-item" data-hltip-classes="hltip-ajuda" title='. \MapasCulturais\i::__("Excluir").'></a></div>', 'galleryThumb', true)?>
            </div>
        </p>
    <?php endif; ?>
<?php endif; ?>
