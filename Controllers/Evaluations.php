<?php

namespace Ceara\Controllers;

use DateTime;
use MapasCulturais\App;
use MapasCulturais\Entities\RegistrationEvaluation;

class Evaluations extends \MapasCulturais\Controller
{


    function GET_createEvaluation()
    {
        $app = App::i();
        $app->view->enqueueScript('app', 'evaluations', 'js/evaluations/evaluations.js');
        $app->view->enqueueScript('app', 'pnotify', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.js');
        $app->view->enqueueScript('app', 'pnotify-animate', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.animate.min.js');
        $app->view->enqueueStyle('app', 'pnotify', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.css');
        $app->view->enqueueStyle('app', 'pnotify-theme', 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.brighttheme.min.css');
        $this->render('index');
    }

    function POST_force()
    {
        $this->requireAuthentication();//autenticado
        $app = App::i();
        $msgRegEva = '';
        try {
            // dump($this->data['registration_id']);
            $reg_evaluations = $app->repo('RegistrationEvaluation')->findBy(['registration' => $this->data['registration_id'] ]);
            // dump(empty($reg_evaluations));
            // die;
            if(!empty($reg_evaluations)){
                $msgRegEva = 'Já existe avaliação para essa inscrição.';
                $this->errorJson(['message' => 'Já existe avaliação para essa inscrição.'], 400);
            }
            //Tem que ser sasaAdmin+
            if($app->user->is('saasAdmin') && empty($reg_evaluations)){
                // dump($reg_evaluations);
                // dump($app->user->is('saasAdmin'));
                // $evaluation_reg = $app->repo('RegistrationEvaluation');
                $user = $app->repo('User')->find($this->data['user_id']);
                $registration = $app->repo('Registration')->find($this->data['registration_id']);
                $createTimestamp = new DateTime($this->data['create_timestamp']);
                $updateTimestamp = new DateTime($this->data['update_timestamp']);
                $dataEvaluation = json_decode($this->data['evaluation_data']);
                // dump(gettype((array) $dataEvaluation));
                // die;
                $evaluation = new RegistrationEvaluation;
                $evaluation->registration = $registration;
                $evaluation->user = $user;
                $evaluation->status = $this->data['status'];
                $evaluation->result = $this->data['result'];
                $evaluation->createTimestamp = $createTimestamp;
                $evaluation->updateTimestamp = $updateTimestamp;
                
                // die;
                $evaluation->save(true);
                $posEvaluation = $app->repo('RegistrationEvaluation');
                $eval = $posEvaluation->find($evaluation->id);
                $eval->setEvaluationData( (array) $dataEvaluation );
                $eval->save(true);
                dump($eval);
            }
        } catch (\Exception $th) {
            // dump($th->getMessage());
            // $this->errorJson(['message' => 'Ocorreu um erro inesperado, sempre verifique os campos que são obrigatórios.'], 400);
            if($msgRegEva !== ''){
                $this->errorJson(['message' => $msgRegEva ], 400);
            }
            $this->errorJson(['message' => $th->getMessage()], 400);
        
        }
        
        // dump($this->data);
        // $this->json(['message' => 'success'], 200);
    }

    function GET_loadCsv()
    {
        $app = App::i();
        dump('GET_loadCsv');
        $file = THEMES_PATH.'Ceara/Controllers/csv/avaliacoes.csv';
        dump($file);
        $row = 1;
        $handle = fopen($file, "r");
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $num = count($data);
            echo "<p> $num campos na linha $row: <br /></p>\n";
            // $row++;
            // for ($c=0; $c < $num; $c++) {
            //     echo $data[$c] . "<br />\n";
            // }
            $dataConsolidate = json_encode($data[3]);
            // dump($data);
            $reg = $data[0];
            $user_id = $data[1];
            $result = $data[2];
            // $evalData =  str_replace('\\"', '', $dataConsolidate);
            $evalData = $data[3];
            $status = $data[4];
            $create = date($data[5]);
            $update = $data[6];
            echo $evalData."<br/>";
            // $dtime = DateTime::createFromFormat($data[5]);
          
              
            if($data[6] !== "")
            {
                // $query_insert = "INSERT INTO public.registration_evaluation
                // (id, registration_id, user_id, result, evaluation_data, status, create_timestamp, update_timestamp)
                // VALUES(nextval('registration_evaluation_pkey'), $reg, $user_id, $result, $evalData, $status, $create, $update);";
                // $stmt_file = $app->em->getConnection()->prepare($query_insert);
                // $stmt_file->execute();
            }else{
                $upTimeDate = date($data[6]);
                // $query_insert = "INSERT INTO public.registration_evaluation
                // (id, registration_id, user_id, result, evaluation_data, status, create_timestamp, update_timestamp)
                // VALUES(nextval('registration_evaluation_pkey'), $reg, $user_id, $result, $evalData, $status, $create, $update);";
                // $stmt_file = $app->em->getConnection()->prepare($query_insert);
                // $stmt_file->execute();
            }

        }
    }
}
