<?php

namespace Ceara\Controllers;

use DateTime;
use MapasCulturais\App;

class SearchAll extends \MapasCulturais\Controller
{

    function GET_all()
    {
        $this->requireAuthentication();
        $app = App::i();
        $app->view->enqueueScript('app', 'agent-search', 'js/agents/search/ng.agent-search.js');
        $app->view->enqueueStyle('app', 'tabs-style', 'css/search/style.css');
        $this->render("index");
    }
    /**
     * Metodo que recebe os paramentros e realiza uma busca no banco e devolvo para front
     *
     * @return void
     */
    function POST_searchAgent()
    {
        $this->requireAuthentication();
        
        $app = App::i();
        if ($this->data['type'] == 'email') {
            //Busca somente por um resultado por que não tem o mesmo email varias vezes
            $row = $app->repo('User')->findOneBy(['email' => $this->data['value']]);
            //Dados para envio
            $result = $this->getReturnData($row);
            return $this->json(['data' => $result], 200);
        }

        if ($this->data['type'] == 'cpf') {
            try {
                //Busca por cpf ou documento em caso de agente em rascunho
                $row = $app->repo("AgentMeta")->findBy([
                    'key' => array('cpf','documento'), 'value' => $this->data['value']
                ]);
                $result = $this->getReturnData($row);
                return $this->json(['data' => $result], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        if ($this->data['type'] == 'dataDeNascimento') {
            //Alterando formato da data
            $birthDate = str_replace("/", "-", $this->data['value']);
            try {
                $row = $app->repo("AgentMeta")->findBy([
                    'key' => 'dataDeNascimento', 'value' => date('Y-m-d', strtotime($birthDate))
                ]);
                $result = $this->getReturnData($row);
             
                return $this->json(['data' => $result], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        if ($this->data['type'] == 'cnpj') {
            try {
                $row = $app->repo("AgentMeta")->findBy([
                    'key' => array('cnpj','documento'), 'value' => $this->data['value']
                ]);
                $result = $this->getReturnData($row);
             
                return $this->json(['data' => $result], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    /**
     * Funcao que retorna uma matriz de array
     *
     * @param [object] $rows
     * @return array
     */
    function getReturnData($rows) {
        $result = [];
        $app = App::i();
        //Para busca com email
        if(gettype($rows) == 'object'){
            $agent = $app->repo('Agent')->find($rows->profile->id);
            $result['id'] = $agent->owner->user->id;
            $result['name'] = $agent->name;
            $result['longDescription'] = $agent->longDescription;
        }
        foreach ($rows as $key => $value) {
            //Buscando instancia de cada agente para montar o array de resultado
            $agent = $app->repo('Agent')->find($value->owner->id);
            //Preenchendo array
            $result[$key]['id'] = $agent->owner->user->id;
            $result[$key]['name'] = $agent->name;
            $result[$key]['longDescription'] = $agent->longDescription;
        }
        return $result;
    }
}
