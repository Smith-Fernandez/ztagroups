<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function __construct() {
        parent::__construct();        
        $this->load->model('clientes_model');
        $this->load->model('tipo_contratos_model');
        $this->load->model('activos_model');
        $this->load->model('accesos_model');
        $this->load->model('empleados_model');
        $this->load->model('contactos_model');
        $this->load->model('contratos_model');
        $this->load->model('empresas_model');
        $this->load->model('tipo_clientes_model');
        
        $this->load->library('pagination');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }   

    public function index($pagina = FALSE){                
        $data['tipo_contratos'] = $this->tipo_contratos_model->select();
        $order_activo = " ORDER BY activo ASC ";
        $data['activos'] = $this->activos_model->select('', $order_activo);
        $data['tipo_clientes']= $this->tipo_clientes_model->select('','','activo');

        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');

        if (($this->input->post('estado_cliente')) != '') {
            $data['tipo_activo_select'] = $this->input->post('estado_cliente');
            if (($this->input->post('estado_cliente')) == 'todos') {
                $data['tipo_activo_select'] = '';
            }
        } else {
            $data['tipo_activo_select'] = 1; //quiere decir siempre activo
        }

        $data['tipo_contratos_select'] = $this->input->post('tipo_contratos');
        
        if (($this->input->post('estado_contrato')) != '') {
            $data['tipo_activo_contrato_select'] = $this->input->post('estado_contrato');
            if (($this->input->post('estado_contrato')) == 'todos') {
                $data['tipo_activo_contrato_select'] = '';
            }
        } else {
            $data['tipo_activo_contrato_select'] = ''; //quiere decir siempre activo
        }
        
        $data['tipo_clientes_select']=  $this->input->post('tipo_cliente');

        $estado_cliente = array();
        $estados_cliente = '';
        if (($this->input->post('estado_cliente') != '') && ($this->input->post('estado_cliente') != 'todos')) {
            $estado_cliente = $this->activos_model->select($this->input->post('estado_cliente'));
            $estados_cliente = $estado_cliente['activo'];
        }


        $estado_contrato = array();
        $estados_contrato = '';
        if (($this->input->post('estado_contrato') != '') && ($this->input->post('estado_contrato') != 'todos')) {
            $estado_contrato = $this->activos_model->select($this->input->post('estado_contrato'));
            $estados_contrato = $estado_contrato['activo'];
        }
        

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }
        
        $tipo_cliente = '';
        if(($this->input->post('tipo_cliente')!= '') && ($this->input->post('tipo_cliente')!="todos")){           
            $tipo_cliente= $this->input->post('tipo_cliente');
        }
        
                
        //PAGINACION - PAGINACION
        $inicio = 0;
        $limite = 20;
        $tipo_contrato = '';
        if($pagina){
            //$inicio = $pagina;
            $inicio = ($pagina-1)*$limite;
        } 
        
         $data['clientes'] = $this->clientes_model->select('', $estados_cliente, $cliente_id, '',$tipo_cliente, $tipo_contrato, $estados_contrato,$limite,$inicio);
        
        
         $config['base_url']    = base_url().'index.php/clientes/index/';
         $config['total_rows']  = count($this->clientes_model->select('', $estados_cliente, $cliente_id, '',$tipo_cliente, $tipo_contrato, $estados_contrato));
         $config['per_page']    = $limite;
         $config['uri_segment'] = 3;        
         //$choice = $config['total_rows']/$config['per_page'];
         $config['num_links'] = 2;
         $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // PAGINACION - BOOSTRAP
        $config['full_tag_open']   = '<ul class="pagination">';
        $config['full_tag_close']  = '</ul>';
        $config['first_link']      = false;
        $config['last_link']       = false;
        $config['first_tag_open']  = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link']       = '&laquo';
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '</li>';
        $config['next_link']       = '&raquo';
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '</li>';
        $config['last_tag_open']   = '<li>';
        $config['last_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="active"><a href="#">';
        $config['cur_tag_close']   = '</a></li>';
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';                                            
        
        $this->pagination->initialize($config);
        $data['pagination']  = $this->pagination->create_links();        

        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/index', $data);
        $this->load->view('templates/footer');
    }

    public function ExportarExcel($id='',$estado='',$tipo='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('id', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('activo', 'activo');
        }
        if($this->uri->segment(5)!='0') {
            $this->db->where('tipo_cliente_id', $this->uri->segment(5));
        }

        $this->db->from("clientes");                              
        $query = $this->db->get();
        $result = $query->result();
        //print_r($result);exit();
        
        /*EXPORTAR A EXCEL*/
        $spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i=2;
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'N°')
                ->setCellValue('B1', 'RUC/DNI')
                ->setCellValue('C1', 'CLIENTE')
                ->setCellValue('D1', 'RAZON SOCIAL/NOMBRES');
            
               // ->setCellValue('E1', 'EMPRESA');

        $spreadsheet->getActiveSheet()->setTitle('clientes');
        foreach ($result as $value) {
            $empresa = $this->db->from('empresas')
                            ->where('id',$value->empresa_id)
                            ->get()
                            ->row();

            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->id)
                        ->setCellValue('B'.$i, $value->ruc)
                        ->setCellValue('C'.$i, $value->tipo_cliente)
                        ->setCellValue('D'.$i, $value->nombres.' '.$value->razon_social);
                        //->setCellValue('E'.$i, $empresa->empresa);
            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_cliente.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }
    
    public function index_select(){
        $cliente_id = $this->input->post('cliente_id');
        $tipo_contrato = $this->input->post('tipo_contratos');
        $actividad_id = $this->input->post('actividad');
        redirect(base_url()."index.php/clientes/index/".$cliente_id."/".$tipo_contrato."/".$actividad_id);
    }

    public function nuevo() {
        $data['activos'] = $this->activos_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();

        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/nuevo', $data);
        $this->load->view('templates/footer');
    }

    public function grabar() {
        
        $ruc =  $_POST['ruc'];        
        //BUSCAMOS EN EL CLIENTE EN LA BASE DE DATOS        
        $cliente = $this->clientes_model->clientePorRuc($ruc);                
        if($cliente['id'] == 0){        
        
        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));       
        $data = array(
            'ruc' => trim($this->input->post('ruc')),
            'razon_social' => trim($this->input->post('razon_social')),
            'domicilio1' => trim($this->input->post('domicilio1')),
            'email' => trim($this->input->post('email')),
             'email2' => trim($this->input->post('email2')),
              'email3' => trim($this->input->post('email3')),
            'pagina_web' => $this->input->post('pagina_web'),
            'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'empresa_id' => 1,
            'activo' => 'activo',
            'empleado_id_insert' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        if ($this->input->post('nombres') != ''){
            $data = array_merge($data,array('nombres' => $this->input->post('nombres')));
        }
        if($tipo_cliente[0] == "2"){ //solo en caso de ser cliente juridico se actualiza el campo: razon_social_sunat
            $data = array_merge($data,array('razon_social_sunat' => $this->input->post('razon_social')));
        }
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ' ingresado exitosamente';        

        $this->clientes_model->insertar($data, $mensaje);
        } else{
        $this->session->set_flashdata('mensaje_cliente_index', 'Cliente: YA SE ENCUENTRA REGISTRADO');
        }        
        redirect(base_url()."index.php/clientes/index");
    }

    public function grabar_para_comprobante() {
     

        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));
        $id = $this->clientes_model->obtener_codigo();

        if($this->input->post('ruc')==''){
            $cliente['success'] = 1;
            echo json_encode($cliente);
            exit();
        }

        if($this->input->post('razon_social')==''){
            $cliente['success'] = 2;
            echo json_encode($cliente);
            exit();
        }

        if($this->input->post('domicilio1')==''){
            $cliente['success'] = 3;
            echo json_encode($cliente);
            exit();
        }
        
        
        $ruc =  $_POST['ruc'];        
        //BUSCAMOS EN EL CLIENTE EN LA BASE DE DATOS        
        $cliente = $this->clientes_model->clientePorRuc($ruc);                
        if($cliente['id'] == 0){             
        $data = array(
             'id' => $id,
            'ruc' => trim($this->input->post('ruc')),
            'razon_social' => strtoupper(trim($this->input->post('razon_social'))),
            'domicilio1' => strtoupper(trim($this->input->post('domicilio1'))),
            'email' => trim($this->input->post('email')),
            //'pagina_web' => $this->input->post('pagina_web'),
            //'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'empresa_id' => 1,
            'activo' => 'activo',
            'empleado_id_insert' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        if ($this->input->post('nombres') != ''){
            $data = array_merge($data,array('nombres' => $this->input->post('nombres')));
            
        }
        if($tipo_cliente[0] == "2"){ //solo en caso de ser cliente juridico se actualiza el campo: razon_social_sunat
            $data = array_merge($data,array('razon_social_sunat' => $this->input->post('razon_social')));
        }
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ' ingresado exitosamente';

        $this->clientes_model->insertar($data, $mensaje);

        if($tipo_cliente[0]==1){
            $tpc = "DNI";
        }else if($tipo_cliente[0]==2){
            $tpc = "RUC";
        }else{
            $tpc = "SIN DOC.";
        }
        
        $cliente['nombre'] = $tpc.' '.$this->input->post('ruc').' '.strtoupper($this->input->post('razon_social'));
        $cliente['direccion'] = strtoupper($this->input->post('domicilio1'));
        $cliente['id'] = $id;
        $cliente['success'] = 4;
        echo json_encode($cliente);
        } else{
            $cliente['success'] = 5;
            echo json_encode($cliente);
            exit();
        }        
    }

    public function perfil(){
        $data['cliente'] = $this->clientes_model->select($this->uri->segment(3));        
                
        if($data['cliente']['empresa_id']>0){
            $data['empresa'] = $this->empresas_model->select($data['cliente']['empresa_id']);
        }                
        
        $this->load->view('templates/header_sin_menu');
        $this->load->view('clientes/perfil', $data);
        $this->load->view('templates/footer');
    }

    public function selectAutocompleteEmpleados(){
        $value = $this->input->get('term');
        $where_cutomizado = ' tipo_empleado_id IN (3,4,5)';
        echo json_encode($this->empleados_model->selectAutocomplete($value, '', '', '', '',$where_cutomizado));
    }

    public function modificar(){
        $data['cliente'] = $this->clientes_model->select($this->uri->segment(3));
        $data['activos'] = $this->activos_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_clientes'] = $this->tipo_clientes_model->select('','','activo');
        
        if($data['cliente']['empleado_id_responsable']>0){
            $data['abogado'] = $this->empleados_model->select($data['cliente']['empleado_id_responsable']);
        }
        
        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/modificar', $data);
        $this->load->view('templates/footer');
    }

    public function modificar_g(){
        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));
        $data = array(
            'ruc' => $this->input->post('ruc'),
            'razon_social' => $this->input->post('razon_social'),
            'razon_social_sunat' => $this->input->post('razon_social_sunat'),
            'nombres'    => $this->input->post('nombres'),
            'domicilio1' => $this->input->post('domicilio1'),
            'domicilio2' => $this->input->post('domicilio2'),
            'email' => $this->input->post('email'),
            'email2' => $this->input->post('email2'),
            'email3' => $this->input->post('email3'),
            'pagina_web' => $this->input->post('pagina_web'),
            'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_fijo_2' => $this->input->post('telefono_fijo_2'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'telefono_movil_2' => $this->input->post('telefono_movil_2'),
            'empresa_id' => $this->input->post('empresa'),
            'activo' => $this->input->post('activo'),
            'fecha_update' => date("Y-m-d H:i:s"),
            'empleado_id_update' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        
        if($tipo_cliente[0] == "2"){ //solo en caso de ser cliente juridico se actualiza el campo: razon_social_sunat
            $data = array_merge($data, array('razon_social_sunat' => $this->input->post('razon_social_sunat')));
        }
        
        
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ', modificado exitosamente';
        $this->clientes_model->modificar($this->input->post('id'), $data, $mensaje);
        redirect(base_url()."index.php/clientes/index");
    }

    public function eliminar(){      
        $id = $this->uri->segment(3);
        $data = array('eliminado_cliente' => 1);
        $this->clientes_model->modificar($id, $data);    
        redirect(base_url()."index.php/clientes/index");
    }
    
    //SeachCliente    
    public function searchCustomer(){        
        $ruc =  $_POST['ruc'];
        $typeCustomer = $_POST['tipoCliente'];        
        //BUSCAMOS EN EL CLIENTE EN LA BASE DE DATOS        
        $cliente = $this->clientes_model->clientePorRuc($ruc);                
        if($cliente['id'] == 0){        
        //OBTENEMOS EL VALOR            
        switch ($typeCustomer) {
        case 1:
        $consultaApi = file_get_contents('http://mundosoftperu.com/reniec/consulta_reniec.php?dni='.$ruc);
        $consulta = json_decode($consultaApi);        
            if($consultaApi != ''){
                //$partes = explode('|', $consultaApi);  
                sendJsonData(['status'=>STATUS_OK,'typeCustomer' => $typeCustomer,'paterno' => $consulta[2],'materno' => $consulta[3],'nombres' => $consulta[1]]);
            }
            else{
                sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'DNI NO ENCONTRADO']);
                exit();
            }        
            break;
        //$consultaSunat = fille_get_contents('https://api.sunat.cloud/ruc/'.$ruc);                
        case 2:
        $consultaApi = file_get_contents('https://apis.sitefact.pe/api/ConsultaRuc?ruc='.$ruc);        
        $consulta = json_decode($consultaApi);        
            if($consulta->success != FALSE){            
            $razonSocial = $consulta->result->RazonSocial;
            $direccionFiscal = $consulta->result->DireccionFiscal;                                 
            sendJsonData(['status'=>STATUS_OK,'typeCustomer'=> $typeCustomer,'razonSocial' => $razonSocial, 'direccionFiscal' => $direccionFiscal]);
            } else{
              sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'RUC NO ENCONTRADO']);
              exit();  
            }        
            break;
        }}
        else{
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'Cliente ya se encuentra registrado']);
            exit();}
        }    
}