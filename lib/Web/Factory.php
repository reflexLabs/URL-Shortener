<?php

namespace Library\Web;

class Factory {
    private static $core;
    private static $config;

    public static function createController($parameters, $core, $config) {
        self::$config = $config;
        self::$core = $core;
        extract($parameters);

        // create an instance of child controller
        $c = new $controller();

        // even if not neede, assign a view instance
        $c->view = new View();
        $c->setCore($core);

        // call action
        $c->{$action}($params);
    }

    public static function createModel($model) {
        $m = new $model();
        
        // assign db connection instances
        $m->setCore(self::$core);
        return $m;
    }
}