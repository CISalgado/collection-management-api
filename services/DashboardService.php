<?php

require_once './config/database.php';

class DashboardService {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }
    
    public function getDebtors() {

        $query = "
            CALL sp_clientes_morosos();
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTodaysPayments(){
        $query = "
            call sp_cobros_hoy();
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}