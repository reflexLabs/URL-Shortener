<?php
require_once __DIR__ . '/vendor/autoload.php';

use Library\Core\ {Config, Core, Database, ErrorHandler, Queries};
use Library\Misc\ {Cookie, Hash, Output, Redirect, Response, Session, URL, Validate};
use Library\Web\ {Factory, Routes, Controller, Model, View};

session_start();

try {
    require_once __DIR__ . '/app/config/config.php';
   
    $_db = Database::getInstance();

    Routes::process(Config::get('production'), $_db);
} catch (Exception $e) {
    if (Config::get('production'))
        error_log($e->getMessage());
    else
        echo $e->getMessage();
}
