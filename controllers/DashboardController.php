<?php

require_once './services/DashboardService.php';
require_once './helpers/response.php';

class DashboardController {

    private $service;

    public function __construct() {

        $this->service = new DashboardService();
    }

    public function getDebtors() {

        try {

            $result = $this->service->getDebtors();

            response(true, 'Deudores encontrados', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }

    public function getTodaysPayments() {

        try {

            $result = $this->service->getTodaysPayments();

            response(true, 'Cobros del día encontrados', $result);

        } catch(Exception $e) {

            response(false, $e->getMessage(), null, 500);

        }
    }
}