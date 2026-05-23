<?php

require_once './controllers/DashboardController.php';
require_once './middlewares/authMiddleware.php';
authenticate();

$controller = new DashboardController();

switch($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if($action === 'debtors') {
            $controller->getDebtors();
        } else if($action === 'payments') {
            $controller->getTodaysPayments();
        } else {
           response(false, 'Acción no permitida', null, 405);
        }
        break; 

    default:
        response(false, 'Método no permitido', null, 405);
}