<?php

require_once './services/AuthService.php';
require_once './helpers/response.php';

class AuthController {

    private $service;

    public function __construct() {
        $this->service = new AuthService();
    }

    public function login() {

        $data = json_decode(file_get_contents("php://input"));

        if(
            !isset($data->user_name) ||
            !isset($data->user_password)
        ) {
            response(false, 'Datos incompletos', null, 400);
        }

        $result = $this->service->login(
            $data->user_name,
            $data->user_password
        );

        response(true, 'Login correcto', $result);
    }
}