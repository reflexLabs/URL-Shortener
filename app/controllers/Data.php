<?php

namespace Controllers;

class Data extends Controller {

    function get($data){
        switch($data['action']){
            case "count":
                print_r(json_encode($this->getURLs()->getClicksByURL($data->url)));
                break;
            case 'default':
                return null;
                break;
        }
    }

    function post($action){
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        switch($action['action']){
            case "generate":
                $result = $this->getURLs()->generateURL($data->url);
                print_r(json_encode($result));
                break;
            case "count":
                $result = $this->getURLs()->getClicks($data->url);
                print_r(json_encode($result));
                break;    
            case 'default':
                return null;
                break;
        }
    }

}