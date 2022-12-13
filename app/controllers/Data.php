<?php

namespace Controllers;

class Data extends Controller {

    function get($data){
        switch($data['action']){
            case "plans":
                print_r(json_encode($this->getPlans()->results()));
                break;
            case 'default':
                return null;
                break;
        }
    }

    function getPlans(){
        return $this->getCore()->getPlans();
    }
    
}