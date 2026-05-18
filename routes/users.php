<?php

require_once './controllers/UserController.php';

$controller = new UserController();

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
        if(!isset($id)) {
            response(false, 'ID requerido', null, 400);
        }
        $controller->update($id);
        break;

    default:
        response(false, 'Método no permitido', null, 405);
}