<?php

require_once './services/DebtService.php';
require_once './helpers/response.php';

class DebtController {

    private $service;

    public function __construct() {
        $this->service = new DebtService();
    }

    public function get() {

        try {

            $data = json_decode(file_get_contents("php://input"));

            $result = $this->service->get($data);

            response(true, 'Deudas encontradas', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function getById($id) {

        try {

            $data = json_decode(file_get_contents("php://input"));
            
            $result = $this->service->getById($id, $data);

            response(true, 'Deuda encontrada', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function create() {

        try {

            $data = json_decode(file_get_contents("php://input"));

            $result = $this->service->create($data);

            response(true, 'Deuda creada', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function update($id) {
    
        try {
    
            $data = json_decode(file_get_contents("php://input"));
    
            $result = $this->service->update($id, $data);
    
            response(true, 'Deuda actualizada', $result);
    
        } catch(Exception $e) {
    
            response(false, $e->getMessage(), null, 500);
    
        }
    }
}