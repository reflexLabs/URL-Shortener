<?php

namespace Controllers;

use Library\Redirect;

class Controller extends \Library\Web\Controller {
    public $core;

    function __construct() { }

    public function redirect($path){
        Redirect::to($path);
    }

    public function homepage(){
        Redirect::to('/index');
        return;
    }

    public function getCore(){
        return $this->core;
    }

    public function setCore($core){
        $this->core = $core;
    }

}