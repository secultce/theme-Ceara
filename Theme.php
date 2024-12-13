<?php

namespace Ceara;

use MapasCulturais\Entities\OpportunityMeta;
use \MapasCulturais\i;
use MapasCulturais\App;
use MapasCulturais\Utils;
use MapasCulturais\AssetManager;
use MapasCulturais\Themes\BaseV1;
use MapasCulturais\Entities\Agent;
use MapasCulturais\Entities\Opportunity;
use MapasCulturais\Entities\ProjectOpportunity;

// Constante para definir itens por página
define("ITEMS_PER_PAGE", 100);
class Theme extends BaseV1\Theme
{
    public function __construct(AssetManager $asset_manager)
    {
        $app = App::i();

        /* Hook Aldir Blanc Config */
        $app->hook('aldirblanc.config', function (&$config, &$skipConfig) use ($app) {
            $skipConfig = true;
            $opps = array_values($config['inciso2_opportunity_ids']);
            $query = $app->em->createQuery("
                SELECT
                    op.id
                FROM
                    MapasCulturais\Entities\Opportunity op
                WHERE
                    op.status = 1 AND op.id in (:opportunitiesIds)
            ");

            $params = ['opportunitiesIds' => $opps];
            $query->setParameters($params);
            $result = $query->getArrayResult();

            $newConfig = [];
            foreach ($config['inciso2_opportunity_ids'] as $cityName => $cityValue) {
                foreach ($result as $item) {
                    if ((int) $cityValue == (int) $item['id']) {
                        $newConfig[$cityName] = $cityValue;
                        break;
                    }
                }
            }

            $config['inciso2_opportunity_ids'] = $newConfig;
        });
               
        parent::__construct($asset_manager);
    }    

    protected static function _getTexts()
    {
        $self = App::i()->view;
        $url_search_agents = $self->searchAgentsUrl;
        $url_search_spaces = $self->searchSpacesUrl;
        $url_search_events = $self->searchEventsUrl;
        $url_search_projects = $self->searchProjectsUrl;

        return [
            'site: in the region' => 'no Estado do Ceará',
            'site: of the region' => 'do Estado do Ceará',
            'site: owner' => 'Secretaria da Cultura do Estado do Ceará',
            'site: by the site owner' => 'pela Secretaria da Cultura do Estado do Ceará',

            'home: abbreviation' => "SECULT",
            // 'home: colabore' => "Colabore com o Mapas Culturais",
            //'home: welcome' => "O Mapa Cultural do Ceará é a plataforma livre, gratuita e colaborativa de mapeamento da Secretaria da Cultura do Estado do Ceará sobre cenário cultural cearense. Ficou mais fácil se programar para conhecer as opções culturais que as cidades cearenses oferecem: shows musicais, espetáculos teatrais, sessões de cinema, saraus, entre outras. Além de conferir a agenda de eventos, você também pode colaborar na gestão da cultura do estado: basta criar seu perfil de <a href=\"$url_search_agents\" >agente cultural</a>. A partir deste cadastro, fica mais fácil participar dos editais e programas da Secretaria e também divulgar seus <a href=\"{$url_search_events}\">eventos</a>, <a href=\"{$url_search_spaces}\">espaços</a> ou <a href=\"$url_search_projects\">projetos</a>.",
            'home: welcome' => "Plataforma livre, colaborativa e interativa de mapeamento do cenário cultural cearense e instrumento de governança digital no aprimoramento da gestão pública, dos mecanismos de participação e da democratização do acesso às políticas culturais promovidas pela Secretaria da Cultura do Estado do Ceará.
                                <br><br>O Mapa Cultural é uma ferramenta de comunicação visibilizando os eventos do circuito de festivais de artes e do calendário cultural, os projetos desenvolvidos e os espaços promovidos pelos agentes e instituições culturais do Ceará. É também a plataforma de acesso e execução dos editais realizados pela Secretaria.
                                <br><br>Além de conferir a agenda de eventos, você também pode colaborar na gestão da cultura do estado: basta criar seu perfil de agente cultural. A partir do cadastro, fica mais fácil participar dos editais e programas da Secretaria e também divulgar seus eventos, espaços ou projetos.",

            // 'home: events' => "Você pode pesquisar eventos culturais nos campos de busca combinada. Como usuário cadastrado, você pode incluir seus eventos na plataforma e divulgá-los gratuitamente.",
            // 'home: agents' => "Você pode colaborar na gestão da cultura com suas próprias informações, preenchendo seu perfil de agente cultural. Neste espaço, estão registrados artistas, gestores e produtores; uma rede de atores envolvidos na cena cultural paulistana. Você pode cadastrar um ou mais agentes (grupos, coletivos, bandas instituições, empresas, etc.), além de associar ao seu perfil eventos e espaços culturais com divulgação gratuita.",
            // 'home: spaces' => "Procure por espaços culturais incluídos na plataforma, acessando os campos de busca combinada que ajudam na precisão de sua pesquisa. Cadastre também os espaços onde desenvolve suas atividades artísticas e culturais.",
            // 'home: projects' => "Reúne projetos culturais ou agrupa eventos de todos os tipos. Neste espaço, você encontra leis de fomento, mostras, convocatórias e editais criados, além de diversas iniciativas cadastradas pelos usuários da plataforma. Cadastre-se e divulgue seus projetos.",
            'home: home_devs' => 'Existem algumas maneiras de desenvolvedores interagirem com o Mapas Culturais. A primeira é através da nossa <a href="https://github.com/secultce/mapasculturais/blob/master/documentation/docs/mc_config_api.md" target="_blank">API</a>. Com ela você pode acessar os dados públicos no nosso banco de dados e utilizá-los para desenvolver aplicações externas. Além disso, o Mapas Culturais é construído a partir do sofware livre <a href="http://institutotim.org.br/project/mapas-culturais/" target="_blank">Mapas Culturais</a>, criado em parceria com o <a href="http://institutotim.org.br" target="_blank">Instituto TIM</a>, e você pode contribuir para o seu desenvolvimento através do <a href="https://github.com/secultce/mapasculturais/" target="_blank">GitHub</a>.',
            //
            // 'search: verified results' => 'Resultados Verificados',
            // 'search: verified' => "Verificados"
        ];
    }

    public static function getThemeFolder()
    {
        return __DIR__;
    }

    public function addDocumentMetas()
    {
        parent::addDocumentMetas();
        $app = App::i();
        foreach ($this->documentMeta as $key => $meta) {
            if (isset($meta['property']) && ($meta['property'] === 'og:image' || $meta['property'] === 'og:image:url')) {
                $this->documentMeta[$key] = array(
                    'property' => $meta['property'],
                    'content' => $app->view->asset('img/share-ca.png', false),
                );
            }
        }
    }

    public function addPagination()
    {
        $app = App::i();
        $entity = $this->data->entity;
        $profile = $entity->id;

        // Paginação
        $currentPage = $_GET['page'] ?? 1;        
        $offset = ($currentPage - 1) * ITEMS_PER_PAGE;

        if (isset($entity->files['gallery'])) {
            $gallery = $entity->files['gallery'];
            $images = array_slice($gallery, $offset, ITEMS_PER_PAGE);
            $url = $app->config['base.url'] . 'files/agent/' . $profile . '/';
            return [
                'images' => $images,
                'url' => $url,
                'currentPage' => $currentPage
            ];
        }
    }

    public function scroll(){
        
        $anchor = '';
        if((isset($_GET['page'])) && ($_GET['page']) != null){
            $anchor = 'gallery-img-agent';
        }else{
            $anchor = '';
        }
        return $anchor;
    }    

    public function getGalleryUrl()
    {
        $app = App::i();
        $url = $app->config['base.url'];
    
        $profile = $this->data->entity->id;
        $className = $this->controller->id;
        return [
            'url' => $url,
            'profile' => $profile,          
            'className' => $className
        ];
    }

    // Mostra botões de paginação na galeria
    public function seeButtons($currentPage)
    {
        $app = App::i();
        $entity = $this->data->entity;       
        if (isset($entity->files['gallery'])) {
            $totalImages = count($entity->files['gallery']);
            $totalPages = ceil($totalImages / ITEMS_PER_PAGE);

            // Verificar se o número de página é válido
        if ($currentPage > $totalPages) {
            // Redirecionar para a página mais próxima existente            
            $redirectUrl = '?page=' . $totalPages . '#gallery-img-agent';
            header('Location: ' . $redirectUrl);
            exit();
        }

            $prevPageUrl = '?page=' . ($currentPage - 1) . '#gallery-img-agent';
            $nextPageUrl = '?page=' . ($currentPage + 1) . '#gallery-img-agent';

            if ($currentPage > 1) {
                echo '<a id="prev-page" href="' . $prevPageUrl . '" class="btn btn-primary">Página anterior</a>&nbsp&nbsp';
            }

            if (isset($currentPage) && $totalPages > 1) {
                $color = (int) $currentPage;
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i != $color) {
                        echo '<a id="prev-page" href="?page=' . $i . '#gallery-img-agent" class="btn btn-primary">' . $i . '</a>&nbsp&nbsp';
                    }

                    if ($i == $color) {
                        echo '<a id="prev-page" href="?page=' . $i . '#gallery-img-agent" class="btn btn-success">' . $i . '</a>&nbsp&nbsp';
                    }
                }
            }
            if (isset($currentPage) && $currentPage < $totalPages) {
                echo '<a id="next-page" href="' . $nextPageUrl . '" class="btn btn-primary">Próxima página</a>';
            }            
        }   
        $app->view->enqueueScript('app', 'scroll', 'js/scroll.js');
    }

    /**
     *
     * {@inheritdoc}
     * @see \MapasCulturais\Themes\BaseV1\Theme::_init()
     */
    protected function _init()
    {
        parent::_init();
        $app = App::i();
        //Chamada  da função de alerta nas views
        $this->alertMessageMaintenance();

        $this->enqueueScript('app', 'accessibility', 'js/accessibility.js');
        $this->enqueueScript('app', 'analytics', 'js/analytics.js');
        $this->enqueueStyle('app', 'accessibility', 'css/accessibility.css');
        //Para notificação ao usuário
        $this->enqueueScript('app', 'pnotify', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.js');
        $this->enqueueScript('app', 'pnotify-animate', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.animate.min.js');
        $this->enqueueScript('app', 'pnotify-confirm', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.confirm.min.js');
        $this->enqueueScript('app', 'pnotify-buttons', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.buttons.min.js');
        $this->enqueueStyle('app', 'pnotify', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.css');
        $this->enqueueStyle('app', 'pnotify-theme', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.brighttheme.min.css');
        $this->enqueueStyle('app', 'pnotify-buttons', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.buttons.min.css');
        //chamada do arquivo js que contém o ocultar botão + da modal criação
        $this->enqueueScript('app', 'hidebutton', 'js/opportunity-ceara/hidebutton.js');
        // adiciona tracker do Hotjar
        $this->enqueueScript('app', 'hotjar', 'js/hotjar.js');

        $app->hook('view.render(<<*>>):before', function () use ($app) {
            $this->_publishAssets();
        });

        // Ativaçao de modal
        $app->hook('template(site.index.home-search):before', function () use ($app) {
            $this->enqueueStyle('app', 'remodal-css', 'css/modal/remodal.min.css');
            $this->enqueueStyle('app', 'remodal-theme-css', 'css/modal/remodal-default-theme.css');
            $this->enqueueStyle('app', 'remodal-style-css', 'css/modal/style.css');
            $this->enqueueScript('app', 'remodal-js', 'js/modal/remodal.min.js');
            $this->enqueueScript('app', 'remodal-custom', 'js/modal/custom.js');
            $this->part('modal/active-account');
        });
        // Verificando se o usuário já aceitou os termos para ocutar o modal
        $app->hook("GET(agent.verify-email)", function () use ($app) {
            if($app->user->is('guest')) {
                return $this->json(['result' => false]);
            }
            // Para usuário ativos nao mostra o modal
            if($app->user->getMetadata('accountIsActive') == "1"){
                return $this->json(['result' => true]);
            }else{
                return $this->json(['result' => false]);
            }            
        }); 
        /**
         * Conjunto de Hooks para add botão de suporte dentro do tema
         */
        $app->hook('template(site.<<*>>.nav.main.events):before', function () use ($app) {
            $this->part('site/header');
        });       
        $app->hook('template(panel.<<*>>.nav.main.events):before', function () use ($app) {
            $this->part('site/header');
        });
        $app->hook('template(<<*>>.<<single|edit>>.nav.main.events):before', function () use ($app) {
            $this->part('site/header');
        });
        //APLICANDO PARA REGISTRATION E OUTRAS ENTIDADES
        $app->hook('template(<<*>>.view.nav.main.events):before', function () use ($app) {
            $this->part('site/header');
        });

        
        /*$app->hook('<<GET|POST>>(registration.remove)', function () use ($app) {

            $this->requireAuthentication();

            if (!$app->user->is('admin')) {
                $this->json(array("error" => "Permissão negada!"));
                return;
            }

            if (!isset($this->data['registration_id'])) {
                $this->json(array("error" => "Inscrição invalida!"));
                return;
            }

            $registration_id = (int) $this->data['registration_id'];
            $registration = $app->repo('Registration')->find($registration_id);

            if (!$registration) {
                $this->json(array("error" => "Inscrição invalida!"));
                return;
            }

            $connection = $app->em->getConnection();
            $statement = $connection->prepare("DELETE FROM registration where id = {$registration_id}");

            try {
                $statement->execute();
                $result = $statement->fetchAll();
                $result_type = "success";
                $result = "OK";
            } catch (\Exception $e) {
                $result = $e->getMessage();
                $result_type = "error";
            }

            $this->json(array($result_type => $result));
        });*/

        //HOOK PARA FORÇAR A INCLUSAO DE PERFIL INDIVIDUAL E COLETIVO
        $app->hook('template(agent.edit.type):before', function () use ($app) {
            $entity = $this->controller->requestedEntity;
            $app->view->enqueueScript('app', 'edit-type', 'js/agents/edit-agent.js');   
            $this->part('singles/agents/type', ['entity' => $entity]);
        });

        /*$app->hook('template(opportunity.<<create|edit|single>>.registration-list-header):end', function () use ($app) {
            if ($app->user->is('admin')) {
                echo '<th class="registration-status-col">Administrador</th>';
            }
        });
        
        $app->hook('template(opportunity.<<create|edit|single>>.registration-list-item):end', function () use ($app) {
            if ($app->user->is('admin')) {
                echo '<td><button data-id="{{reg.id}}" onclick=\'if (confirm("Tem certeza que você deseja apagar a inscrição n. on-" + this.dataset.id + " ?")) {$.ajax({url: MapasCulturais.baseURL + "/registration/remove/registration_id:"+ this.dataset.id , success: function(result){ if(result.success) {MapasCulturais.Messages.success("Inscrição excluida com sucesso!");} else{ MapasCulturais.Messages.error(result.error);} }});}\'> Apagar </button> </td>';
            }
        });

        $app->hook('template.opportunity.single.header.registration-item', function ($registrationId) use ($app) {
            if ($app->user->is('admin')) {
                echo '<button class="btn btn-danger" data-id=' . $registrationId . ' onclick=\'if (confirm("Tem certeza que você deseja apagar a inscrição n. on-" + this.dataset.id + " ?")) {$.ajax({url: MapasCulturais.baseURL + "/registration/remove/registration_id:"+ this.dataset.id , success: function(result){ if(result.success) {MapasCulturais.Messages.success("Inscrição excluida com sucesso!");} else{ MapasCulturais.Messages.error(result.error);} }});}\'> Apagar </button>';
            }
        });*/

        /* Adicionando novos campos na entidade entity revision agent */
        $app->hook('template(entityrevision.history.tab-about-service):end', function () {
            $this->part('news-fields-agent-revision', [
                'entityRevision' => $this->data->entityRevision,
            ]);
        });

        /* Adicionando novos campos na entidade entity revision agent */
        $app->hook('template(entityrevision.history.tab-about-service):end', function () {
            $this->part('news-fields-agent-revision', [
                'entityRevision' => $this->data->entityRevision,
            ]);
        });

        $app->hook('template(opportunity.single.header-inscritos):end', function () use ($app) {
            $opportunity = $this->controller->requestedEntity;
            $this->part('report/opportunity-report-buttons', ['entity' => $opportunity]);
        });

        $app->hook('template(opportunity.single.tab-about):begin', function () use ($app) {
            $app->view->enqueueScript('app', 'btn-disable-on-register', 'js/opportunity-ceara/btn-disable-on-register.js');
        });

        //relatórios de inscritos botão antigo 
        $app->hook("<<GET|POST>>(opportunity.reportOld)", function () use ($app) {
            //return var_dump("ola");
            $this->requireAuthentication();
            // //$app = App::i();


            $entity = $app->repo("Opportunity")->find($this->urlData['id']);

            if (!$entity) {
                $app->pass();
            }

            $entity->checkPermission('@control');

            $app->controller('Registration')->registerRegistrationMetadata($entity);

            $filename = sprintf(\MapasCulturais\i::__("oportunidade-%s--inscricoes"), $entity->id);


            $response = $app->response();
            $response['Content-Encoding'] = 'UTF-8';
            $response['Content-Type'] = 'application/force-download';
            $response['Content-Disposition'] = 'attachment; filename=' . $filename . '.xls';
            $response['Pragma'] = 'no-cache';

            $app->contentType('application/vnd.ms-excel; charset=UTF-8');


            ob_start();
            $this->partial("report-old", ['entity' => $entity]);
            $output = ob_get_clean();
            echo mb_convert_encoding($output, "HTML-ENTITIES", "UTF-8");
        });


        //relatorios de inscritos por data 
        $app->hook("<<GET|POST>>(opportunity.reportResultEvaluationsDocumental)", function () use ($app) {

            $format = isset($this->data['fileFormat']) ? $this->data['fileFormat'] : 'pdf';
            $date = isset($this->data['publishDate']) ? $this->data['publishDate'] : date("d/m/Y");
            $datePubish = date("d/m/Y", strtotime($date));

            $opportunityId = (int) $this->data['id'];
            $opportunity = $app->repo("Opportunity")->find($opportunityId);

            $dql = "SELECT e,r,a
                    FROM
                        MapasCulturais\Entities\RegistrationEvaluation e
                        JOIN e.registration r
                        JOIN r.owner a
                    WHERE r.opportunity = :opportunity ORDER BY r.consolidatedResult ASC";

            $q = $app->em->createQuery($dql);
            $q->setParameters(['opportunity' => $opportunity]);
            $evaluations = $q->getResult();

            $json_array = [];
            foreach ($evaluations as $e) {
                $registration = $e->registration;
                $evaluationData = (array) $e->evaluationData;
                $result = $e->getResultString();
                $metadata = (array) $registration->getMetadata();
                $projectName = (isset($metadata['projectName'])) ? $metadata['projectName'] : '';
                $descumprimentoDosItens = (string) array_reduce($evaluationData, function ($motivos, $item) {
                    if ($item['evaluation'] == 'invalid') {
                        $motivos .= trim($item['obs_items']);
                    }
                    return $motivos;
                });
                $categoria = $registration->category;
                $agentRelations = $app->repo('RegistrationAgentRelation')->findBy(['owner' => $registration]);

                $coletivo = null;

                if ($agentRelations) {
                    $coletivo = $agentRelations[0]->agent->nomeCompleto;
                }

                $proponente = $registration->owner->nomeCompleto;
                if (strpos($categoria, 'JURÍDICA') && $coletivo !== null) {
                    $proponente = $coletivo;
                }

                $json_array[] = [
                    'n_inscricao' => $registration->number,
                    'projeto' => $projectName,
                    'proponente' => trim($proponente),
                    'categoria' => $categoria,
                    'municipio' => trim($registration->owner->En_Municipio),
                    'resultado' => ($result == 'Válida') ? 'HABILITADO' : 'INABILITADO',
                    'motivo_inabilitacao' => $descumprimentoDosItens,
                ];
            }
            $filename = __DIR__ . "/report/" . time() . "habilitacao-preliminar.csv";
            $output = fopen($filename, 'w') or die("error");
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($output, ["Inscrição", "Projeto", "Proponente", "Categoria", "Município", "Resultado", "Motivo_Inabilitação"], ";");
            foreach ($json_array as $relatorio) {
                fputcsv($output, $relatorio, ";");
            }
            fclose($output) or die("Can't close php://output");
            header('Content-Encoding: UTF-8');
            header("Content-type: text/csv; charset=UTF-8");
            header("Content-Disposition: attachment; filename=habilitacao-documental.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($filename);
            unlink($filename);
        });

        $app->hook('template(<<agent|space|event|project>>.<<single>>.main-content):end', function () use ($app) {
            // É possível acessar a propriedade config pelo o $app;

            $params = [];

            if (array_key_exists('compliant', $app->_config)) {
                $params['compliant'] = $app->_config['compliant']; // Denuncia
            }

            if (array_key_exists('suggestion', $app->_config)) {
                $params['suggestion'] = $app->_config['suggestion']; // Contato
            }

            if (array_key_exists('google-recaptcha-sitekey', $app->_config)) {
                $params['googleRecaptchaSiteKey'] = $app->_config['google-recaptcha-sitekey'];
            }

            $this->part('compliant_suggestion_ceara.php', $params);
        });

        $app->hook('template(opportunity.single.header-inscritos):end', function () use ($app) {
            $opportunity = $this->controller->requestedEntity;
            $inciso1_opportunity_id = isset($app->_config['plugins']['AldirBlanc']) ? $app->_config['plugins']['AldirBlanc']['config']['inciso1_opportunity_id'] : 0;

            if ($opportunity->id == $inciso1_opportunity_id) {
                $url = $app->createUrl('aldirblanc', 'inciso1ProcessResult');
                echo '<a class="btn btn-default" href="' . $url . '"> Processar Resultado das Avaliacoes Inciso 1 </a>';
            }
        });


        $theme = $this;
        //HOOK PARA TROCAR O STATUS DO AGENTE APOS A CRIAÇÃO DE UM USUARIO P/ RASCUNHO
        $app->hook('auth.createUser:after', function ($user) use ($app, $theme) {           
            $theme->fixAgentPermission($user);
                       
            $app->disableAccessControl();
            //Buscando o agente desse usuário
            $agent = $app->repo('Agent')->find($user->profile->id);
            $agent->status = 0;//alterando o status para rascunho
            $agent->save();
            $app->enableAccessControl();
        });

        $app->hook('auth.successful', function () use ($app, $theme) {
            $theme->fixAgentPermission($app->user);
        });

        //disparo de e-mail quando iniciar uma inscrição
        $app->hook("entity(Registration).insert:finish", function () use ($app){
            //Array com dados para preencher o template do email
            //@ParaMelhorar: chamar os dados atraves dos métodos mágicos
            $dataValue = [
                'siteName' => $app->view->dict('site: name', false),
                'baseUrl' => $app->getBaseUrl(),
                'userName' => $app->auth->authenticatedUser->profile->name,
                'projectId' => $this->entity['opportunity']->id,
                'projectName' => $this->entity['opportunity']->name,
                'registrationId' => $this->entity['id'],
                'registrationNumber' => $this->entity['number']
            ];
            
            $message = $app->renderMailerTemplate('start_registration',$dataValue);
            $app->createAndSendMailMessage([
                'from' => $app->config['mailer.from'],
                'to' => $app->auth->authenticatedUser->profile->user->email,
                'subject' => $message['title'],
                'body' => $message['body']
            ]);
            
        });

        //EM CASOS DE RECUPERAÇÃO DE SENHA, FOI CRIADO UM HOOK PARA SUBISTITUIR A MENSAGEM DE FEEDBACK
        $app->hook("template(auth.recover.head):begin", function() use($app){
            // O $this é o contexto do plugin multipleLocal
            $feedback_success = $this->feedback_success = true;
            $this->feedback_msg = i::__('Sucesso! Um e-mail foi enviado para a sua caixa de entrada. Caso não tenha recebido, favor verificar no spam ou lixo eletrônico. '."\n\n".' E-mail: '. $app->request->post('email'), 'multipleLocal');
            //PARA OCULTAR A DIV COM A MENSAGEM DO PLUGIN
            $this->enqueueScript('app', 'auth', 'js/auth/auth.js');
            //CRIADO UMA VIEW PARA MOSTRAR A MENSAGEM
            $this->part('auth/feedback', ['feedback_success' => $feedback_success, 'feedback_msg' => $this->feedback_msg]);
        });

        //HOOK PARA MELHORAR A IMPORTAÇÃO DOS ARQUIVOS
        $app->hook("entity(Opportunity).importFields:after", function (&$importSource, &$created_fields, &$created_files) use ($app) {
            //LOOP com os campos já criados            
            foreach($importSource->fields as $key => $field) {
                //Verifica se tem condicional
                if($importSource->fields[$key]->conditional)
                {
                    //separando o valor para obter somenete o id do campo original
                    $idParent = explode("_" , $importSource->fields[$key]->conditionalField);
                    //Id da oportunidade atual
                    $idOpActual = $field->newField->owner->id;

                    //Retorno da instancia do campo original
                    /**
                     * Busca o campo original e procura esse mesmo campo na oportunidade atual
                     * registrada na tabela para saber o ID do campo para substituir no campo 
                     * conditionalField da oportunidade atual
                     */
                    $fieldParent = $app->repo('RegistrationFieldConfiguration')->find($idParent[1]);

                    $fieldChidren = $app->repo('RegistrationFieldConfiguration')->findBy([
                        'owner' => $idOpActual,
                        'title' => $fieldParent->title,
                        'description' => $fieldParent->description,
                        'maxSize' => $fieldParent->maxSize ,
                        'fieldType' => $fieldParent->fieldType ,
                        'displayOrder' => $fieldParent->displayOrder
                    ]);
                    //Se encontrar registro
                    if(is_array($fieldChidren) && count($fieldChidren) > 0) {
                        //Adiciona ao campo conditionalField uma concatenação com o nome campo registrado para ter dep.
                        //anteriormente está sendo registrado o campo original perdendo o relacionamento no frontEnd
                        $field->newField->conditional = true;
                        $field->newField->conditionalField =  "field_" . strval($fieldChidren[0]->id) ;
                        $field->newField->conditionalValue = $field->conditionalValue;
                        $app->em->persist($field->newField);
                        $app->em->flush();

                    }
                }
            }
        });
       
        /**
         * Hook para excluir o registro que tem inscrições excluidas relacionada ao usuário como avalidor e etc
         */   
        $app->hook('view.partial(panel/opportunities):before', function ($arguments) use ($app) {
            //ADD MAIS MEMORIA PARA CASOS DE MUITO REGISTROS NA PCACHE
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 0);
           
            $opportunitiesPermission = $app->repo('MapasCulturais\Entities\PermissionCache')->findBy([
                'action' => 'viewUserEvaluation',
                'userId' => $app->user->id
            ]);
            //Se tiver registro
            if (count($opportunitiesPermission) > 0 ) {
                $opportunityIDs = [];
                foreach ($opportunitiesPermission as $keyOp => $opportunity) {
                    //TRATANDO ERRO EM CASO DA INSCRIÇÃO TER SIDO EXCLUIDA
                    try {
                        $op = $app->repo('Registration')->find($opportunity->objectId);
                        $opportunityIDs[] = $op->opportunity->id;
                    } catch (\Throwable $th) {
                        $deletePCache = $app->repo('MapasCulturais\Entities\PermissionCache')->find($opportunitiesPermission[$keyOp]);
                        $deletePCache->delete(true);
                    }
                }
            }
        });

        /**
         * Hook para inscluir aviso de admins para perfil de coletivo add a uma oportunidade
         */
        $app->hook('template(registration.view.form):begin', function() use ($app) {
            $this->part('registration/ceara/alert-collective');
        });

        /**
         * Hook para colocar link na lateral esqueda
         */
        $app->hook('template(<<*>>.nav.panel.userManagement):before', function() use ($app) {
            $url = $app->config['base.url'] . 'pesquisar/all';
            echo '<li>
            <a href="'. $url .'" target="_blank">
                    <span class="icon icon-publication-status-open"></span> Busca avançada Usuário </a>
            </li>';
        });

        /**
         * Previnir a importação de campos para uma oportunidade que já tem campos cadastrados
         */
        $app->hook('template(opportunity.edit.registration-config):begin', function() use ($app) {
            /**
             * @todo Adicionar biblioteca ao tema como um todo e remover essa importação
             */
            $app->view->enqueueStyle('app', 'swal2', 'swal2/swal2.secultce.min.css');
            $app->view->enqueueScript('app', 'swal2', 'swal2/sweetalert2.min.js');
            $app->view->enqueueScript('app', 'prevent-import-fields', 'js/opportunity-ceara/prevent-import-fields.js');
        });

        $app->hook('entity(<<Agent|Event|Project|Seal|Space>>).validations', function (&$properties_validations) use ($app) {
            unset($properties_validations['shortDescription']['v::stringType()->length(0,400)']);
            $properties_validations['shortDescription']['v::stringType()->length(0,900)'] = 'A descrição curta deve ter no máximo 900 caracteres';
        });

        //Hook somente para admin da oportunidade ou admin do mapa
        $app->hook('template(opportunity.single.header-inscritos):actions', function () use ($app) {
            //Buscando no banco de dados os agentes que podem publicar no site
            //taxonomy tem que está 'publish_site'
            $sitePublish = $app->repo('Term')->findBy(['taxonomy' => 'publish_site']);
            $agentesId = array_map(function($term) { return $term->term; }, $sitePublish);
            //Conferindo se quem está logado tem o id que consta no array do retorno do banco
            if ($app->user->is('superAdmin') || in_array($app->getUser()->profile->id, $agentesId)) {
                $this->part('opportunity/btn-publish-site');
            }
        });

        /**
         * Hook para criar um metadata da Oportunidade com registro de publicação no site
         * @params Object Request
         */
        $app->hook('POST(opportunity.publish_site)', function () use ($app) {
                //Recebendo id da oportunidade, instanciando OpportunityMeta e inserindo o metadata
                $op = $app->repo('Opportunity')->find($this->data['id']);
                $newOpMeta = new OpportunityMeta;
                $newOpMeta->owner = $op;
                $newOpMeta->key = 'publish_site';
                $newOpMeta->value = $this->postData['publish_site'];
                $error = $newOpMeta->save(true);

                if($error !== null){
                    $this->errorJson(false, 400);
                }
                $this->json(['message' => 'Publicação realizada com sucesso', 'status' => 200],200);

        });

        // Troca alguns termos em oportunidades/inscrições de prestação de contas
        $app->hook('view.partial(<<opportunity|registration>>/<<single|edit>>):after', function ($template, &$html) {
            $entity = $this->controller->requestedEntity;
            $opportunity = $entity instanceof Opportunity ? $entity : $entity->opportunity;
            $html = Utils::getTermsByOpportunity($html, $opportunity);
        });
    }

    /**
     * Mesmo método da Entidade User.php, mas com uma validação para tratar o erro
     * em caso de uma inscrição excluída
     *
     * @return void
     */
    function getOpportunitiesCanBeEvaluated() {
        $app = App::i();
        $app->user->profile->checkPermission('modify');
        $opportunities = [];
        $user_id = $app->user->id;
        
        $opportunitiesPermission = $app->repo('MapasCulturais\Entities\PermissionCache')->findBy([
            'action' => 'viewUserEvaluation',
            'userId' => $user_id
        ]);
       
        if (count($opportunitiesPermission) > 0 ) {
            $opportunityIDs = [];
            foreach ($opportunitiesPermission as $keyOp => $opportunity) {
                //TRATANDO ERRO EM CASO DA INSCRIÇÃO TER SIDO EXCLUIDA
                try {
                    $op = $app->repo('Registration')->find($opportunity->objectId);
                    $opportunityIDs[] = $op->opportunity->id;
                } catch (\Throwable $th) {
                    unset($opportunitiesPermission[$keyOp]);
                }
            }
           
            $opportunities = $app->repo('Opportunity')->findBy([
                'id' => $opportunityIDs,
                'status' => [Opportunity::STATUS_ENABLED, Agent::STATUS_RELATED]
            ]);

            foreach ($opportunities as $key => $opportunity) {
                $_is_opportunity_owner = $user_id === $opportunity->owner->userId;
                if (!$opportunity->evaluationMethodConfiguration->canUser('@control') || $_is_opportunity_owner) {
                    unset($opportunities[$key]);
                }
            }
        }
        return array_reverse($opportunities);
    }
    /**
     * atribuindo mensagem de alerta para manutenção
     */
    function alertMessageMaintenance()
    {
        $app = App::i();

        $app->_config['maintenance_enabled'] = false;
        $app->_config['maintenance_message'] = 'Sr(@), o Mapa Cultural passará por atualizações nos próximos dias. Não deixe sua inscrição para última hora';
    }
    /**
     *
     * {@inheritdoc}
     * @see \MapasCulturais\Themes\BaseV1\Theme::_publishAssets()
     */
    protected function _publishAssets()
    {
        $this->jsObject['assets']['fundo'] = $this->asset('img/backgroud.png', false);
        $this->jsObject['assets']['email-aldir'] = $this->asset('img/email-aldir.png', false);
        $this->jsObject['assets']['lei-aldir'] = $this->asset('img/lei-aldir.png', false);
        $this->jsObject['assets']['lei-aldir-small'] = $this->asset('img/lei-aldir-small.png', false);
        $this->jsObject['assets']['logo-ce-small'] = $this->asset('img/logo-org-ceara-small.png', false);
        $this->enqueueStyle('app', 'secultalert', 'css/secultce/dist/secultce.min.css');
        $this->enqueueScript('app','sweetalert2','https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js');
    }

    /**
     *
     * {@inheritdoc}
     * @see \MapasCulturais\Themes\BaseV1\Theme::register()
     */
    public function register()
    {
        $app = App::i();
        parent::register();

        $app->registerController('pesquisar', Controllers\SearchAll::class);
        $app->registerController('quantidadeCampos', \Ceara\Controllers\OpportunityFields::class);

        /**
         * Adicionando novos metadata na entidade Projeto
         *
         */
        $this->registerProjectMetadata('contraPartida', [
            'private' => false,
            'label' => \MapasCulturais\i::__('Preencha aqui a contrapartida do projeto'),
            'type' => 'text',
        ]);

        $this->registerProjectMetadata('valor', [
            'private' => false,
            'label' => \MapasCulturais\i::__('Informe o valor do projeto'),
            'type' => 'string',
        ]);

        $this->registerAgentMetadata('documento', [
            'private' => false,
            'label' => \MapasCulturais\i::__('CPF ou CNPJ'),
            'validations' => array(
                'v::oneOf(v::cpf(),v::cnpj())' => \MapasCulturais\i::__('O número de documento informado é inválido.'),
                // 'required' => \MapasCulturais\i::__('O CPF é obrigatório'),//VALIDAÇÃO ATUALMENTE PELO AGENT-TYPES
            ),
            'available_for_opportunities' => false
        ]);

        $this->registerAgentMetadata('En_Municipio', [
            'label' => \MapasCulturais\i::__('Município'),
            'type' => 'select',
            'validations' => array(
                'required' => \MapasCulturais\i::__('O município é obrigatório'),
            ),
            'private' => false,
            'options' => array(
                "ABAIARA",
                "ACARAPE",
                "ACARAU",
                "ACOPIARA",
                "AIUABA",
                "ALCANTARAS",
                "ALTANEIRA",
                "ALTO SANTO",
                "AMONTADA",
                "ANTONINA DO NORTE",
                "APUIARES",
                "AQUIRAZ",
                "ARACATI",
                "ARACOIABA",
                "ARARENDA",
                "ARARIPE",
                "ARATUBA",
                "ARNEIROZ",
                "ASSARE",
                "AURORA",
                "BAIXIO",
                "BANABUIU",
                "BARBALHA",
                "BARREIRA",
                "BARRO",
                "BARROQUINHA",
                "BATURITE",
                "BEBERIBE",
                "BELA CRUZ",
                "BOA VIAGEM",
                "BREJO SANTO",
                "CAMOCIM",
                "CAMPOS SALES",
                "CANINDE",
                "CAPISTRANO",
                "CARIDADE",
                "CARIRE",
                "CARIRIACU",
                "CARIUS",
                "CARNAUBAL",
                "CASCAVEL",
                "CATARINA",
                "CATUNDA",
                "CAUCAIA",
                "CEDRO",
                "CHAVAL",
                "CHORO",
                "CHOROZINHO",
                "COREAU",
                "CRATEUS",
                "CRATO",
                "CROATA",
                "CRUZ",
                "DEPUTADO IRAPUAN PINHEIRO",
                "ERERE",
                "EUSEBIO",
                "FARIAS BRITO",
                "FORQUILHA",
                "FORTALEZA",
                "FORTIM",
                "FRECHEIRINHA",
                "GENERAL SAMPAIO",
                "GRACA",
                "GRANJA",
                "GRANJEIRO",
                "GROAIRAS",
                "GUAIUBA",
                "GUARACIABA DO NORTE",
                "GUARAMIRANGA",
                "HIDROLANDIA",
                "HORIZONTE",
                "IBARETAMA",
                "IBIAPINA",
                "IBICUITINGA",
                "ICAPUI",
                "ICO",
                "IGUATU",
                "INDEPENDENCIA",
                "IPAPORANGA",
                "IPAUMIRIM",
                "IPU",
                "IPUEIRAS",
                "IRACEMA",
                "IRAUCUBA",
                "ITAICABA",
                "ITAITINGA",
                "ITAPAGE",
                "ITAPIPOCA",
                "ITAPIUNA",
                "ITAREMA",
                "ITATIRA",
                "JAGUARETAMA",
                "JAGUARIBARA",
                "JAGUARIBE",
                "JAGUARUANA",
                "JARDIM",
                "JATI",
                "JIJOCA DE JERICOACOARA",
                "JUAZEIRO DO NORTE",
                "JUCAS",
                "LAVRAS DA MANGABEIRA",
                "LIMOEIRO DO NORTE",
                "MADALENA",
                "MARACANAU",
                "MARANGUAPE",
                "MARCO",
                "MARTINOPOLE",
                "MASSAPE",
                "MAURITI",
                "MERUOCA",
                "MILAGRES",
                "MILHA",
                "MIRAIMA",
                "MISSAO VELHA",
                "MOMBACA",
                "MONSENHOR TABOSA",
                "MORADA NOVA",
                "MORAUJO",
                "MORRINHOS",
                "MUCAMBO",
                "MULUNGU",
                "NOVA OLINDA",
                "NOVA RUSSAS",
                "NOVO ORIENTE",
                "OCARA",
                "OROS",
                "PACAJUS",
                "PACATUBA",
                "PACOTI",
                "PACUJA",
                "PALHANO",
                "PALMACIA",
                "PARACURU",
                "PARAIPABA",
                "PARAMBU",
                "PARAMOTI",
                "PEDRA BRANCA",
                "PENAFORTE",
                "PENTECOSTE",
                "PEREIRO",
                "PINDORETAMA",
                "PIQUET CARNEIRO",
                "PIRES FERREIRA",
                "PORANGA",
                "PORTEIRAS",
                "POTENGI",
                "POTIRETAMA",
                "QUITERIANOPOLIS",
                "QUIXADA",
                "QUIXELO",
                "QUIXERAMOBIM",
                "QUIXERE",
                "REDENCAO",
                "RERIUTABA",
                "RUSSAS",
                "SABOEIRO",
                "SALITRE",
                "SANTANA DO ACARAU",
                "SANTANA DO CARIRI",
                "SANTA QUITERIA",
                "SAO BENEDITO",
                "SAO GONCALO DO AMARANTE",
                "SAO JOAO DO JAGUARIBE",
                "SAO LUIS DO CURU",
                "SENADOR POMPEU",
                "SENADOR SA",
                "SOBRAL",
                "SOLONOPOLE",
                "TABULEIRO DO NORTE",
                "TAMBORIL",
                "TARRAFAS",
                "TAUA",
                "TEJUCUOCA",
                "TIANGUA",
                "TRAIRI",
                "TURURU",
                "UBAJARA",
                "UMARI",
                "UMIRIM",
                "URUBURETAMA",
                "URUOCA",
                "VARJOTA",
                "VARZEA ALEGRE",
                "VICOSA DO CEARA",

            )
        ]);

        $this->registerAgentMetadata('cnpj', [
            'private' => true,
            'label' => \MapasCulturais\i::__('CNPJ'),
            'serialize' => function($value, $entity = null){
                $app = \MapasCulturais\App::i();
                /**@var MapasCulturais\App $this */
                $key = "hook:cnpj:{$entity}";
                if(!$app->cache->contains($key)){
                    if($entity->type && $entity->type->id == 2){
                        $entity->documento = $value;
                    }
                    $app->cache->save($key, 1);
                }
                return Utils::formatCnpjCpf($value);
            },
            'validations' => array(
                'v::cnpj()' => \MapasCulturais\i::__('O número de CNPJ informado é inválido.')
             ),
            'available_for_opportunities' => true,
        ]);

        $this->registerOpportunityMetadata('publish_site', [
            'type' => 'text',
            'label' => \MapasCulturais\i::__('Publicar no site')
        ]);

        $this->registerOpportunityMetadata('hasVacanciesForQuotaHolders', [
            'label' => \MapasCulturais\i::__('Selecione uma opção'),
            'type' => 'select',
            'options' => ['Sim', 'Não'],
            'default' => 'Sim',
        ]);

        $this->registerOpportunityMetadata('numberVacancies', [
            'label' => \MapasCulturais\i::__('Digite o número de vagas'),
            'type' => 'string',
            'validations' => [
                'v::intVal()->positive()' => 'O valor deve ser um número maior que zero'
            ]
        ]);

        //GERANDO NOVAS TAXONOMIA DE FUNCAO - NECESSÁRIO PARA V5.6.20
        $newsTaxo = array(
            i::__("Aderecista"),
            i::__("Adestrador(a) para Artes Cênicas"),
            i::__("Afinador de Instrumentos"),
            i::__("Ajudante de Câmera"),
            i::__("Ajudante de Locação"),
            i::__("Ajudante de Objetos"),
            i::__("Ajudante de Produção"),
            i::__("Amestrador(a)"),
            i::__("Aplicador(a) de sign"),
            i::__("Armeiro(a)"),
            i::__("Assessor(a) de Comunicação"),
            i::__("Assistente Administrativo"),
            i::__("Assistente de Arte"),
            i::__("Assistente de Artista Gráfico"),
            i::__("Assistente de Backstage"),
            i::__("Assistente de Camarim"),
            i::__("Assistente de Câmera"),
            i::__("Assistente de Cenotécnica"),
            i::__("Assistente de Continuidade"),
            i::__("Assistente de Controller"),
            i::__("Assistente de Criação Artística"),
            i::__("Assistente de Edição"),
            i::__("Assistente de Edição de Som"),
            i::__("Assistente de Eletricista"),
            i::__("Assistente de Figurino"),
            i::__("Assistente de Ilha de Edição"),
            i::__("Assistente de Maquiagem"),
            i::__("Assistente de Maquinaria"),
            i::__("Assistente de Montagem"),
            i::__("Assistente de Objetos"),
            i::__("Assistente de Pós Produção"),
            i::__("Assistente de Produção (Base ou Set"),
            i::__("Assistente de Produção de Arte"),
            i::__("Assistente de Produção de Elenco"),
            i::__("Assistente de Produção de Locação"),
            i::__("Assistente de Produção em geral"),
            i::__("Assistente de Produção Musical"),
            i::__("Assistente de Roteiro"),
            i::__("Assistente de Som"),
            i::__("Assistente de Transporte"),
            i::__("Assistente Mixador(a)"),
            i::__("Audiodescritor(a)"),
            i::__("Barreira"),
            i::__("Bilheteiro(a)"),
            i::__("Bordadeiro(a)"),
            i::__("Cabeleireiro"),
            i::__("Cabeleireiro(a)"),
            i::__("Camareiro(a)"),
            i::__("Capataz(a) - Montador(a)"),
            i::__("Carpinteiro(a)"),
            i::__("Cenógrafo(a)"),
            i::__("Cenotécnico(a)"),
            i::__("Chefe de Maquinária"),
            i::__("Cinegrafista"),
            i::__("Colorista"),
            i::__("Colorista Assistente"),
            i::__("Consultor de Imagem"),
            i::__("Consultor em Legendagem"),
            i::__("Consultor(a) em Audiodescrição"),
            i::__("Consultor(a) em Braille"),
            i::__("Consultor(a) em Libras"),
            i::__("Continuista"),
            i::__("Contrarregra"),
            i::__("Coolhunter"),
            i::__("Coordenador(a) de Palco"),
            i::__("Cortineiro(a)"),
            i::__("Costureiro(a)"),
            i::__("Design de Montagem"),
            i::__("Diagramador(a)"),
            i::__("Digitalizador(a)"),
            i::__("Diretor(a) de Palco"),
            i::__("Editor de Partituras"),
            i::__("Editor(a)"),
            i::__("Editor(a) de Som"),
            i::__("Eletricista"),
            i::__("Eletricista Chefe"),
            i::__("Engenheiro(a) de Som"),
            i::__("Ensaiador(a)"),
            i::__("Estampador"),
            i::__("Figurinista"),
            i::__("Foley"),
            i::__("Guia-intérprete de língua de sinais"),
            i::__("Iluminador"),
            i::__("Iluminador(a)"),
            i::__("Legendista ou Tradutor(a)"),
            i::__("Logger"),
            i::__("Luthier"),
            i::__("Maquiador"),
            i::__("Maquiador(a)"),
            i::__("Maquinista"),
            i::__("Marcador(a) de Cena"),
            i::__("Mediador(a)"),
            i::__("Mestre(a) de Pista"),
            i::__("Montador(a)"),
            i::__("Montador(a) de Palco"),
            i::__("Motorista de Audiovisual"),
            i::__("Músico-Musicista"),
            i::__("Operador(a) de Luz"),
            i::__("Operador(a) de Som"),
            i::__("Outra função técnica"),
            i::__("Passador (a)"),
            i::__("Peruqueiro"),
            i::__("Peruqueiro(a)"),
            i::__("Pilotista"),
            i::__("Produtor de Casting"),
            i::__("Produtor(a) de Casting/Booker"),
            i::__("Produtor(a) Fonográfico"),
            i::__("Radialista"),
            i::__("Roadie"),
            i::__("Sapateiro"),
            i::__("Secretario(a) de Frente"),
            i::__("Secretário(a) Teatral"),
            i::__("Serigrafista"),
            i::__("Sonoplasta"),
            i::__("Tatuador"),
            i::__("Técnico de Luz"),
            i::__("Técnico(a) Contábil"),
            i::__("Técnico(a) de Estúdio"),
            i::__("Técnico(a) de Luz"),
            i::__("Técnico(a) de Palco"),
            i::__("Técnico(a) de Som"),
            i::__("Técnico(a) de Som Direto"),
            i::__("Tradutor(a) ou Intérprete de Libras"),
            i::__("Transcritor ou Revisor em Braille"),
            i::__("Visagista"),
            i::__("Visual Merchandiser"),
            i::__("Vitrinista"),
        );
        //ID É O VALOR DO INDICE DO ARRAY DO ARQUIVO TAXONOMI
        $def = new \MapasCulturais\Definitions\Taxonomy(6, 'funcao', 'Função', $newsTaxo, false);
        $app->registerTaxonomy('MapasCulturais\Entities\Agent', $def);


    }

    /**
     * Fix agent Permission
     *
     * @return void
     */
    public function fixAgentPermission($user)
    {
        $app = App::i();
        $conn = $app->em->getConnection();
        //VERIFICA SE TEM AGENTE COM O USUÁRIO QUE RECEBER
        $agents = $app->repo('Agent')->findBy(['user' => $user]);
        //PARA NA HIPOTESE DE SER MAIS QUE 2 REGISTROS ENCONTRADOS
        if (count($agents) > 2) return;

        if ($user->createTimestamp < new \DateTime("2020-02-15")) return;
        $agent_profile = $app->repo('Agent')->findBy(['id' => $user->profile->id, 'status' => 0]);

        if (count($agent_profile) > 1) {
            $agent_profile[0]->status = 1;
            $agent_profile[0]->save(true);
        }

        $actions = [
            '@control', 'create', 'remove', 'destroy', 'changeOwner', 'archive', 'view', 'modify', 'viewPrivateFiles', 'viewPrivateData', 'createAgentRelation', 'createAgentRelationWithControl', 'removeAgentRelation', 'removeAgentRelationWithControl', 'createSealRelation', 'removeSealRelation'
        ];

        //Para cada agent individual adiciona as permissoes que podem estar faltando
        foreach ($agents as $agent) {
            if ($agent->type->id == 2) return;
            $pcaches = $conn->fetchAll('select action from pcache where user_id = ' . $user->id . ' and object_id = ' . $agent->id);
            foreach ($actions as $action) {
                $has_permission = false;
                foreach ($pcaches as $p) {
                    if ($p['action'] == $action) {
                        $has_permission = true;
                        break;
                    }
                }
                if ($has_permission === false) {
                    $permission = new \MapasCulturais\Entities\AgentPermissionCache();
                    $permission->owner = $agent;
                    $permission->action = $action;
                    $permission->user = $user;
                    $permission->createTimestamp = new \DateTime;
                    $app->em->persist($permission);
                    $app->em->flush();
                }
            }
        }
    }
}
