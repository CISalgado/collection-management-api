<?php

require_once './services/PaymentService.php';
require_once './helpers/response.php';

class PaymentController {

    private $service;

    public function __construct() {
        $this->service = new PaymentService();
    }

    public function get() {

        try {

            $data = json_decode(file_get_contents("php://input"));

            $result = $this->service->get($data);

            response(true, 'Pagos encontrados', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function getById($id) {

        try {

            $data = json_decode(file_get_contents("php://input"));
            
            $result = $this->service->getById($id, $data);

            response(true, 'Pago encontrado', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function create() {

        try {

            $data = json_decode(file_get_contents("php://input"));

            $result = $this->service->create($data);

            response(true, 'Pago creado', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function update($id) {
    
        try {
    
            $data = json_decode(file_get_contents("php://input"));
    
            $result = $this->service->update($id, $data);
    
            response(true, 'Pago actualizado', $result);
    
        } catch(Exception $e) {
    
            response(false, $e->getMessage(), null, 500);
    
        }
    }
}