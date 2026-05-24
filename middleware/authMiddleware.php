<?php
require_once './helpers/response.php';
require_once './helpers/jwt.php';

function authenticate() {

    $headers = getallheaders();

    $authHeader =
        $headers['Authorization']
        ?? $headers['authorization']
        ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? null);

    if(!$authHeader) {

        response(false, 'Token requerido', null, 401);

        exit;
    }

    $token = str_replace(
        'Bearer ',
        '',
        $authHeader
    );

    try {

        $decoded = validateJWT($token);

        return $decoded;

    } catch(Exception $e) {

        response(false, 'Token inválido', null, 401);

        exit;
    }
}