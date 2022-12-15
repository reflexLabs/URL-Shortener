<?php

namespace Controllers;

class PageController extends Controller {

    function index() {
        $this->view->load('/pages/Homepage/index', [

        ]);
    }

    function counter() {
        $this->view->load('/pages/Counter/index', [

        ]);
    }
}