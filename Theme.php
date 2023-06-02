<?php

namespace Ceara;

use MapasCulturais\App;
use MapasCulturais\AssetManager;
use MapasCulturais\Themes\BaseV1;
use MapasCulturais\Entities;

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
        $this->enqueueScript('app', 'analytics', 'js/analytics.js');
        $this->enqueueStyle('app', 'accessibility', 'css/accessibility.css');

        $app->hook('view.render(<<*>>):before', function () use ($app) {
            $this->_publishAssets();
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

        $app->hook('auth.createUser:after', function ($user) use ($app, $theme) {
            $theme->fixAgentPermission($user);
        });

        $app->hook('auth.successful', function () use ($app, $theme) {
            $theme->fixAgentPermission($app->user);
        });
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

        parent::register();

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
               'required' => \MapasCulturais\i::__('O CPF é obrigatório'),
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

        $agents = $app->repo('Agent')->findBy(['user' => $user]);
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
