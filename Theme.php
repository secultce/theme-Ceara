<?php
namespace Ceara;

use MapasCulturais\Themes\BaseV1;
use MapasCulturais\App;

class Theme extends BaseV1\Theme
{

    protected static function _getTexts()
    {
        $self = App::i()->view;
        $url_search_agents = $self->searchAgentsUrl;
        $url_search_spaces = $self->searchSpacesUrl;
        $url_search_events = $self->searchEventsUrl;
        $url_search_projects = $self->searchProjectsUrl;
        
        return array(
            'site: in the region' => 'no Estado do Ceará',
            'site: of the region' => 'do Estado do Ceará',
            'site: owner' => 'Secretaria da Cultura do Estado do Ceará',
            'site: by the site owner' => 'pela Secretaria da Cultura do Estado do Ceará',
            
            'home: abbreviation' => "SECULT",
            // 'home: colabore' => "Colabore com o Mapas Culturais",
            'home: welcome' => "O Mapa Cultural do Ceará é a plataforma livre, gratuita e colaborativa de mapeamento da Secretaria da Cultura do Estado do Ceará sobre cenário cultural cearense. Ficou mais fácil se programar para conhecer as opções culturais que as cidades cearenses oferecem: shows musicais, espetáculos teatrais, sessões de cinema, saraus, entre outras. Além de conferir a agenda de eventos, você também pode colaborar na gestão da cultura do estado: basta criar seu perfil de <a href=\"$url_search_agents\" >agente cultural</a>. A partir deste cadastro, fica mais fácil participar dos editais e programas da Secretaria e também divulgar seus <a href=\"{$url_search_events}\">eventos</a>, <a href=\"{$url_search_spaces}\">espaços</a> ou <a href=\"$url_search_projects\">projetos</a>."
            // 'home: events' => "Você pode pesquisar eventos culturais nos campos de busca combinada. Como usuário cadastrado, você pode incluir seus eventos na plataforma e divulgá-los gratuitamente.",
            // 'home: agents' => "Você pode colaborar na gestão da cultura com suas próprias informações, preenchendo seu perfil de agente cultural. Neste espaço, estão registrados artistas, gestores e produtores; uma rede de atores envolvidos na cena cultural paulistana. Você pode cadastrar um ou mais agentes (grupos, coletivos, bandas instituições, empresas, etc.), além de associar ao seu perfil eventos e espaços culturais com divulgação gratuita.",
            // 'home: spaces' => "Procure por espaços culturais incluídos na plataforma, acessando os campos de busca combinada que ajudam na precisão de sua pesquisa. Cadastre também os espaços onde desenvolve suas atividades artísticas e culturais.",
            // 'home: projects' => "Reúne projetos culturais ou agrupa eventos de todos os tipos. Neste espaço, você encontra leis de fomento, mostras, convocatórias e editais criados, além de diversas iniciativas cadastradas pelos usuários da plataforma. Cadastre-se e divulgue seus projetos.",
            // 'home: home_devs' => 'Existem algumas maneiras de desenvolvedores interagirem com o Mapas Culturais. A primeira é através da nossa <a href="https://github.com/hacklabr/mapasculturais/blob/master/documentation/docs/mc_config_api.md" target="_blank">API</a>. Com ela você pode acessar os dados públicos no nosso banco de dados e utilizá-los para desenvolver aplicações externas. Além disso, o Mapas Culturais é construído a partir do sofware livre <a href="http://institutotim.org.br/project/mapas-culturais/" target="_blank">Mapas Culturais</a>, criado em parceria com o <a href="http://institutotim.org.br" target="_blank">Instituto TIM</a>, e você pode contribuir para o seu desenvolvimento através do <a href="https://github.com/hacklabr/mapasculturais/" target="_blank">GitHub</a>.',
            //
            // 'search: verified results' => 'Resultados Verificados',
            // 'search: verified' => "Verificados"
        );
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
        
        /* Adicionando novos campos na entidade agente */
        $app->hook('template(agent.<<create|single|edit>>.tab-about-service):begin', function () use ($app) {
            $entity = $this->controller->requestedEntity;
            if ($this->isEditable()) :
                echo '<p class="privado">
                <span class="icon icon-private-info"></span>
                <span class="label">Login:</span>
                <span>' . $entity->user->email . '</span>
              </p>';
            endif;
            
        });
        
        $app->hook('template(agent.<<create|single|edit>>.tab-about-service):after', function () {
            $this->part('news-fields-agent', [
                'entity' => $this->data->entity
            ]);
        });
        
        /* Adicionando novos campos na entidade entity revision agent */
        $app->hook('template(entityrevision.history.tab-about-service):after', function () {
            $this->part('news-fields-agent-revision', [
                'entityRevision' => $this->data->entityRevision
            ]);
        });
    }

    /**
     *
     * {@inheritdoc}
     * @see \MapasCulturais\Themes\BaseV1\Theme::register()
     */
    function register()
    {
        parent::register();
        
        /* Adicionando novas áreas de atuação */
        $taxonomy = App::i()->getRegisteredTaxonomyBySlug('area');
        $novasAreasAtuacao = array_merge($taxonomy->restrictedTerms, [
            "humor" => "Humor"
        ]);
        ksort($novasAreasAtuacao);
        $taxonomy->restrictedTerms = $novasAreasAtuacao;
        
        /* Adicionando novos "AgentMetadata" para os campos da entidade agente */
        $this->registerAgentMetadata('escolaridade', [
            'private' => true,
            'label' => \MapasCulturais\i::__('Escolaridade'),
            'type' => 'select',
            'options' => [
                'Nenhuma' => \MapasCulturais\i::__('Nenhuma'),
                'Fundamental' => \MapasCulturais\i::__('Ensino Fundamental'),
                'Fundamental Incompleto' => \MapasCulturais\i::__('Ensino Fundamental Incompleto'),
                'Medio' => \MapasCulturais\i::__('Ensino Medio'),
                'Medio Incompleto' => \MapasCulturais\i::__('Ensino Medio Incompleto'),
                'Superior' => \MapasCulturais\i::__('Ensino Superior'),
                'Superior Incompleto' => \MapasCulturais\i::__('Ensino Superior Incompleto'),
                'Especializacao' => \MapasCulturais\i::__('Especializacao'),
                'Especializacao Incompleta' => \MapasCulturais\i::__('Especializacao Incompleta'),
                'mestrado' => \MapasCulturais\i::__('Mestrado'),
                'Mestrado Incompleto' => \MapasCulturais\i::__('Mestrado Incompleto'),
                'Doutorado' => \MapasCulturais\i::__('Doutorado'),
                'Doutorado Incompleto' => \MapasCulturais\i::__('Doutorado Incompleto')
            ]
        ]);
    }
}
