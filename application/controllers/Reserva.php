<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Reserva extends REST_Controller {

    public function __construct(){
        
       header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
       header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
       header("Access-Control-Allow-Origin: *");
  
       parent::__construct();
       $this->load->database();
    }

   //obtener todos los barberos
    public function all_barberos_get(){
        $query = $this->db->query("SELECT * FROM barbero");
        $respuesta = array('error' => FALSE, 'barberos' => $query->result_array());
        $this->response($respuesta);
    }

    //obtener todas citas la agenda por barbero
    public function obtener_cita_barbero_get($id_barbero){
        $query = $this->db->query("SELECT * FROM `citas` WHERE id_barbero =".$id_barbero);
        $respuesta = array('error' => FALSE, 'barbero' => $query->result_array());
        $this->response($respuesta);
    }

    public function getAllCitas_get(){
        $this->load->model("api_model");
        $barber = $this->api_model->getAll();
        $this->response($barber);
    }

    public function getCitaById_get($id){
        $this->load->model("api_model");
        $barber = $this->api_model->getById($id);
        $this->response($barber);
    }

    public function insertReserva_post(){
       $this->load->model("api_model");
       $data = $this->request->body;
       $barber = $this->api_model->insertCita($data);
       $this->response($barber);         
    }

    public function updateReserva_put($id){
        $this->load->model("api_model");
        $data = $this->request->body;
        $barber = $this->api_model->updateCita($data, $id);
        $this->response($barber);
    }

    public function deleteReserva_delete($id){
        $this->load->model("api_model");
        $barber = $this->api_model->deleteCita($id);
        $this->response($barber);
    }

    //obtener detalle de las citas por usuario
    public function obtener_detalle_cita_get($id_usuario){
        $query = $this->db->query('SELECT a.fecha, a.hora, b.nombre, b.correo, c.nombre FROM `citas` a INNER JOIN login b ON a.id_usuario = b.id INNER JOIN barbero c ON a.id_barbero = c.id WHERE a.id_usuario = "'.$id_usuario.'"');
        $respuesta = array('error' => FALSE, 'detalle_cita' => $query->result_array());
        $this->response($respuesta);
    }

    //crear una nueva reserva por usuario logueado
    public function insert_cita_barbero_post($fecha="0", $hora="0", $id_barbero="0", $id_usuario="0"){

        $data = $this->post();
        
        //verifica que no tenga ninguna cita asignada con el barbero a la misma hora y fecha
        $condiciones = array('fecha' => $data['fecha'], 'hora' => $data['hora'], 'id_usuario' => $data['id_usuario'], 'id_barbero' => $data['id_barbero']);
        $this->db->where($condiciones);
        $query = $this->db->get('citas');

        $existe = $query->row();

        if ($existe) {
            $respuesta = array('error' => TRUE, 'status' => 'false', 'mensaje' => 'Ya existe');
            $this->response($respuesta); 
            return;

        }else{
          $query = $this->db->insert('citas', $data);
          $respuesta = array('error' => FALSE, 'status' => $query, 'cita' => $data);
          $this->response($respuesta);
        }        
    }

    public function insertReservaBarbero_post(){   
      // $data = $this->post(); deprecated
      $data = $this->request->body;
      $query = $this->db->insert('citas', $data);
      $rows = $this->db->affected_rows();
      $respuesta = array('error' => FALSE, 'status' => $query, 'cita' => $data);
      $this->response($respuesta);
        
    }

    //verificar si el barbero esta disponible el fecha y hora seleccionada
    public function check_general_calendar_post($fecha="0", $hora="0", $id_barbero="0"){
        
        $data = $this->post();
        $condiciones = array('fecha' => $data['fecha'], 'hora' => $data['hora'], 'id_barbero' => $data['id_barbero']);
        $this->db->where($condiciones);
        $query = $this->db->get('citas');

        $existe = $query->row();

        if ($existe) {
           $respuesta = array('error' => TRUE, 'status' => 'false', 'mensaje' => 'Ya existe');
           $this->response($respuesta); 
           return;
        }
    }

    public function updateReservaBarbero_put($id){
        $data = $this->request->body;  
        $this->db->set($data);
        $this->db->where('id', $id);
        $query = $this->db->update('citas');
        $respuesta = array('error' => FALSE, 'status' => $query, 'cita' => $data);
        $this->response($respuesta);
    }

    public function deleteReservaBarbero_delete($id){
       $this->db->where('id', $id);
       $query = $this->db->delete('citas');
       $status = ($query) ? 'Eliminado...' : 'Error, no eliminado..';
       $respuesta = array('error' => FALSE, 'status' => $status);
       
       $this->response($respuesta);
    }

}