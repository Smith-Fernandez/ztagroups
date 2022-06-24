<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($id = '', $order = '') {
//        if ($where === FALSE) {
//            $query = $this->db->get('activos');            
//            return $query->result_array();
//        }
//        $query = $this->db->get_where('activos', array('id' => $where));        
//        $query = $this->db->order_by("activo", "asc");
//        return $query->row_array();

        if ($id != '') {
            $sql = "SELECT *FROM activos WHERE id = " . $id;
            $query = $this->db->query($sql);
            return $query->row_array();
        }
        
        $sql = "SELECT *FROM activos ".$order;
        $query = $this->db->query($sql);
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
        
        
        
        
        
        
        
        
    }

}