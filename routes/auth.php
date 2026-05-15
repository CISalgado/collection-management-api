<?php

require_once './controllers/AuthController.php';

$controller = new AuthController();

switch($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $controller->login();
        break;

    default:
        response(false, 'Método no permitido', null, 405);
}