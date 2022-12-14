<?php

namespace Library\Web;

class View {
    public function load($view, $data = []) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/app/views/' . $view . '.php';
    }
}