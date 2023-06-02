<?php
use MapasCulturais\i;

$action = preg_replace("#^(\w+/)#", "", $this->template);
$this->bodyProperties['ng-app'] = "entity.app";
$this->bodyProperties['ng-controller'] = "EntityController";

$request_project = null;

$this->addEntityToJs($entity);
if ($this->isEditable()) {
    $this->addEntityTypesToJs($entity);
    $this->addTaxonoyTermsToJs('tag');
    $this->addTaxonoyTermsToJs('linguagem');

    $this->addOccurrenceFrequenciesToJs();

    if(!$entity->isNew()){
        $request_project = $app->repo('RequestEventProject')->findOneBy(['originType' => $entity->getClassName(), 'originId' => $entity->id]);
    }
}

$this->enqueueScript('app', 'events', '/js/events.js', array('mapasculturais'));
$this->localizeScript('singleEvents', [
            'correctErrors' => \MapasCulturais\i::__('Corrija os erros indicados abaixo.'),
            'requestAddToSpace' => \MapasCulturais\i::__('Sua requisi��o para adicionar este evento no espa�o %s foi enviada.'),
            'notAllowed' => \MapasCulturais\i::__('Voc� n�o tem permiss�o para criar eventos nesse espa�o.'),
            'unexpectedError' => \MapasCulturais\i::__('Erro inesperado.'),
            'confirmDescription' => \MapasCulturais\i::__('As datas foram alteradas mas a descri��o n�o. Tem certeza que deseja salvar?'),
            'Erro'=> \MapasCulturais\i::__('Erro'),
        ]);

$this->includeAngularEntityAssets($entity);

$this->includeMapAssets();

$editEntity = $this->controller->action === 'create' || $this->controller->action === 'edit';

