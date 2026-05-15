<?php

require_once './config/database.php';

class AuthService {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }

    public function login($user_name, $user_password) {

        $query = "
            SELECT
                id_user,
                user_name,
                user_password
            FROM ca_users
            WHERE user_name = :user_name
            AND status = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_name', $user_name);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user) {
            throw new Exception('Usuario no encontrado');
        }

        if(!password_verify($user_password, $user['user_password'])) {
            throw new Exception('Contraseña incorrecta');
        }

        unset($user['user_password']);

        return $user;
    }
}