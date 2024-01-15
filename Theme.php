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
use MapasCulturais\Entities\RegistrationFileConfiguration;
use MapasCulturais\Entities\RegistrationFieldConfiguration;
use MapasCulturais\Entities\RegistrationFileConfigurationFile;
use Doctrine\ORM\Query\ResultSetMapping;
use function MapasCulturais\dump;

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

        $app->hook('view.render(<<*>>):before', function () use ($app) {
            $this->_publishAssets();
        });

        //HOOK PROVISÓRIO PARA ADD SELOS AOS AGENTES SELECIONADOS
        $app->hook('GET(seal.atribuir-agente)', function () use ($app) {
         
           $codUser = [10034,
           10047,
           10070,
           10160,
           10170,
           10223,
           10229,
           102380,
           103245,
           10352,
           103686,
           10377,
           10497,
           10523,
           105326,
           10536,
           105559,
           106122,
           10658,
           107251,
           107483,
           108121,
           108322,
           108475,
           108581,
           108598,
           108700,
           108714,
           108905,
           11036,
           111397,
           11203,
           11469,
           11695,
           117240,
           117248,
           117425,
           117426,
           117660,
           117667,
           117670,
           117758,
           117767,
           117802,
           117823,
           117825,
           117909,
           117958,
           117973,
           117994,
           118007,
           118077,
           118115,
           118163,
           118174,
           118189,
           118198,
           118206,
           118209,
           11905,
           12062,
           123294,
           123299,
           12761,
           13569,
           13883,
           14036,
           16222,
           16432,
           17314,
           17358,
           17488,
           17513,
           17755,
           18450,
           18641,
           18701,
           19025,
           19025,
           19051,
           20172,
           20197,
           21377,
           21628,
           21671,
           25070,
           25229,
           26988,
           27714,
           28638,
           29512,
           29565,
           29628,
           29734,
           29772,
           29942,
           30076,
           31015,
           33076,
           33353,
           33414,
           33442,
           33847,
           33851,
           33851,
           33952,
           34088,
           34119,
           34277,
           34320,
           34321,
           34341,
           34385,
           34410,
           34543,
           35187,
           35347,
           35778,
           35792,
           35824,
           36605,
           36616,
           36735,
           36939,
           37052,
           37380,
           37392,
           37728,
           37955,
           38218,
           38275,
           38468,
           38481,
           41469,
           42332,
           42947,
           43071,
           43117,
           43290,
           43747,
           43809,
           44916,
           44953,
           45254,
           45985,
           46345,
           46618,
           47334,
           47601,
           48913,
           51243,
           51584,
           52110,
           53512,
           53616,
           53630,
           57690,
           58067,
           58746,
           58907,
           59653,
           60017,
           6075,
           61010,
           61271,
           61524,
           61663,
           62102,
           63876,
           6455,
           65055,
           66179,
           6657,
           6751,
           6794,
           68333,
           69332,
           6959,
           6964,
           6970,
           6986,
           7264,
           7700,
           7718,
           8229,
           8298,
           8301,
           8345,
           8351,
           8358,
           8373,
           8379,
           8385,
           8414,
           8437,
           8449,
           8493,
           8522,
           8553,
           8558,
           8596,
           8609,
           8621,
           8621,
           8623,
           8626,
           8656,
           8662,
           8694,
           8696,
           8701,
           8705,
           8738,
           8819,
           9051,
           9119,
           9321,
           9387,
           9574,
           9660,
           9706,
           97213,
           97259,
           9728,
           9762,
           97938,
           9799,
           9874,
           9876,
           9927];

            foreach ($codUser as $key => $agent) {
                $query_insert_seal = "INSERT INTO seal_relation (id, seal_id, object_id, create_timestamp, status, object_type, agent_id, owner_id, validate_date, renovation_request)
                VALUES ( nextval('seal_relation_id_seq'), 18, '$agent', '2023-08-29 10:47:32.000', 1, 'MapasCulturais\Entities\Agent', 5975, 5975, '2023-08-29', null)";
                $stmt_file = $app->em->getConnection()->prepare($query_insert_seal);
                $stmt_file->execute();
            }

            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('id', 'occurrence_id');
            //verificando se o ultimo agente foi cadastrado
            $query = $app->em->createNativeQuery("SELECT id FROM seal_relation WHERE object_id = 9927 AND object_type = 'MapasCulturais\Entities\Agent'", $rsm);
            $divisions = $query->getScalarResult();

            if(is_array($divisions) && count($divisions) > 0)
            {
                echo "Cadastro realizado com sucesso";
            }
        });
        $app->hook('GET(panel.updatealdirblancinciso2)', function () use ($app) {
            ini_set('max_execution_time', 0);

            $app = App::i();
            $app->disableAccessControl();

            $aldirblancInciso2Cities = '[
                {
                "CIDADE": "ABAIARA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "CICERO ANDRÉ DOS SANTOS OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/40258",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2161
                },
                {
                "CIDADE": "ACARAPE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSÉ ARCELINO DA SILVA NETO",
                "PERFIL": "",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal nem do responsável pela inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2162
                },
                {
                "CIDADE": "ACARAÚ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCIA MARIA GOMES DE ANDRADE GONÇALVES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/13466",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2163
                },
                {
                "CIDADE": "ACOPIARA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WALTER CLEBER ALVES DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46006/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2164
                },
                {
                "CIDADE": "AIUABA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO TIEGO DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/12088/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2165
                },
                {
                "CIDADE": "ALCÂNTARAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOAQUIM SEVERIANO SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/29197",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2166
                },
                {
                "CIDADE": "ALTANEIRA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUIZ PEDRO BEZERRA NETO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45976/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2167
                },
                {
                "CIDADE": "ALTO SANTO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "TARCILIO JEFFERSON DE LIMA MOREIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16881/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2168
                },
                {
                "CIDADE": "AMONTADA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ALLTEMY CARNEIRO MOURA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/12080/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2169
                },
                {
                "CIDADE": "ANTONINA DO NORTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCA DE MATOS ARRAIS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11761/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2170
                },
                {
                "CIDADE": "APUIARÉS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO REGINALDO PEREIRA DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8759/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2171
                },
                {
                "CIDADE": "AQUIRAZ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCELO FREITAS DAS CHAGAS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9529/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2172
                },
                {
                "CIDADE": "ARACATI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ALDELINO DE OLIVEIRA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42399/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2173
                },
                {
                "CIDADE": "ARACOIABA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCOS AURÉLIO DA SILVA PIMENTA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/44136",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2174
                },
                {
                "CIDADE": "ARARENDÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ALEXANDRE NIBON MOURÃO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/13451/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2175
                },
                {
                "CIDADE": "ARARIPE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "NIVALDO JOSÉ DE SOUZA NOGUEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9989/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2176
                },
                {
                "CIDADE": "ARATUBA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSE ARIMATEIA DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/21563/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2177
                },
                {
                "CIDADE": "ARNEIROZ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RAIMUNDA GHYSLAINE SALVIANO ARAÚJO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51564/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2178
                },
                {
                "CIDADE": "ASSARÉ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO VAGNER PEREIRA GÓIS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16397/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2179
                },
                {
                "CIDADE": "AURORA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "CÍCERO MARCIANO ALVES DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/34680/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2180
                },
                {
                "CIDADE": "BAIXIO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA DO SOCORRO SALES SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42435",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2181
                },
                {
                "CIDADE": "BANABUIÚ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "GERLANIA MARIA LEMOS NOBRE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51565/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2182
                },
                {
                "CIDADE": "BARBALHA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RODRIGO TORRES SAMPAIO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11350/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2183
                },
                {
                "CIDADE": "BARRO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WELLINGTON OLIVEIRA SOARES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8368/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2184
                },
                {
                "CIDADE": "BARROQUINHA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUCIA CÉLIA DA ROCHA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16459/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2185
                },
                {
                "CIDADE": "BATURITÉ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WEDNEY RODRIGUES DE SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16164/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2186
                },
                {
                "CIDADE": "BEBERIBE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JESSYCA MENDES RODRIGUES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46124",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2187
                },
                {
                "CIDADE": "BELA CRUZ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA QUIRIANE NASCIMENTO LEITE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51114/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2188
                },
                {
                "CIDADE": "BOA VIAGEM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANDREA ALVES DE SOUSA CAVALCANTE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/37224/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2189
                },
                {
                "CIDADE": "BREJO SANTO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO DAVID DOS SANTOS JUNIOR",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24874",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2190
                },
                {
                "CIDADE": "CAMOCIM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "EGLAUBER CIRIACO LIMA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46516/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2191
                },
                {
                "CIDADE": "CAMPOS SALES",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSÉ WILTON LEITE SOBRINHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14071",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2192
                },
                {
                "CIDADE": "CANINDÉ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RÔMULO LAURÊNIO DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24493/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2193
                },
                {
                "CIDADE": "CAPISTRANO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOABY LIMA DUARTE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/18411/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2194
                },
                {
                "CIDADE": "CARIRÉ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCELO ARAÚJO ALVES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9008/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2195
                },
                {
                "CIDADE": "CARIRIAÇU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "PAULO ROBERTO DO MONTE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24680/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2196
                },
                {
                "CIDADE": "CARIÚS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "KLIFTON NOGUEIRA DE CARVALHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/52079/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2197
                },
                {
                "CIDADE": "CASCAVEL",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCOS ANTONIO PEREIRA DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8299",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2198
                },
                {
                "CIDADE": "CATUNDA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "VITAL ARAUJO DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/17473/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2199
                },
                {
                "CIDADE": "CAUCAIA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO EUGÊNIO COSTA OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24896/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2200
                },
                {
                "CIDADE": "CEDRO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "THATYANA KAYRONE MARINHEIRO DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/49797",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2201
                },
                {
                "CIDADE": "CHAVAL",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MAURICIO MELO MENDES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51885",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2202
                },
                {
                "CIDADE": "CHORÓ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RENEI BENÍCIO DE SÁ FREITAS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/10285",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2203
                },
                {
                "CIDADE": "CHOROZINHO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WALDEVAL SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14680/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2204
                },
                {
                "CIDADE": "COREAÚ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SABRINA CRISTINO DE ARAÚJO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46857",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2205
                },
                {
                "CIDADE": "CRATEÚS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ALFREDO JADER VERAS TORRES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16493/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2206
                },
                {
                "CIDADE": "CRATO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "VALCICLEIA NUNES FERREIRA FEITOSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51880/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2207
                },
                {
                "CIDADE": "CROATÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "AURENI GONÇALVES FEITOSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/53477/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2208
                },
                {
                "CIDADE": "CRUZ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JORGE PAULO DA SILVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/10116/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2209
                },
                {
                "CIDADE": "DEPUTADO IRAPUAN PINHEIRO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIO ILVANCÉLIO GUEDES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/17014",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2210
                },
                {
                "CIDADE": "ERERÊ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARTA MARIA DE PAIVA SANTOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/44790/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2211
                },
                {
                "CIDADE": "EUSÉBIO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MACIEL EDUARDO DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45591/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2212
                },
                {
                "CIDADE": "FARIAS BRITO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MANOEL NAILSON TEIXEIRA DE CARVALHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/10031",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2213
                },
                {
                "CIDADE": "FORQUILHA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "GREYCY KELLY SALES PINHEIRO MARTINS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/47824/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2214
                },
                {
                "CIDADE": "FORTALEZA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "EVISON RODRIGUES DE CARVALHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/28042/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2215
                },
                {
                "CIDADE": "FORTIM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FLÁVIO MARCELO BARBOSA PINTO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/52027",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2216
                },
                {
                "CIDADE": "FRECHEIRINHA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "EVANDRO AGUIAR PONTES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45456/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2217
                },
                {
                "CIDADE": "GENERAL SAMPAIO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO DAVI MACENA LOPES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16162",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2218
                },
                {
                "CIDADE": "GRANJA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA DO LIVRAMENTO ARAUJO XIMENES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/10297/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2219
                },
                {
                "CIDADE": "GRANJEIRO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "CICERO MURILO DE SOUSA BEZERRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/47518/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2220
                },
                {
                "CIDADE": "GROAÍRAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUCIA PAULA MATOS XIMENES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9941/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2221
                },
                {
                "CIDADE": "GUAIÚBA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIA ARAUJO DA SILVA ALMEIDA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45882",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2222
                },
                {
                "CIDADE": "GUARACIABA DO NORTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "PAULO ROBERTO CATUNDA RODRIGUES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/38030/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2223
                },
                {
                "CIDADE": "GUARAMIRANGA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "IVAN VALENTIM",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8194/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2224
                },
                {
                "CIDADE": "HIDROLÂNDIA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIA CLÉZIA FEITOSA DE SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/26650",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2225
                },
                {
                "CIDADE": "HORIZONTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "VANIA MARIA DUTRA DE MELO SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16499/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2226
                },
                {
                "CIDADE": "IBARETAMA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA VERÔNICA DA SILVA MELO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46531",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2227
                },
                {
                "CIDADE": "IBIAPINA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIO AURELIO COSME DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46258",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2228
                },
                {
                "CIDADE": "IBICUITINGA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RITA DE CASSIA NOBRE DE MEDEIROS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/52071/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2229
                },
                {
                "CIDADE": "ICAPUÍ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MANUEL DE FREITAS FILHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/17409",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2230
                },
                {
                "CIDADE": "ICÓ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "DANIEL BRUNO BATISTA MARTINS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42409/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2231
                },
                {
                "CIDADE": "IGUATU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO FABRICIO FRANCO VIEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/44501/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2232
                },
                {
                "CIDADE": "INDEPENDÊNCIA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCELO VICTOR TORRES PINTO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/49755/",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2233
                },
                {
                "CIDADE": "IPAPORANGA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ELIVELSON RODRIGUES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8567",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2234
                },
                {
                "CIDADE": "IPAUMIRIM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ROBSON RAUL BARBOSA DE MOURA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/47693/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2235
                },
                {
                "CIDADE": "IPU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SILVIO CARVALHO BEZERRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45448/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2236
                },
                {
                "CIDADE": "IPUEIRAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "PEDRO EMMY ALVES DA COSTA MOREIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/17537/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2237
                },
                {
                "CIDADE": "IRACEMA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FÁBIO GOMES DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42752/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2238
                },
                {
                "CIDADE": "IRAUÇUBA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MÁRCIA HELENA SANTOS BARRETO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24287/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2239
                },
                {
                "CIDADE": "ITAIÇABA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCÍLIA GALDINO DE SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24634/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2240
                },
                {
                "CIDADE": "ITAITINGA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WERLYANA BARBOSA BRUNO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11444/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2241
                },
                {
                "CIDADE": "ITAPAJÉ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA GISELE DUARTE MOURA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/37174",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2242
                },
                {
                "CIDADE": "ITAPIPOCA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIO MARCOS BRAGA VIANA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9297/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2243
                },
                {
                "CIDADE": "ITAPIÚNA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSÉ HUDSON MENEZES OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/16213/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2244
                },
                {
                "CIDADE": "ITATIRA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCOS LENNON JUCÁ LOPES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/5501/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2245
                },
                {
                "CIDADE": "JAGUARETAMA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCOS JUNGLAS MIRANDA TEÓFILO SOBRINHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11271/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2246
                },
                {
                "CIDADE": "JAGUARIBARA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ELIZABETT PEIXOTO BEZERRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/52084/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2247
                },
                {
                "CIDADE": "JAGUARIBE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "DAVI BEZERRA VIEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11285/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2248
                },
                {
                "CIDADE": "JAGUARUANA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SERGIANA MARIA FREITAS DE ALMEIDA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/5413/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2249
                },
                {
                "CIDADE": "JARDIM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUIZ PEREIRA LEMOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/29372/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2250
                },
                {
                "CIDADE": "JATI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUÍS BENTO DE SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/52811/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2251
                },
                {
                "CIDADE": "JIJOCA DE JERICOACOARA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "CELIOMAR DE ARAÚJO BRANDÃO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/34590/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2252
                },
                {
                "CIDADE": "JUAZEIRO DO NORTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ROSANA PEREIRA MARINHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/5345/",
                "CHECK": "mudou",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2253
                },
                {
                "CIDADE": "JUCÁS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "AÍLA MARIA GOMES LUNA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/18696",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2254
                },
                {
                "CIDADE": "LAVRAS DA MANGABEIRA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "YGOR FERREIRA MACÊDO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/43904",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2255
                },
                {
                "CIDADE": "LIMOEIRO DO NORTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RENATO MAIA REMÍGIO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/13945/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2256
                },
                {
                "CIDADE": "MADALENA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SUYANE MARA GOMES DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/32919/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2257
                },
                {
                "CIDADE": "MARACANAÚ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SERGIO DIAS DA  PAZ",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14031/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2258
                },
                {
                "CIDADE": "MARANGUAPE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ALEXANDRE CABRAL FREIRE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14123/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2259
                },
                {
                "CIDADE": "MARCO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "IERY OSTERNO RIOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14551",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2260
                },
                {
                "CIDADE": "MARTINÓPOLE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "CARLOS RUBENS MARQUES CUNHA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24870/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2261
                },
                {
                "CIDADE": "MASSAPÊ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LARA DE CASTRO ARRUDA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/47777",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2262
                },
                {
                "CIDADE": "MAURITI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ILDIVAN SANTANA DE SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/21635/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2263
                },
                {
                "CIDADE": "MERUOCA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "AUGUSTO CESAR DOS SANTOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/7911/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2264
                },
                {
                "CIDADE": "MILAGRES",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUCIA MACEDO LANDIM",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51592/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2265
                },
                {
                "CIDADE": "MILHÃ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO WDELANIO PINHEIRO PEIXOTO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9077/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2266
                },
                {
                "CIDADE": "MISSÃO VELHA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "GEORGE SARAIVA JANUARIO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51840",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2267
                },
                {
                "CIDADE": "MOMBAÇA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "NEY WERBSON MOREIRA ALVES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14078/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2268
                },
                {
                "CIDADE": "MONSENHOR TABOSA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOÃO LUCAS GOMES DE ARAÚJO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9013/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2269
                },
                {
                "CIDADE": "MORADA NOVA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSÉ CLEUDO DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/13312/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2270
                },
                {
                "CIDADE": "MORRINHOS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSE ROBERIO FERREIRA DE FREITAS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11391/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2271
                },
                {
                "CIDADE": "MUCAMBO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO GILMAR MARTINS DE SOUZA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14576/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2272
                },
                {
                "CIDADE": "MULUNGU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "GERMANA PEREIRA PORFIRIO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/49533/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2273
                },
                {
                "CIDADE": "NOVA OLINDA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WILLIAM FAGNER ALVES DE MATOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/34083/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2274
                },
                {
                "CIDADE": "NOVA RUSSAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MAYKON LIMA RIBEIRO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/29268/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2275
                },
                {
                "CIDADE": "NOVO ORIENTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "TÚLIO CESAR ALVES SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/14066/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2276
                },
                {
                "CIDADE": "OCARA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIO VAGNER DE LIMA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46045/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2277
                },
                {
                "CIDADE": "ORÓS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSÉ ADAILSON BARBOSA DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/5340/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2278
                },
                {
                "CIDADE": "PACAJUS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "REGINALDO JOSÉ DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/40935/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2279
                },
                {
                "CIDADE": "PACATUBA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ROSTENY CABRAL DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/13673/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2280
                },
                {
                "CIDADE": "PACOTI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LÁZARO SILVEIRA NUNES GOMES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/48255/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2281
                },
                {
                "CIDADE": "PACUJÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIO EVILASIO ALVES OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/28510/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2282
                },
                {
                "CIDADE": "PALHANO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO ORLANDO DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/35763/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2283
                },
                {
                "CIDADE": "PALMÁCIA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIO IPIRANGA FONSECA NETO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9789/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2284
                },
                {
                "CIDADE": "PARACURU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MILIANE BARBOSA DE MOURA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8421/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2285
                },
                {
                "CIDADE": "PARAIPABA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA AURINEIDE BATISTA PIRES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46065",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2286
                },
                {
                "CIDADE": "PARAMOTI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO HERLANDSON SILVA GOMES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/18994/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2287
                },
                {
                "CIDADE": "PEDRA BRANCA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO DIAS DE SOUZA JÚNIOR",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51113",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2288
                },
                {
                "CIDADE": "PENAFORTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "PAULA NASCIMENTO DA CRUZ DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45438",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2289
                },
                {
                "CIDADE": "PENTECOSTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "DEBORAH PONTES ARAUJO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/10706/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2290
                },
                {
                "CIDADE": "PEREIRO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO LEUDIVAN ALVES PEIXOTO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45607",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2291
                },
                {
                "CIDADE": "PINDORETAMA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "HERBESON SALES CASSIANO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/30831/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2292
                },
                {
                "CIDADE": "PIQUET CARNEIRO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JULIANA PATRÍCIA PINTO LOPES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9754/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2293
                },
                {
                "CIDADE": "PORANGA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ELMO ALMEIDA NUNES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/35441/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2294
                },
                {
                "CIDADE": "PORTEIRAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "TICIANO LINARD DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/15769/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2295
                },
                {
                "CIDADE": "POTENGI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARCOS AURÉLIO RODRIGUES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/9291/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2296
                },
                {
                "CIDADE": "POTIRETAMA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "HEMANUELLY DANTAS DE ALMEIDA",
                "PERFIL": "",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal nem do responsável pela inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2297
                },
                {
                "CIDADE": "QUITERIANÓPOLIS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "EPAMINONDAS BEZERRA DA SILVA SOBRINHO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45998/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2298
                },
                {
                "CIDADE": "QUIXADÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCA IRIS ALVES DE FREITAS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/41402/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2299
                },
                {
                "CIDADE": "QUIXELÔ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "AILTON FERNANDES DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24452/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2300
                },
                {
                "CIDADE": "QUIXERAMOBIM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "RAFAELA DA SILVA MENDES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/49783/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2301
                },
                {
                "CIDADE": "QUIXERÉ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANA PATRICIA RODRIGUES DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42809/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2302
                },
                {
                "CIDADE": "REDENÇÃO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "REGIS MANOEL RODRIGUES DE ANDRADE",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/44700/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2303
                },
                {
                "CIDADE": "RUSSAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "WELLISON FELIPE DA SILVA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42062/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2304
                },
                {
                "CIDADE": "SALITRE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "EMERSON ELIAS FERREIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45170/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2305
                },
                {
                "CIDADE": "SANTA QUITÉRIA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANA PAULA DUARTE CAMPOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/48199/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2306
                },
                {
                "CIDADE": "SANTANA DO ACARAÚ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCO WISLEY DE SOUZA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45848/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2307
                },
                {
                "CIDADE": "SANTANA DO CARIRI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LILIANE FEITOSA DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/44639",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2308
                },
                {
                "CIDADE": "SÃO BENEDITO",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA JANE KEILY DE SOUZA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/51581/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2309
                },
                {
                "CIDADE": "SÃO GONÇALO DO AMARANTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "FRANCISCA JULIANA SOUSA ALCANTARA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/46498",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2310
                },
                {
                "CIDADE": "SÃO JOÃO DO JAGUARIBE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "BENEDITA SUELI DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/11038/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2311
                },
                {
                "CIDADE": "SÃO LUÍS DO CURU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "JOSÉ CLAYSON DA SILVA VIANA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/13471/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2312
                },
                {
                "CIDADE": "SENADOR POMPEU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "KLEBER PINHEIRO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/28805/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2313
                },
                {
                "CIDADE": "SENADOR SÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "MARIA DO LIVRAMENTO RAÚJO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45942/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2314
                },
                {
                "CIDADE": "SOBRAL",
                "COORDENADOR DO CADASTRO MUNICIPAL": "EUGÊNIO PARCELI SAMPAIO SILVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/52012/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2315
                },
                {
                "CIDADE": "SOLONÓPOLE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUIS CLAUDIO MACIEL",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8371/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2316
                },
                {
                "CIDADE": "TABULEIRO DO NORTE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SAMUEL MOREIRA CHAVES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/8518/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2317
                },
                {
                "CIDADE": "TAMBORIL",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ARNUEDO CESAR BEZERRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45071/",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2318
                },
                {
                "CIDADE": "TARRAFAS",
                "COORDENADOR DO CADASTRO MUNICIPAL": "GERMÁ MARTINS DOS SANTOS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/10052",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2319
                },
                {
                "CIDADE": "TAUÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "CLEUDIA HENRIQUE DE LIMA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/50875/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2320
                },
                {
                "CIDADE": "TEJUÇUOCA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ROSANGELA RIBEIRO PAIXÃO GÓIS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24758/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2321
                },
                {
                "CIDADE": "TIANGUÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANDERSON LEITE LIMA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42361/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2322
                },
                {
                "CIDADE": "TRAIRI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "KARINE SOUSA COSTA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24661/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2323
                },
                {
                "CIDADE": "TURURU",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ADAILDO CAETANO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/21964/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2324
                },
                {
                "CIDADE": "UBAJARA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "AGILDO DE SOUSA SIQUEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/12442/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2325
                },
                {
                "CIDADE": "UMARI",
                "COORDENADOR DO CADASTRO MUNICIPAL": "DIOCÉLIA GRANGEIRO BEZERRA LUCAS",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24879",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2326
                },
                {
                "CIDADE": "UMIRIM",
                "COORDENADOR DO CADASTRO MUNICIPAL": "SAMANTA NAGITA PINTO CASTRO ALVES",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/45844",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2327
                },
                {
                "CIDADE": "URUBURETAMA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "GEORGE LUIZ FREITAS BARROSO",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/7252/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2328
                },
                {
                "CIDADE": "URUOCA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "INGRED ROCHA DE LIMA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/42531/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2329
                },
                {
                "CIDADE": "VARJOTA",
                "COORDENADOR DO CADASTRO MUNICIPAL": "LUIZ MAILSON PAIVA SOUSA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/24496",
                "CHECK": "sim perfil",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2330
                },
                {
                "CIDADE": "VÁRZEA ALEGRE",
                "COORDENADOR DO CADASTRO MUNICIPAL": "ANTONIA PEREIRA DE OLIVEIRA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/41871/",
                "CHECK": "sim",
                "OBSERVAÇÕES": "",
                "LINK_DO_EDITAL_NO_MAPAS": 2331
                },
                {
                "CIDADE": "VIÇOSA DO CEARÁ",
                "COORDENADOR DO CADASTRO MUNICIPAL": "DANIELA RUFINO DA CUNHA",
                "PERFIL": "https://mapacultural.secult.ce.gov.br/agente/43637",
                "CHECK": "não",
                "OBSERVAÇÕES": "Não foi informado o link do perfil do mapa cultural do coordenador do cadastro municipal, por isso, foi considerado condedido o acesso para o usuário que realizou a inscrição.",
                "LINK_DO_EDITAL_NO_MAPAS": 2332
                }
            ]';

            $decodedCities = json_decode($aldirblancInciso2Cities);

            foreach ($decodedCities as $value) {
                $owner = $app->repo('Opportunity')->find($value->LINK_DO_EDITAL_NO_MAPAS);
                $agentId = trim($value->PERFIL, "/");
                $agentId = explode('/', $agentId);
                $agentId = $agentId[count($agentId) - 1];

                if ($agentId) {
                    $agent = $app->repo('Agent')->find($agentId);
                    $relation = $owner->createAgentRelation($agent, 'group-admin', true, false);
                    $relation->save(true);
                }
            }

            $app->enableAccessControl();
        });

        $app->hook('template(site.index.home-search):begin', function () use ($app) {
            return;
            /*
            $titulo = "AUXÍLIO FINANCEIRO AOS PROFISSIONAIS DO SETOR DE EVENTOS";
            $titulo_url =  $app->createUrl('opportunity','single', [2852]);
            $texto = "Ação do Governo do Estado do Ceará que tem por objetivo conceder aos trabalhadores e trabalhadoras do setor de eventos um auxílio financeiro. Faz parte de um pacote de ações para socorrer o setor de eventos no Estado em meio à pandemia da Covid-19. <br/><br/> O auxílio será pago em duas parcelas de R$ 500, mediante cadastro dos profissionais junto à Secretaria da Cultura do Estado, através do Mapa Cultural do Ceará. Cerca de 10 mil profissionais, como músicos, humoristas e técnicos de som, deverão ser beneficiados. Ao todo R$ 10 milhões serão investidos pelo Estado para transferência dessa renda. Estão inclusos músicos, humoristas, profissionais de circo, técnicos de som, luz e imagem, montadores de palcos, etc.";
            $botao = "Solicite seu auxílio";
            $botao_url = $app->createUrl('opportunity','single', [2852]);

            $this->part('auxilioeventos/home-search', ['texto' => $texto, 'botao' => $botao, 'titulo' => $titulo, 'titulo_url'=> $titulo_url, 'botao_url' => $botao_url]);
            */
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

        
        $app->hook('<<GET|POST>>(registration.remove)', function () use ($app) {

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
        });

        //HOOK PARA FORÇAR A INCLUSAO DE PERFIL INDIVIDUAL E COLETIVO
        $app->hook('template(agent.edit.type):before', function () use ($app) {
            $entity = $this->controller->requestedEntity;
            $app->view->enqueueScript('app', 'edit-type', 'js/agents/edit-agent.js');   
            $this->part('singles/agents/type', ['entity' => $entity]);
        });

        $app->hook('template(opportunity.<<create|edit|single>>.registration-list-header):end', function () use ($app) {
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
        });

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

        $app->hook("POST(aldirblanc.status)", function () use ($app) {
            $this->requireAuthentication();
            $app = App::i();

            $type_bank_account = $app->repo('RegistrationMeta')->findOneBy([
                'key' => 'field_6494',
                'owner' => $this->urlData['registration_id']
            ]); // Tipo de conta bancária

            $bank = $app->repo('RegistrationMeta')->findOneBy([
                'key' => 'field_6469',
                'owner' => $this->urlData['registration_id']
            ]);

            $agency = $app->repo('RegistrationMeta')->findOneBy([
                'key' => 'field_6468',
                'owner' => $this->urlData['registration_id']
            ]);

            $account = $app->repo('RegistrationMeta')->findOneBy([
                'key' => 'field_6464',
                'owner' => $this->urlData['registration_id']
            ]);

            $bank->value = $this->postData['banks'];
            $type_bank_account->value = $this->postData['type_bank_accounts'];
            $agency->value = $this->postData["agency_digit"]
                ? $this->postData["agency_number"] . "-" . $this->postData["agency_digit"]
                : $this->postData["agency_number"];
            $account->value = $this->postData["account_digit"]
                ? $this->postData["account_number"] . "-" . $this->postData["account_digit"]
                : $this->postData["account_number"];

            $app->em->flush();

            $app->redirect($this->createUrl('status', [$this->urlData['registration_id']]));
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

        $app->hook('template(aldirblanc.status.reason-failure):begin', function ($params) use ($app) {

            $evaluations = $app->repo('RegistrationEvaluation')->findByRegistrationAndUsersAndStatus($params['entity']);
            $params['evaluations'] = $evaluations;

            $this->part('reason-failure', $params);
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

        $app->hook('template(opportunity.edit.registration-config):after', function () use ($app) {
            $app->view->enqueueScript(
                'app',
                'prevent-remove-evaluator',
                'js/opportunity-ceara/prevent-remove-evaluator.js'
            );
        });

        $app->hook('entity(<<Agent|Event|Project|Seal|Space>>).validations', function (&$properties_validations) use ($app) {
            unset($properties_validations['shortDescription']['v::stringType()->length(0,400)']);
            $properties_validations['shortDescription']['v::stringType()->length(0,900)'] = 'A descrição curta deve ter no máximo 900 caracteres';
        });

        $app->hook('template(opportunity.single.header-inscritos):actions', function () use ($app) {
            if ($app->user->is('superAdmin')) {
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
        $app->registerController('quantidadeCampos', \Ceara\Controllers\Opportunity::class);

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
