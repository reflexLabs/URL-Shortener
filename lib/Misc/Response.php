<?php

namespace Library\Misc;

class Response {
    private $msg ="";
    private $state = false;
    private $error;

    function render(){
        $arr [] = array(
            "msg" => $this->getMsg(),
            "state" => $this->getState(),
            "error" => $this->getError()
        );
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    function getMsg(){
        return $this->msg;
    }

    function getState(){
        return $this->state;
    }

    function getError(){
        return $this->error;
    }

    function setMsg($msg){
        $this->msg = $msg;
    }

    function setState($state){
        $this->state = $state;
    }

    function setError($error){
        $this->error = $error;
    }
}
