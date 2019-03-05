<?php

   class Api_model extends CI_Model{

        public function getAll(){
          $query = $this->db->get("facturas");
          if ($query->num_rows() > 0) {
              return $query->result();
          }  
        }

        public function getById($id){
          $query = $this->db->query("SELECT * FROM `facturas` WHERE id_factura =".$id);
          return $query->result_array();
        }

        public function insertFactura($data){
           $query = $this->db->insert('facturas', $data);
           return $data; 
        }

        public function updateFactura($data, $id){
          $this->db->set($data);
          $this->db->where('id_factura', $id);
          $query = $this->db->update('facturas');
          return $data;
        }

        public function deleteFactura($id){
          $this->db->where('id_factura', $id);
          $query = $this->db->delete('facturas');
          $status = ($query) ? 'Eliminado...' : 'Error, no eliminado..';
          $respuesta = array('error' => FALSE, 'status' => $status);
          return $respuesta;
        }

   } 

?>