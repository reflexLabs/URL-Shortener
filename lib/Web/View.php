<?php

namespace Library\Web;

class View {
    public function load($view, $data = []) {
        require_once 'app/views/' . $view . '.php';
    }
}