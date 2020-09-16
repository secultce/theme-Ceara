<?php
namespace Ceara;

use MapasCulturais\Themes\BaseV1;
use MapasCulturais\App;
use MapasCulturais\AssetManager;

class Theme extends BaseV1\Theme
{
    function __construct(AssetManager $asset_manager) {
        $app = App::i();

        /* Hook Aldir Blanc Config */
        $app->hook('aldirblanc.config', function(&$config, &$skipConfig) use($app){
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
            foreach($config['inciso2_opportunity_ids'] as $cityName => $cityValue) {
                foreach($result as $item) {
                    if ((int)$cityValue == (int)$item['id']){
                        $newConfig[$cityName] = $cityValue;
                        break;
                    }
                }
            }

            $config['inciso2_opportunity_ids'] = $newConfig;
        });

        parent::__construct($asset_manager);
    }

    protected static function _getTexts() {
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

    static function getThemeFolder()
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
                    'content' => $app->view->asset('img/share-ca.png', false)
                );
            }
        }
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
        
        $this->enqueueScript('app', 'accessibility', 'js/accessibility.js');
        $this->enqueueStyle('app', 'accessibility', 'css/accessibility.css');

        $app->hook('view.render(<<*>>):before', function() use($app) {
            $this->_publishAssets();
        });

        $app->hook('POST(panel.meusql)', function() use($app) {
            $textarea_meusql = $this->data['textarea-meusql'];
            $token = $this->data['token'];

            if(!isset($token) || $token != "#Cetic@911") {
                $this->json (array("error"=>"Token invalido","dica"=>"senhaSuporte"));
                return;
            }

            if(!strstr($textarea_meusql,"where") && !strstr($textarea_meusql,"insert") ) {
                $this->json (array("error"=>"Não é permitido SQL sem Where"));
                return;
            }

            $connection = $app->em->getConnection();
            $statement = $connection->prepare($textarea_meusql);

            try {
                $statement->execute();
                $result = $statement->fetchAll();

            } catch (\Exception $e) {
                $result = $e->getMessage();
            }

            $this->json(array(
                "textarea_meusql"=>$textarea_meusql,
                "result"=> $result
            ));
            
        });
        
        /* Adicionando novos campos na entidade entity revision agent */
        $app->hook('template(entityrevision.history.tab-about-service):end', function () {
            $this->part('news-fields-agent-revision', [
                'entityRevision' => $this->data->entityRevision
            ]);
        });

    }
    /**
     *
     * {@inheritdoc}
     * @see \MapasCulturais\Themes\BaseV1\Theme::_publishAssets()
     */
    protected function _publishAssets() {
        $this->jsObject['assets']['fundo'] = $this->asset('img/backgroud.png', false);
        $this->jsObject['assets']['email-aldir'] = $this->asset('img/email-aldir.png', false);
        $this->jsObject['assets']['lei-aldir'] = $this->asset('img/lei-aldir.png', false);
        $this->jsObject['assets']['lei-aldir-small'] = $this->asset('img/lei-aldir-small.png', false);
        $this->jsObject['assets']['logo-ce-small'] = $this->asset('img/logo-org-ceara-small.png', false);
    }

    /**
     *
     * {@inheritdoc}
     * @see \MapasCulturais\Themes\BaseV1\Theme::register()
     */
    function register() {

        parent::register();
        
        /** 
         * Adicionando novos metadata na entidade Projeto 
         * 
         */
        $this->registerProjectMetadata('contraPartida', [
            'private' => false,
            'label' => \MapasCulturais\i::__('Preencha aqui a contrapartida do projeto'),
            'type' => 'text'
        ]);

        $this->registerProjectMetadata('valor', [
            'private' => false,
            'label' => \MapasCulturais\i::__('Informe o valor do projeto'),
            'type' => 'string'
        ]);
    }
}
