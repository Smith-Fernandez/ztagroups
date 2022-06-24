<?PHP

/**
 * 
 */
class Producto_presentaciones_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function select($idProductoPres='',$idProducto = ''){

		if($idProductoPres == ''){
			if($idProducto != ''){
				$this->db->where('prp.prod_id',$idProducto);
			}
			$rsProductoPres =  $this->db->from('producto_presentaciones prp')
										->join('productos pro','pro.prod_id = prp.prod_id')
										->join('presentaciones pre','pre.pre_id = prp.pre_id')
										->where('estado',ST_ACTIVO)
										->get()
										->result();

			return $rsProductoPres;			
		}else{

			$rsProductoPre =  $this->db->from('producto_presentaciones')
										->join('productos pro','pro.prod_id = prp.prod_id')
										->join('presentaciones pre','pre.pre_id = prp.pre_id')
										->where('pre.estado',ST_ACTIVO)
										->where('id',$idProductoPres)
										->get()
										->row();
		}
	}

	public function selectPresentacion($idProducto,$idView){	
			if($idProducto != ''){
				$this->db->where('prp.prod_id',$idProducto);
			}			
			$rsProductoPres =  $this->db->from('producto_presentaciones prp')
										->join('productos pro','pro.prod_id = prp.prod_c_id')
										->where('estado',ST_ACTIVO)
										->get()
										->result();

			
			$cssClass = ($idView == 1) ? 'btn_eliminar_presentacionVenta' : 'btn_eliminar_presentacion';

			foreach ($rsProductoPres as $rsProductoPre) {		

				$rsProductoPre->eliminar = "<a href='#'' class='btn btn-default btn-xs ". $cssClass ."' data-id='{$rsProductoPre->id}' data-msg='Desea Eliminar Presentacion: {$rsProductoPre->pre_nombre} ?'>Eliminar</a>";
			}

			return $rsProductoPres;		
	}	


	public function guardar(){

		$prod_c_id =  $_POST['producto_c_id'];
		$prod_id   = $_POST['idProducto'];
		$cantidad  = $_POST['cantidad'];

		$insertArray= array('prod_c_id' => $prod_c_id,
							'prod_id'  => $prod_id,
							'cantidad' => $cantidad);

		$this->db->insert('producto_presentaciones',$insertArray);		
	}

	public function eliminar($idPresentacion){

		$arrayUpdate = array(
								'estado' => ST_ELIMINADO);

		$this->db->where('id',$idPresentacion)
				 ->update('producto_presentaciones',$arrayUpdate);
		return true;
	}
}




