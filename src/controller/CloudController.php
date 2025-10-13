<?php

require 'Controller.php';

class CloudController extends Controller {
    public static function index() {
        require PATH_VIEW . 'cloud/index.php';

        if (!empty($_POST)) {
            echo 'teste';
        }
    }
}

CloudController::index();