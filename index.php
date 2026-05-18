<?php

header("Content-Type: application/json");

require_once './helpers/response.php';

$url = $_GET['url'] ?? '';

$url = explode('/', trim($url, '/'));

$route = $url[0] ?? null;
$id = null;
$action = null;

if(isset($url[1])) {
    if(is_numeric($url[1])) {
        $id = $url[1];
        $action = $url[2] ?? null;
    } else {
        $action = $url[1];
    }
}

switch($route) {

    case 'auth':
        require './routes/auth.php';
        break;

    case 'clients':
        require './routes/clients.php';
        break;

    case 'debts':
        require './routes/debts.php';
        break;

    case 'payments':
        require './routes/payments.php';
        break;

    case 'dashboard':
        require './routes/dashboard.php';
        break;
    
    case 'users':
        require './routes/users.php';
        break;

    default:
        response(false, 'Ruta no encontrada', null, 404);
}