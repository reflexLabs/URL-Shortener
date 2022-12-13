<?php

namespace Controllers;

use Library\ {Validate, Response, Session, Redirect};

class System extends Controller {
    private $users;

    function __construct() {
        $this->users = \Library\Factory::createModel('\Models\Users');
    }

    function page($path, $page, $category, $layout, $data){
        $page = [
            'path' => $path,
            'name' => $page,
            'category' => $category,
            'layout' => $layout,
            'data' => $data,
        ];
        return $page;
    }

    function to($page){
        $this->view->load($page['path'], $data = [
            'page' => $page['name'],
            'category' => $page['category'],
            'layout' => $page['layout']
        ]);
    }

    function index(){
        $path = '/pages/Dashboard/index';
        $page = 'Dashboard';
        $category = 'Dashboard';
        $layout = 'index.php';
        $data = [
            'data' => null
        ];
        $_page = $this->page($path, $page, $category, $layout, $data);
        $this->to($_page);
    }
}