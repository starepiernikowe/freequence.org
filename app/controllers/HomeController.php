<?php

class HomeController {
    public function index($view_data = []) { // Accept $view_data
        require_once BASE_PATH . 'app/views/home.php';
    }
}