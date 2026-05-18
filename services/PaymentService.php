<?php

require_once './config/database.php';

class PaymentService {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }
    
    public function get($data) {

        $query = "
            SELECT
                dde.id_depayment,
                dde.id_client,
                dde.payment_amount,
                dde.payment_date,
                CONCAT(
                    cc.client_firstname,
                    ' ',
                    cc.client_lastname1,
                    ' ',
                    cc.client_lastname2
                ) AS client_name
            FROM de_depayment dde
            INNER JOIN ca_client cc ON dde.id_client = cc.id_client
            WHERE dde.status = 1
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $data) {

        $query = "
            SELECT
                dde.id_depayment,
                dde.id_client,
                dde.payment_amount,
                dde.payment_date,
                CONCAT(
                    cc.client_firstname,
                    ' ',
                    cc.client_lastname1,
                    ' ',
                    cc.client_lastname2
                ) AS client_name
            FROM de_depayment dde
            INNER JOIN ca_client cc ON dde.id_client = cc.id_client
            WHERE dde.status = 1
            AND dde.id_depayment = :id_depayment
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_depayment', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {

        $query = "
            CALL sp_registrar_pago(
                :id_client,
                :payment_amount,
                :payment_date,
                :id_user
            )
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_client', $data->id_client);
        $stmt->bindParam(':payment_amount', $data->payment_amount);
        $stmt->bindParam(':payment_date', $data->payment_date);
        $stmt->bindParam(':id_user', $data->create_iduser);

        $stmt->execute();

        return [
            'success' => true
        ];
    }

    public function update($id, $data) {

        $checkQuery = "
            SELECT id_depayment
            FROM de_depayment
            WHERE id_depayment = :id_depayment
            LIMIT 1
        ";
        
        $checkStmt = $this->conn->prepare($checkQuery);
        
        $checkStmt->bindParam(':id_depayment', $id);
        
        $checkStmt->execute();
        
        if($checkStmt->rowCount() <= 0) {
            throw new Exception('Deuda no encontrada');
        }
    
        $query = "
            UPDATE de_depayment
            SET
                id_client = :id_client,
                payment_amount = :payment_amount,                
                payment_date = :payment_date,
                update_iduser = :update_iduser,
                update_date = NOW()
        ";

        $query .= "
            WHERE id_depayment = :id_depayment
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_depayment', $id);
        $stmt->bindParam(':id_client', $data->id_client);
        $stmt->bindParam(':payment_amount', $data->payment_amount);
        $stmt->bindParam(':payment_date', $data->payment_date);
        $stmt->bindParam(':update_iduser', $data->update_iduser);

        $stmt->execute();

        return [
            'id_depayment' => $id
        ];
    }
}