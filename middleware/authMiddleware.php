<?php

require_once './helpers/jwt.php';

function authenticate() {

    $headers = getallheaders();

    if(!isset($headers['Authorization'])) {
        response(false, 'Token requerido', null, 401);
        exit;
    }

    $token = str_replace(
        'Bearer ',
        '',
        $headers['Authorization']
    );

    try {

        $decoded = validateJWT($token);

        return $decoded;

    } catch(Exception $e) {

        response(false, 'Token inválido', null, 401);
        exit;
    }
}