<?php
namespace Ceara\Controllers;
use MapasCulturais\App;
class SearchAll extends \MapasCulturais\Controller {

     function GET_all() {
         $app = App::i();

         // $this->requireAutentication();
         // $app->view->enqueueScript('app', 'tabs', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js');
         // $app->view->enqueueStyle('app', 'tabs-jquery', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
         $app->view->enqueueStyle('app', 'tabs-style', 'css/tabs/style.css');
         $this->render("index");
     }

     function GET_index() {
        echo 'teste';
     }


}