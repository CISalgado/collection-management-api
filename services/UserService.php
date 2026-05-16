<?php

require_once './config/database.php';

class UserService {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }

    public function get($data) {

        $query = "
            SELECT
                id_user,
                user_firstname,
                user_lastname1,
                user_lastname2,
                user_name
            FROM ca_users
            WHERE status = 1
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $data) {

        $query = "
            SELECT
                id_user,
                user_firstname,
                user_lastname1,
                user_lastname2,
                user_name
            FROM ca_users
            WHERE status = 1
            AND id_user = :id_user
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_user', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {

        $password = password_hash(
            $data->user_password,
            PASSWORD_DEFAULT
        );

        $query = "
            INSERT INTO ca_users (
                user_firstname,
                user_lastname1,
                user_lastname2,
                user_name,
                user_password,
                status,
                create_iduser,
                create_date
            )
            VALUES (
                :firstname,
                :lastname1,
                :lastname2,
                :username,
                :password,
                1,
                :create_iduser,
                NOW()
            )
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':firstname', $data->user_firstname);
        $stmt->bindParam(':lastname1', $data->user_lastname1);
        $stmt->bindParam(':lastname2', $data->user_lastname2);
        $stmt->bindParam(':username', $data->user_name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':create_iduser', $data->create_iduser);

        $stmt->execute();

        return [
            'id_user' => $this->conn->lastInsertId()
        ];
    }

    public function update($id, $data) {

        $checkQuery = "
            SELECT id_user
            FROM ca_users
            WHERE id_user = :id_user
            LIMIT 1
        ";
        
        $checkStmt = $this->conn->prepare($checkQuery);
        
        $checkStmt->bindParam(':id_user', $id);
        
        $checkStmt->execute();
        
        if($checkStmt->rowCount() <= 0) {
            throw new Exception('Usuario no encontrado');
        }
    
        $query = "
            UPDATE ca_users
            SET
                user_firstname = :firstname,
                user_lastname1 = :lastname1,
                user_lastname2 = :lastname2,
                user_name = :username,
                update_iduser = :update_iduser,
                update_date = NOW()
        ";

        // Solo actualizar password si viene
        if(!empty($data->user_password)) {

            $query .= ",
                user_password = :password
            ";
        }

        $query .= "
            WHERE id_user = :id_user
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':firstname', $data->user_firstname);
        $stmt->bindParam(':lastname1', $data->user_lastname1);
        $stmt->bindParam(':lastname2', $data->user_lastname2);
        $stmt->bindParam(':username', $data->user_name);
        $stmt->bindParam(':update_iduser', $data->update_iduser);
        $stmt->bindParam(':id_user', $id);

        if(!empty($data->user_password)) {

            $password = password_hash(
                $data->user_password,
                PASSWORD_DEFAULT
            );

            $stmt->bindParam(':password', $password);
        }

        $stmt->execute();

        return [
            'id_user' => $id
        ];
    }
}