<?php

require_once 'libs/php-jwt/JWT.php';
require_once 'libs/php-jwt/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const JWT_SECRET = '7fK9xQ2mP8vL4sA1zR6nD3wT5yH8uJ0cB9eF2gK7mN4pQ1xZ';

function generateJWT($user) {

    $payload = [
        'id_user' => $user['id_user'],
        'user_name' => $user['user_name'],
        'iat' => time(),
        'exp' => time() + (60 * 60 * 8)
    ];

    return JWT::encode(
        $payload,
        JWT_SECRET,
        'HS256'
    );
}

function validateJWT($token) {

    return JWT::decode(
        $token,
        new Key(JWT_SECRET, 'HS256')
    );
}