<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_insumos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idTipoInsumo = '') {
        if($idTipoInsumo == '') {

            $rsTipoInsumos = $this->db->from("tipo_insumos")
                                     ->where("estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsTipoInsumos;
        } else {
            $rsTipoInsumo = $this->db->from("tipo_insumos")
                            ->where("id", $idTipoInsumo)
                            ->get()
                            ->row();
            return $rsTipoInsumo;          
        }           
    }

}    