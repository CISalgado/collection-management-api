<?php

require_once './config/database.php';

class ClientService {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }
    
    public function get($data) {

        $query = "
            SELECT
                id_client,
                client_firstname,
                client_lastname1,
                client_lastname2,
                client_collectionmethod,
                client_collectionday
            FROM ca_clients
            WHERE status = 1
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $data) {

        $query = "
            SELECT
                id_client,
                client_firstname,
                client_lastname1,
                client_lastname2,
                client_collectionmethod,
                client_collectionday
            FROM ca_clients
            WHERE status = 1
            AND id_client = :id_client
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_client', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {

        $query = "
            INSERT INTO ca_clients (
                client_firstname,
                client_lastname1,
                client_lastname2,
                client_collectionmethod,
                client_collectionday,
                status,
                create_iduser,
                create_date
            )
            VALUES (
                :firstname,
                :lastname1,
                :lastname2,
                :collectionmethod,
                :collectionday,
                1,
                :create_iduser,
                NOW()
            )
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':firstname', $data->client_firstname);
        $stmt->bindParam(':lastname1', $data->client_lastname1);
        $stmt->bindParam(':lastname2', $data->client_lastname2);
        $stmt->bindParam(':collectionmethod', $data->client_collectionmethod);
        $stmt->bindParam(':collectionday', $data->client_collectionday);
        $stmt->bindParam(':create_iduser', $data->create_iduser);

        $stmt->execute();

        return [
            'id_client' => $this->conn->lastInsertId()
        ];
    }

    public function update($id, $data) {

        $checkQuery = "
            SELECT id_client
            FROM ca_clients
            WHERE id_client = :id_client
            LIMIT 1
        ";
        
        $checkStmt = $this->conn->prepare($checkQuery);
        
        $checkStmt->bindParam(':id_client', $id);
        
        $checkStmt->execute();
        
        if($checkStmt->rowCount() <= 0) {
            throw new Exception('Cliente no encontrado');
        }
    
        $query = "
            UPDATE ca_clients
            SET
                user_firstname = :firstname,
                user_lastname1 = :lastname1,
                user_lastname2 = :lastname2,
                user_name = :username,
                update_iduser = :update_iduser,
                update_date = NOW()
        ";

        $query .= "
            WHERE id_client = :id_client
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':firstname', $data->user_firstname);
        $stmt->bindParam(':lastname1', $data->user_lastname1);
        $stmt->bindParam(':lastname2', $data->user_lastname2);
        $stmt->bindParam(':username', $data->user_name);
        $stmt->bindParam(':update_iduser', $data->update_iduser);
        $stmt->bindParam(':id_client', $id);

        $stmt->execute();

        return [
            'id_client' => $id
        ];
    }
}