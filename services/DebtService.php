<?php

require_once './config/database.php';

class DebtService {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }
    
    public function get($data) {

        $query = "
            SELECT
                dd.id_dedebt,
                dd.id_client,
                dd.debt_amount,
                dd.debt_date,
                dd.debt_description,
                CONCAT(
                    cc.client_firstname,
                    ' ',
                    cc.client_lastname1,
                    ' ',
                    cc.client_lastname2
                ) AS client_name
            FROM de_dedebt dd
            INNER JOIN ca_client cc ON dd.id_client = cc.id_client
            WHERE dd.status = 1
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $data) {

        $query = "
            SELECT
                dd.id_dedebt,
                dd.id_client,
                dd.debt_amount,
                dd.debt_date,
                dd.debt_description,
                CONCAT(
                    cc.client_firstname,
                    ' ',
                    cc.client_lastname1,
                    ' ',
                    cc.client_lastname2
                ) AS client_name
            FROM de_dedebt dd
            INNER JOIN ca_client cc ON dd.id_client = cc.id_client
            WHERE dd.status = 1
            AND dd.id_dedebt = :id_dedebt
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_dedebt', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {

        $query = "
            CALL sp_registrar_deuda(
                :id_client,
                :debt_amount,
                :debt_description,
                :debt_date,
                :id_user
            )
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_client', $data->id_client);
        $stmt->bindParam(':debt_amount', $data->debt_amount);
        $stmt->bindParam(':debt_description', $data->debt_description);
        $stmt->bindParam(':debt_date', $data->debt_date);
        $stmt->bindParam(':id_user', $data->create_iduser);

        $stmt->execute();

        return [
            'success' => true
        ];
    }

    public function update($id, $data) {

        $checkQuery = "
            SELECT id_dedebt
            FROM de_dedebt
            WHERE id_dedebt = :id_dedebt
            LIMIT 1
        ";
        
        $checkStmt = $this->conn->prepare($checkQuery);
        
        $checkStmt->bindParam(':id_dedebt', $id);
        
        $checkStmt->execute();
        
        if($checkStmt->rowCount() <= 0) {
            throw new Exception('Deuda no encontrada');
        }
    
        $query = "
            UPDATE de_dedebt
            SET
                id_client = :id_client,
                debt_amount = :debt_amount,                
                debt_date = :debt_date,
                debt_description = :debt_description,
                update_iduser = :update_iduser,
                update_date = NOW()
        ";

        $query .= "
            WHERE id_dedebt = :id_dedebt
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_dedebt', $id);
        $stmt->bindParam(':id_client', $data->id_client);
        $stmt->bindParam(':debt_amount', $data->debt_amount);
        $stmt->bindParam(':debt_date', $data->debt_date);
        $stmt->bindParam(':debt_description', $data->debt_description);
        $stmt->bindParam(':update_iduser', $data->update_iduser);

        $stmt->execute();

        return [
            'id_dedebt' => $id
        ];
    }
}