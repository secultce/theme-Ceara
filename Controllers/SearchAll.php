<?php
namespace Ceara\Controllers;
use DateTime;
use MapasCulturais\App;

class SearchAll extends \MapasCulturais\Controller {

     function GET_all() {
         $app = App::i();

         // $this->requireAutentication();
         // $app->view->enqueueScript('app', 'tabs', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js');
         $app->view->enqueueScript('app', 'agent-search', 'js/agents/search/ng.agent-search.js');
         $app->view->enqueueStyle('app', 'tabs-style', 'css/tabs/style.css');
         $this->render("index");
     }

     function GET_index() {
        echo 'teste';
     }

     function POST_searchAgent()
     {
        // dump($this->data);
        $app = App::i();
        if($this->data['type'] == 'email')
        {
            $query = new \MapasCulturais\ApiQuery ('MapasCulturais\Entities\User', ['@select' => 'id', 'email' => 'ILIKE(' . $this->data['value'] . ')']);
            // dump($query); 
            if($user = $query->findOne()){
                $user = $app->repo("User")->findOneBy($user);
            }
            return $this->json(['data' => $user], 200);
        }

        if($this->data['type'] == 'cpf')
        {
           try {           
                $user = $app->repo("AgentMeta")->findBy([
                    'key' => 'cpf', 'value' => $this->data['value']
                ]);
                return $this->json(['data' => $user], 200);
           } catch (\Throwable $th) {
                throw $th;
           }
        }

        if($this->data['type'] == 'dataDeNascimento')
        {
            //Alterando formato da data
            $birthDate = str_replace("/", "-", $this->data['value']);
            try {           
                $user = $app->repo("AgentMeta")->findBy([
                    'key' => 'dataDeNascimento', 'value' => date('Y-m-d', strtotime($birthDate))
                ]);
                return $this->json(['data' => $user], 200);
           } catch (\Throwable $th) {
                throw $th;
           }
        }

        if($this->data['type'] == 'cnpj')
        {
            try {           
                $user = $app->repo("AgentMeta")->findOneBy([
                    'key' => 'cnpj', 'value' => $this->data['value']
                ]);
                return $this->json(['data' => $user], 200);
           } catch (\Throwable $th) {
                throw $th;
           }
        }
            
       
     }


}