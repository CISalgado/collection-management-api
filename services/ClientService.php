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
                client_phone,
                client_direction,
                CASE 
                    WHEN client_collectionmethod = 1 THEN 'Semanal'
                    WHEN client_collectionmethod = 2 THEN 'Quincenal'
                    WHEN client_collectionmethod = 3 THEN 'Mensual'
                    ELSE 'Otro'
                END AS client_collectionmethod,
                CASE
                    WHEN  client_collectionmethod = 1 THEN 
                        CASE 
                            WHEN client_collectionday = 1 THEN 'Lunes'
                            WHEN client_collectionday = 2 THEN 'Martes'
                            WHEN client_collectionday = 3 THEN 'Miércoles'
                            WHEN client_collectionday = 4 THEN 'Jueves'
                            WHEN client_collectionday = 5 THEN 'Viernes'
                            WHEN client_collectionday = 6 THEN 'Sábado'
                            WHEN client_collectionday = 7 THEN 'Domingo'
                            ELSE 'Otro'
                        END
                    ELSE client_collectionday
                END AS client_collectionday
            FROM ca_client
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
                client_phone,
                client_direction,
                CASE 
                    WHEN client_collectionmethod = 1 THEN 'Semanal'
                    WHEN client_collectionmethod = 2 THEN 'Quincenal'
                    WHEN client_collectionmethod = 3 THEN 'Mensual'
                    ELSE 'Otro'
                END AS client_collectionmethod,
                CASE
                    WHEN  client_collectionmethod = 1 THEN 
                        CASE 
                            WHEN client_collectionday = 1 THEN 'Lunes'
                            WHEN client_collectionday = 2 THEN 'Martes'
                            WHEN client_collectionday = 3 THEN 'Miércoles'
                            WHEN client_collectionday = 4 THEN 'Jueves'
                            WHEN client_collectionday = 5 THEN 'Viernes'
                            WHEN client_collectionday = 6 THEN 'Sábado'
                            WHEN client_collectionday = 7 THEN 'Domingo'
                            ELSE 'Otro'
                        END
                    ELSE client_collectionday
                END AS client_collectionday
            FROM ca_client
            WHERE status = 1
            AND id_client = :id_client
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_client', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function accountStatement($id, $data) {
    
        $query = "
            CALL sp_estado_cuenta_cliente(:id_client);
        ";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':id_client', $id);
    
        $stmt->execute();
    
        $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $stmt->nextRowset();
    
        $balance = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return [
            'movements' => $movements,
            'balance' => $balance['saldo']
        ];
    }

    public function create($data) {

        $query = "
            INSERT INTO ca_client (
                client_firstname,
                client_lastname1,
                client_lastname2,
                client_collectionmethod,
                client_collectionday,
                client_phone,
                client_direction,
                status,
                create_iduser,
                create_date
            )
            VALUES (
                :client_firstname,
                :client_lastname1,
                :client_lastname2,
                :client_collectionmethod,
                :client_collectionday,
                :client_phone,
                :client_direction,
                1,
                :create_iduser,
                NOW()
            )
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':client_firstname', $data->client_firstname);
        $stmt->bindParam(':client_lastname1', $data->client_lastname1);
        $stmt->bindParam(':client_lastname2', $data->client_lastname2);
        $stmt->bindParam(':client_collectionmethod', $data->client_collectionmethod);
        $stmt->bindParam(':client_collectionday', $data->client_collectionday);

        // Campos opcionales
        $phone = $data->client_phone ?? null;
        $direction = $data->client_direction ?? null;

        $stmt->bindParam(':client_phone', $phone);
        $stmt->bindParam(':client_direction', $direction);

        $stmt->bindParam(':create_iduser', $data->create_iduser);

        $stmt->execute();

        return [
            'id_client' => $this->conn->lastInsertId()
        ];
    }

    public function update($id, $data) {

        $checkQuery = "
            SELECT id_client
            FROM ca_client
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
            UPDATE ca_client
            SET
                client_firstname = :client_firstname,
                client_lastname1 = :client_lastname1,
                client_lastname2 = :client_lastname2,
                client_phone = :client_phone,
                client_direction = :client_direction,
                client_collectionmethod = :client_collectionmethod,
                client_collectionday = :client_collectionday,
                update_iduser = :update_iduser,
                update_date = NOW()
        ";

        $query .= "
            WHERE id_client = :id_client
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':client_firstname', $data->client_firstname);
        $stmt->bindParam(':client_lastname1', $data->client_lastname1);
        $stmt->bindParam(':client_lastname2', $data->client_lastname2);
        $stmt->bindParam(':client_collectionmethod', $data->client_collectionmethod);
        $stmt->bindParam(':client_collectionday', $data->client_collectionday);
        $stmt->bindParam(':update_iduser', $data->update_iduser);
        $stmt->bindParam(':id_client', $id);

        // Campos opcionales
        $phone = $data->client_phone ?? null;
        $direction = $data->client_direction ?? null;

        $stmt->bindParam(':client_phone', $phone);
        $stmt->bindParam(':client_direction', $direction);

        $stmt->execute();

        return [
            'id_client' => $id
        ];
    }
}