?>
<?php ob_start(); /* Event Occurrence Item Template - Mustache */ ?>
    <div id="event-occurrence-{{id}}" class="regra clearfix" data-item-id="{{id}}">
        <header class="clearfix">
            <h3 class="alignleft"><a href="{{space.singleUrl}}" rel='noopener noreferrer'>{{space.name}}</a></h3>
            <a class="toggle-mapa" href="#" rel='noopener noreferrer'><span class="ver-mapa"><?php \MapasCulturais\i::_e("ver mapa");?></span><span class="ocultar-mapa"><?php \MapasCulturais\i::_e("ocultar mapa");?></span> <span class="icon icon-show-map"></span></a>
        </header>
        {{#pending}}<div class="alert warning pending"><?php \MapasCulturais\i::_e("Aguardando confirma��o");?></div>{{/pending}}
        <div id="occurrence-map-{{id}}" class="mapa js-map" data-lat="{{space.location.latitude}}" data-lng="{{space.location.longitude}}"></div>
        <!-- .mapa -->
        <div class="infos">
            <p><span class="label"><?php \MapasCulturais\i::_e("Descri��o Leg�vel");?>: </span>{{#rule.description}}{{rule.description}}{{/rule.description}}{{^rule.description}}<?php \MapasCulturais\i::_e("N�o Informado");?>.{{/rule.description}}</p>
            <p><span class="label"><?php \MapasCulturais\i::_e("Pre�o");?>:</span> {{#rule.price}}{{rule.price}}{{/rule.price}}{{^rule.price}}<?php \MapasCulturais\i::_e("N�o Informado");?>.{{/rule.price}}</p>
            <p><span class="label"><?php \MapasCulturais\i::_e("Hor�rio inicial");?>:</span> {{rule.startsAt}}</p>
            {{#rule.duration}}
                <p><span class="label"><?php \MapasCulturais\i::_e("Dura��o");?>:</span> {{rule.duration}} <?php \MapasCulturais\i::_e("min");?></p>
            {{/rule.duration}}
            <p><span class="label"><?php \MapasCulturais\i::_e("Hor�rio final");?>:</span> {{rule.endsAt }}</p>
            <?php if($this->isEditable()): ?>
                <p class="privado"><span class="icon icon-private-info"></span><span class="label"><?php \MapasCulturais\i::_e("Frequ�ncia");?>:</span> {{rule.screen_frequency}}</p>
            <?php endif; ?>
            <p><span class="label"><?php \MapasCulturais\i::_e("Data inicial");?>:</span> {{rule.screen_startsOn}}</p>
            {{#rule.screen_until}}
                <p><span class="label"><?php \MapasCulturais\i::_e("Data final");?>:</span> {{rule.screen_until}}</p>
            {{/rule.screen_until}}
        </div>
        <!-- .infos -->
        <?php if($this->isEditable()): ?>
            <div class="clear">
                <a class="btn btn-default edit js-open-dialog hltip"
                   data-dialog="#dialog-event-occurrence"
                   data-dialog-callback="MapasCulturais.eventOccurrenceUpdateDialog"
                   data-dialog-title="<?php \MapasCulturais\i::esc_attr_e('Modificar local e data'); ?>"
                   data-form-action="edit"
                   data-item="{{serialized}}"
                   href="#" title='<?php \MapasCulturais\i::esc_attr_e('Editar local e data'); ?>'><?php \MapasCulturais\i::_e("Editar");?></a>
               <a class='btn btn-default delete js-event-occurrence-item-delete js-remove-item hltip' style="vertical-align:middle" data-href="{{deleteUrl}}" data-target="#event-occurrence-{{id}}" data-confirm-message="<?php \MapasCulturais\i::esc_attr_e("Excluir este local e data?");?>" title='<?php \MapasCulturais\i::_e("Excluir local e data");?>'><?php \MapasCulturais\i::_e("Excluir");?></a>
            </div>
        <?php endif; ?>
    </div>
<?php $eventOccurrenceItemTemplate = ob_get_clean(); ?>
<?php ob_start(); /* Event Occurrence Item Template VIEW - Mustache */ ?>
    <div class="regra clearfix">
        <header class="clearfix">
            <h3 class="alignleft"><a href="{{space.singleUrl}}" rel='noopener noreferrer'>{{space.name}}</a></h3>
            <a class="toggle-mapa" href="#" rel='noopener noreferrer'><span class="ver-mapa"><?php \MapasCulturais\i::_e("ver mapa");?></span><span class="ocultar-mapa"><?php \MapasCulturais\i::_e("ocultar mapa");?></span> <span class="icon icon-show-map"></span></a>
        </header>
        <div id="occurrence-map-{{space.id}}" class="mapa js-map" data-lat="{{location.latitude}}" data-lng="{{location.longitude}}"></div>
        <!-- .mapa -->
        <div class="infos">
            <p class="descricao-legivel">{{occurrencesDescription}}</p>
            {{#occurrencesPrice}}
                <p><span class="label"><?php \MapasCulturais\i::_e("Pre�o");?>:</span> {{occurrencesPrice}}</p>
            {{/occurrencesPrice}}
            <p><span class="label"><?php \MapasCulturais\i::_e("Endere�o");?>:</span> {{space.endereco}}</p>
        </div>
        <!-- .infos -->
    </div>
<?php $eventOccurrenceItemTemplate_VIEW = ob_get_clean(); ?>

<?php $this->applyTemplateHook('breadcrumb','begin'); ?>

<?php $this->part('singles/breadcrumb', ['entity' => $entity,'entity_panel' => 'events','home_title' => 'entities: My Events']); ?>

<?php $this->applyTemplateHook('breadcrumb','end'); ?>

<?php $this->part('editable-entity', array('entity' => $entity, 'action' => $action));  ?>
<article class="main-content event">
    <?php $this->applyTemplateHook('main-content','begin'); ?>
    <header class="main-content-header">
        <?php $this->part('singles/header-image', ['entity' => $entity]); ?>

        <?php $this->part('singles/entity-status', ['entity' => $entity]); ?>

        <?php $this->applyTemplateHook('header.status','after'); ?>

        <!--.header-image-->
        <?php $this->applyTemplateHook('header-content','before'); ?>
        <div class="container-card">
            <div class="header-content">
                <?php $this->applyTemplateHook('header-content','begin'); ?>

                <?php $this->part('singles/avatar', ['entity' => $entity, 'default_image' => 'img/avatar--event.png']); ?>
                <!--.avatar-->
                <div class="entity-type event-type">
                    <div class="icon icon-event"></div>
                    <a href="#" rel='noopener noreferrer'><?php \MapasCulturais\i::_e("Evento");?></a>
                </div>
                <!--.entity-type-->

                <?php $this->part('singles/name', ['entity' => $entity]) ?>           

                <?php if ($this->isEditable() || $entity->subTitle): ?>
                    <?php $this->applyTemplateHook('subtitle','before'); ?>
                    <h4 class="event-subtitle">
                        <span class="js-editable <?php echo ($entity->isPropertyRequired($entity,"subTitle") && $editEntity? 'required': '');?>" data-edit="subTitle" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Subt�tulo");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira um subt�tulo para o evento");?>" data-tpl='<input tyle="text" maxlength="140"></textarea>'><?php echo $entity->subTitle; ?></span>
                    </h4>
                    <?php $this->applyTemplateHook('subtitle','after'); ?>
                <?php endif; ?>
                <hr style="margin: 0; margin-top: 5px; margin-bottom: 5px;">
                <div class="widget areas">
                    <div class="widget card-event"> 
                            <h3><?php \MapasCulturais\i::_e("Linguagens");?></h3>
                            <?php if ($this->isEditable()): ?>
                                <span id="term-linguagem" class="js-editable-taxonomy" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Linguagens");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Selecione pelo menos uma linguagem");?>" data-restrict="true" data-taxonomy="linguagem"><?php echo implode('; ', $entity->terms['linguagem']) ?></span>
                            <?php else: ?>
                                <?php $linguagens = array_values($app->getRegisteredTaxonomy($entity->getClassName(), 'linguagem')->restrictedTerms); sort($linguagens); ?>
                                <?php foreach ($linguagens as $i => $t): if(in_array($t, $entity->terms['linguagem'])): ?>
                                    <a class="tag tag-event" href="<?php echo $app->createUrl('site', 'search') ?>##(event:(linguagens:!(<?php echo $i ?>)),global:(enabled:(event:!t),filterEntity:event))"><?php echo $t ?></a>
                                <?php endif; endforeach; ?>
                            <?php endif; ?>
                    </div>
                    
                </div>

                <?php $this->applyTemplateHook('header-content','end'); ?>
            </div>
            <!--.header-content-->

            <?php $this->applyTemplateHook('header-content','after'); ?>
            <div class="tags">
                        <?php $this->part('widget-tags', array('entity'=>$entity)); ?>
            </div>
            <?php if($this->isEditable() && $entity->shortDescription && mb_strlen($entity->shortDescription) > 400): ?>
                    <div class="alert warning"><?php \MapasCulturais\i::_e("O limite de caracteres da descri��o curta foi diminuido para 400, mas seu texto atual possui");?> <?php echo mb_strlen($entity->shortDescription) ?> <?php \MapasCulturais\i::_e("caracteres. Voc� deve alterar seu texto ou este ser� cortado ao salvar.");?></div>
                <?php endif; ?>
                <div class="widget">
                    <?php if ($this->isEditable() || $entity->shortDescription): ?>
                        <h3 class=" <?php echo ($entity->isPropertyRequired($entity,"shortDescription") && $editEntity? 'required': '');?>"> <?php \MapasCulturais\i::_e("Descri��o curta");?> <?php if($this->isEditable()){ ?>(<span data-element='countLength'><?=mb_strlen($entity->shortDescription)?></span><?php \MapasCulturais\i::_e("/400 Caracteres)");?></span><?php } ?></h3>
                        <span class="js-editable" data-edit="shortDescription" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descri��o Curta");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira uma descri��o curta para o evento");?>" data-tpl='<textarea data-element="shortDescription" maxlength="400"></textarea>'><?php echo $this->isEditable() ? $entity->shortDescription : nl2br($entity->shortDescription); ?></span>
                    <?php endif; ?>
                    </div>
                <?php if ($this->isEditable() || $entity->site): ?>
                        <div class="widget"><h3 <?php echo ($entity->isPropertyRequired($entity,"site") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Site");?></h3>
                            <?php if ($this->isEditable()): ?>
                                <span class="js-editable" data-edit="site" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Site");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Informe o endere�o do site do evento");?>"><?php echo $entity->site; ?></span>
                        <?php else: ?>
                            <a class="url" href="<?php echo $entity->site; ?>"><?php echo $entity->site; ?></a>
                        <?php endif; ?>
                        </div>
                <?php endif; ?>
                
            <?php $this->part('redes-sociais', array('entity'=>$entity)); ?>
        </div>
    </header>
    <!--.main-content-header-->
    <?php $this->applyTemplateHook('header','after'); ?>

    <?php $this->applyTemplateHook('tabs','before'); ?>
    <ul class="abas clearfix clear">
        <?php $this->applyTemplateHook('tabs','begin'); ?>
        <?php $this->part('tab', ['id' => 'sobre', 'label' => i::__("Sobre"), 'active' => true]) ?>
        <?php if(!($this->controller->action === 'create')):?>
            <?php $this->part('tab', ['id' => 'permissao', 'label' => i::__("Respons�veis")]) ?>
        <?php endif;?>
        <?php $this->applyTemplateHook('tabs','end'); ?>
    </ul>
    <?php $this->applyTemplateHook('tabs','after'); ?>

    <div class="tabs-content">
        <?php $this->applyTemplateHook('tabs-content','begin'); ?>
        <!-- #sobre.aba-content -->
        <div id="sobre" class="aba-content">
            <?php $this->applyTemplateHook('tab-about','begin'); ?>
            <div class="ficha-spcultura">
                
                <?php $this->applyTemplateHook('tab-about-service','before'); ?>
                <div class="servico">
                    <?php $this->applyTemplateHook('tab-about-service','begin'); ?>
                    <?php if ($this->isEditable() || $entity->registrationInfo): ?>
                        <p><span class="label <?php echo ($entity->isPropertyRequired($entity,"registrationInfo") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Inscri��es");?>:</span><span class="js-editable <?php echo ($entity->isPropertyRequired($entity,"registrationInfo") && $editEntity? 'required': '');?>" data-edit="registrationInfo" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Inscri��es");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Informa��es sobre as inscri��es");?>">   <?php echo $this->autoLinkString($entity->registrationInfo); ?></span></p>
                    <?php endif; ?>

                    <?php if ($this->isEditable() || $entity->classificacaoEtaria): ?>
                        <p><span class="label <?php echo ($entity->isPropertyRequired($entity,"classificacaoEtaria") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Classifica��o Et�ria");?>: </span><span class="js-editable" data-edit="classificacaoEtaria" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Classifica��o Et�ria");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Informe a classifica��o et�ria do evento");?>"><?php echo $entity->classificacaoEtaria; ?></span></p>
                    <?php endif; ?>

                    

                    <?php if($this->isEditable() || $entity->telefonePublico): ?>
                        <p><span class="label <?php echo ($entity->isPropertyRequired($entity,"telefonePublico") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Mais Informa��es");?>:</span> <span class="js-editable js-mask-phone" data-edit="telefonePublico" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Mais Informa��es");?>" data-emptytext="(000) 0000-0000"><?php echo $entity->telefonePublico; ?></span></p>
                    <?php endif; ?>

                    <?php if($this->isEditable() || $entity->traducaoLibras || $entity->descricaoSonora): ?>
                        <br>
                        <p>
                            <span><?php \MapasCulturais\i::_e("Acessibilidade");?>:</span>

                            <?php if($this->isEditable() || $entity->traducaoLibras): ?>
                                <p><span class="label <?php echo ($entity->isPropertyRequired($entity,"traducaoLibras") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("Tradu��o para LIBRAS");?>: </span><span class="js-editable" data-edit="traducaoLibras" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Tradu��o para LIBRAS");?>"><?php echo $entity->traducaoLibras; ?></span></p>
                            <?php endif; ?>

                            <?php if($this->isEditable() || $entity->descricaoSonora): ?>
                                <p><span class="label <?php echo ($entity->isPropertyRequired($entity,"descricaoSonora") && $editEntity? 'required': '');?>"><?php \MapasCulturais\i::_e("�udio Descri��o");?>: </span><span class="js-editable" data-edit="descricaoSonora" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descri��o Sonora");?>"><?php echo $entity->descricaoSonora; ?></span></p>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php $this->applyTemplateHook('tab-about-service','end'); ?>
                </div>
                <?php $this->applyTemplateHook('tab-about-service','after'); ?>
                <!--.servico-->
                <div class="servico ocorrencia clearfix">
                    <?php

                    //Event->getOccurrencesGroupedBySpace()
                    function getOccurrencesBySpace($occurrences){
                        $spaces = array();
                        usort($occurrences, function($a, $b) {
                            return $a->space->id - $b->space->id;
                        });
                        foreach ($occurrences as $occurrence) {
                            if (!array_key_exists($occurrence->space->id, $spaces)){
                                $spaces[$occurrence->space->id] = array(
                                    'space' => $occurrence->space,
                                    'location' => $occurrence->space->location,
                                    'occurrences' => array()
                                );
                            }
                            $spaces[$occurrence->space->id]['occurrences'][] = $occurrence;
                        }
                        return $spaces;
                    }

                    function compareArrayElements($array){
                        $compareBase = null;
                        foreach($array as $element){
                            if($compareBase === null) $compareBase = $element;
                            if($compareBase !== $element)
                                return false;
                        }
                        return true;
                    }

                    $occurrences = $entity->occurrences ? $entity->occurrences->toArray() :  array();
                    ?>
                    <?php if ($this->isEditable() || $occurrences): ?>
                        <div class="js-event-occurrence">

                            <?php

                            $screenFrequencies = $this->getOccurrenceFrequencies();
                            $mustache = new Mustache_Engine();

                            if ($occurrences) {

                                if ($this->isEditable()) {

                                    foreach ($occurrences as $occurrence) {
                                        $templateData = json_decode(json_encode($occurrence));
                                        $templateData->pending = $occurrence->status === MapasCulturais\Entities\EventOccurrence::STATUS_PENDING;
                                        if(!is_object($templateData->rule))
                                            $templateData->rule = new stdclass;
                                        $templateData->rule->screen_startsOn = $occurrence->rule->startsOn ? (new DateTime($occurrence->rule->startsOn))->format('d/m/Y') : '';
                                        $templateData->rule->screen_until = $occurrence->rule->until ? (new DateTime($occurrence->rule->until))->format('d/m/Y') : '';
                                        $templateData->rule->screen_frequency = $occurrence->rule->frequency ? $screenFrequencies[$templateData->rule->frequency] : '';

                                        $templateData->rule->screen_spaceAddress = $occurrence->space->endereco;

                                        $templateData->serialized = json_encode($templateData);
                                        $templateData->formAction = $occurrence->editUrl;
                                        echo $mustache->render($eventOccurrenceItemTemplate, $templateData);
                                    }

                                }else{
                                    $occurrences = array_filter($occurrences, function($e){
                                        return $e->status > 0;
                                    });

                                    $spaces = getOccurrencesBySpace($occurrences);

                                    $templateData = array();
                                    $templatesData = array();
                                    foreach($spaces as $space){
                                        $templateData = json_decode(json_encode($space));
                                        $templateData->occurrencesDescription = '';
                                        $templateData->occurrencesPrice = '';
                                        $prices = array();
                                        foreach ($space['occurrences'] as $occurrence) {
                                            $prices[] = !empty($occurrence->rule->price) ? strtolower(trim($occurrence->rule->price)) : '';
                                        }
                                        $arePricesTheSame = compareArrayElements($prices);
                                        if($arePricesTheSame && !empty($space['occurrences'][0]->rule->price)){
                                            $templateData->occurrencesPrice = $space['occurrences'][0]->rule->price;
                                        }
                                        foreach ($space['occurrences'] as $occurrence) {
                                            if(!empty($occurrence->rule->description))
                                                $templateData->occurrencesDescription .= trim($occurrence->rule->description);
                                            if(!$arePricesTheSame)
                                                $templateData->occurrencesDescription .= '. '.$occurrence->rule->price;
                                            if(!empty($occurrence->rule->description))
                                                $templateData->occurrencesDescription .= '; ';
                                        }
                                        $templateData->occurrencesDescription = substr($templateData->occurrencesDescription,0,-2);
                                        $templatesData[] = $templateData;
                                    }

                                    foreach($templatesData as $templateData){
                                        echo $mustache->render($eventOccurrenceItemTemplate_VIEW, $templateData);
                                    }

                                }

                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!--.servico.ocorrencia-->
                <?php if($this->isEditable()): ?>
                    <div class="textright">
                        <a class="btn btn-default add js-open-dialog hltip" data-dialog="#dialog-event-occurrence" href="#"
                           data-dialog-callback="MapasCulturais.eventOccurrenceUpdateDialog"
                           data-dialog-title="<?php \MapasCulturais\i::esc_attr_e('Adicionar local e data'); ?>"
                           data-form-action='insert'
                           title="<?php \MapasCulturais\i::esc_attr_e('Clique para adicionar local e data'); ?>">
                            <?php \MapasCulturais\i::_e("Adicionar local e data");?>
                        </a>
                    </div>
                <?php endif; ?>
                <div id="dialog-event-occurrence" class="js-dialog">
                    <?php if($this->controller->action == 'create'): ?>
                        <span class="js-dialog-disabled" data-message="<?php \MapasCulturais\i::esc_attr_e("Para adicionar local e data, primeiro � preciso salvar o evento");?>"></span>
                    <?php else: ?>
                        <div class="js-dialog-content js-dialog-event-occurrence"></div>
                    <?php endif; ?>
                </div>
            </div>


            <?php if ( $this->isEditable() || $entity->longDescription ): ?>

                <h3><?php \MapasCulturais\i::_e("Descri��o");?></h3>
                <span class="descricao js-editable" data-edit="longDescription" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descri��o do Evento");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira uma descri��o do evento");?>" ><?php echo $this->isEditable() ? $entity->longDescription : nl2br($entity->longDescription); ?></span>
            <?php endif; ?>

            <!-- Video Gallery BEGIN -->
            <?php $this->part('video-gallery.php', array('entity' => $entity)); ?>
            <!-- Video Gallery END -->

            <!-- Image Gallery BEGIN -->
            <?php $this->part('gallery.php', array('entity' => $entity)); ?>
            <!-- Image Gallery END -->

            <?php $this->applyTemplateHook('tab-about','end'); ?>
        </div>
        <!-- #sobre.aba-content -->

        <!-- #permissao -->
        <?php $this->part('singles/permissions') ?>
        <!-- #permissao -->

        <?php $this->applyTemplateHook('tabs-content','end'); ?>
    </div>
    <!-- .tabs-content -->
    <?php $this->applyTemplateHook('tabs-content','after'); ?>

    <?php $this->part('owner', array('entity' => $entity, 'owner' => $entity->owner)) ?>

    <?php $this->applyTemplateHook('main-content','end'); ?>
</article>
<!--.main-content-->
<div class="sidebar-left sidebar event">
    <?php $this->applyTemplateHook('sidebar-left','begin'); ?>
    
    <?php $this->part('related-seals.php', array('entity'=>$entity)); ?>

    <?php if($this->isEditable()): ?>
        <div class="widget">
            <h3><?php \MapasCulturais\i::_e("Projeto");?></h3>
            <?php if($request_project): $proj = $request_project->destination; ?>
                <a href="<?php echo $proj->singleUrl ?>"><?php echo $proj->name ?></a>
            <?php else: ?>
                <a class="js-search js-include-editable"
                    data-field-name='projectId'
                    data-emptytext="<?php \MapasCulturais\i::esc_attr_e('Selecione um projeto'); ?>"
                    data-search-box-width="400px"
                    data-search-box-placeholder="<?php \MapasCulturais\i::esc_attr_e('Selecione um projeto'); ?>"
                    data-entity-controller="project"
                    data-search-result-template="#agent-search-result-template"
                    data-selection-template="#agent-response-template"
                    data-no-result-template="#agent-response-no-results-template"
                    data-selection-format="chooseProject"
                    data-multiple="true"
                    data-allow-clear="1"
                    data-auto-open="true"
                    data-value="<?php echo $entity->project ? $entity->project->id : ''; ?>"
                    data-value-name="<?php echo $entity->project ? $entity->project->name : ''; ?>"
                    title="<?php \MapasCulturais\i::esc_attr_e('Selecionar um Projeto'); ?>">
                    <?php echo $entity->project ? $entity->project->name : ''; ?>
                </a>
            <?php endif; ?>
            <span class="warning pending js-pending-project hltip" data-hltip-classes="hltip-warning" hltitle="<?php \MapasCulturais\i::esc_attr_e("Aguardando confirma��o");?>" <?php if(!$request_project) echo 'style="display:none"'; ?>></span>
        </div>
    <?php elseif($entity->project): ?>
        <div class="widget">
            <h3><?php \MapasCulturais\i::_e("Projeto");?></h3>
            <a class="event-project-link" href="<?php echo $entity->project->singleUrl; ?>"><?php echo $entity->project->name; ?></a>
        </div>
    <?php endif; ?>
  
    
    

    <?php $this->applyTemplateHook('sidebar-left','end'); ?>
</div>
<div class="sidebar event sidebar-right">
    <?php $this->applyTemplateHook('sidebar-right','begin'); ?>

    <?php if($this->controller->action == 'create'): ?>
        <div class="widget">
            <p class="alert info"><?php \MapasCulturais\i::_e("Para adicionar arquivos para download ou links, primeiro � preciso salvar o evento");?>.<span class="close"></span></p>
        </div>
    <?php endif; ?>

    <?php $this->part('related-admin-agents.php', array('entity'=>$entity)); ?>
    
    <?php $this->part('related-agents.php', array('entity' => $entity)); ?>
    
    <?php $this->part('downloads.php', array('entity' => $entity)); ?>
    
    <?php $this->part('link-list.php', array('entity' => $entity)); ?>
    
    <?php $this->part('history.php', array('entity' => $entity)); ?>

    <?php $this->applyTemplateHook('sidebar-right','end'); ?>
</div>
<?php if ($this->isEditable()): ?>

    <?php $this->part('modal/event-occurrence-form', array('entity' => $entity)); ?>
<?php endif; ?>
<script type="text/html" id="event-occurrence-item" class="js-mustache-template">
    <?php echo $eventOccurrenceItemTemplate; ?>
</script>
