<?php

require_once './controllers/ClientController.php';

$controller = new ClientController();

switch($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($id)) {
            $controller->getById($id);
        } else {
            $controller->get();
        }
        break;

    case 'POST':
        $controller->create();
        break;

    case 'PUT':
        $controller->update($id);
        break;

    default:
        response(false, 'Método no permitido', null, 405);
}