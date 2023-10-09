<?php

namespace Ceara\Controllers;

use MapasCulturais\App;

class Evaluations extends \MapasCulturais\Controller
{


    function GET_createEvaluation()
    {
        $app = App::i();
        $app->view->enqueueScript('app', 'evaluations', 'js/evaluations/evaluations.js');
        $this->render('index');
    }
}
