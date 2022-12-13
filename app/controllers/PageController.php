<?php

namespace Controllers;

class PageController extends Controller {

    /* Models */
    
    private $users;

    function __construct() {
        $this->users = \Library\Web\Factory::createModel('\Models\Users');
    }

   
    function index() {
        $this->view->load('/pages/Homepage/index', [
            'user' => null
        ]);
    }
    
    /* function examples(){
        $this->view->load('/pages/Examples/index',['content' => "f"]);
    } */
}