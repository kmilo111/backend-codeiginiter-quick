<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Facturacion extends REST_Controller {

    public function __construct(){
        
       header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
       header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
       header("Access-Control-Allow-Origin: *");
       $method = $_SERVER['REQUEST_METHOD'];
       if ($method == 'OPTIONS') {
           die();
       } 
       parent::__construct();
       $this->load->database();
    }
   
    public function getAllFacturas_get(){
        $this->load->model("api_model");
        $factura = $this->api_model->getAll();
        $this->response($factura);
    }

    public function getFacturaById_get($id){
        $this->load->model("api_model");
        $factura = $this->api_model->getById($id);
        $this->response($factura);
    }

    public function insertFactura_post(){
       $this->load->model("api_model");
       $data = $this->request->body;
       $factura = $this->api_model->insertFactura($data);
       $this->response($factura);         
    }

    public function updateFactura_put($id){
        $this->load->model("api_model");
        $data = $this->request->body;
        $factura = $this->api_model->updateFactura($data, $id);
        $this->response($factura);
    }

    public function deleteFactura_delete($id){
        $this->load->model("api_model");
        $barber = $this->api_model->deleteFactura($id);
        $this->response($barber);
    }
   
}