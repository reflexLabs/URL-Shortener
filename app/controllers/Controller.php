<?php

namespace Controllers;

use Library\Misc\ {Redirect};

class Controller extends \Library\Web\Controller {
    public $core;
    private $urls;

    function __construct() {
        $this->urls = \Library\Web\Factory::createModel('\Models\URLs');
    }

    public function redirect($path){
        $url = $path['url'];
        $original = $this->urls->getURLByShort($url);
        if(!$original->first()){
            Redirect::to('/');
        }
        $redirect = $original->first()->original;
        $clickStatus = $this->urls->click($url);
        var_dump($clickStatus);
        Redirect::to($redirect);
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

    public function getURLs(){
        return $this->urls;
    }

}