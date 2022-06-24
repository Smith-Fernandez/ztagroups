<?PHP
    if(!defined('BASEPATH'))
        exit ('No direct script access allowed');
        
    class Tipo_detracciones_model extends CI_Model {
     
        public function __construct() {
            parent::__construct();         
        }        
        public function select($tipo_detracciones=''){
            if ($tipo_detracciones == ''){

              $result = $this->db->from("tipo_detracciones")
                                 ->where("estado",ST_ACTIVO)
                                 ->get()
                                 ->result();
                             return $result;
              } else{
                    $result = $this->db->from("tipo_detracciones")
                                       ->where("id",$tipo_pagos)
                                       ->where("estado",ST_ACTIVO)
                                       ->get()
                                       ->row();
                             return $result;
            }
        }
}