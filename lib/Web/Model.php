<?php

namespace Library\Web;

abstract class Model /* extends Controller  */{
    private $core;
 
    public function getCore(){
        return $this->core;
    }

    public function setCore($core){
        $this->core = $core;
    }
}