<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empresas extends CI_Controller {

    public function __construct() {
        parent::__construct();        

        date_default_timezone_set('America/Lima');        
        $this->load->model('empresas_model');
        $this->load->model('accesos_model');
        $this->load->model('activos_model');
        $this->load->helper('ayuda');        
//
        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index() {
        $data['empresa'] = $this->empresas_model->select(1);
        $data['activo'] = $this->activos_model->select();
        
        $this->accesos_model->menuGeneral();
        $this->load->view('empresas/basic_index', $data);
        $this->load->view('templates/footer');
    }
    
    public function logo() {
        $data['empresa'] = $this->empresas_model->select(1);
        
        $this->accesos_model->menuGeneral();
        $this->load->view('empresas/logo', $data);
        $this->load->view('templates/footer');
    }
    
    public function logo_g() {
        //        $path = $_FILES['foto']['name'];
        //        ECHO $path;EXIT;
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        //ECHO $ext;
        //EXIT;
        
        $carpeta = "images/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['foto']['name'];
        copy($_FILES['foto']['tmp_name'], $destino);
        
        $data = array(
            'foto' => $_FILES['foto']['name'],           
        );
                
        $this->empresas_model->modificar(1, $data);


        redirect(base_url() . "index.php/empresas/logo");
    }
    
    public function index2() {
        $data['empresas'] = $this->empresas_model->select();
        $data['activo'] = $this->activos_model->select();
        
        $this->accesos_model->menuGeneral();
        $this->load->view('empresas/index2', $data);
        $this->load->view('templates/footer');
    }
    
    public function perfil_detalle(){
        $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));
        
        $this->load->view('templates/header_sin_menu');
        $this->load->view('empresas/perfil_detalle', $data);
        $this->load->view('templates/footer');
    }
    
    public function imagen_detalle(){        
       
        $nombre_file = sanear_string(date("Y-m-d--H-i-s")."---".$_FILES["foto"]["name"]);
        $config['upload_path'] = './images/empresa';
        $config['allowed_types'] = 'gif|jpg|png|doc|docx|pdf|txt|xls|xlsx|ppt|pptx';
        $config['file_name'] = $nombre_file;
        $config['overwrite'] =  'TRUE';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            $data_upload = $this->upload->data();
            $this->empresas_model->modificar($this->input->post('empresa_id'), array('imagen_detalle' => $nombre_file));
        }
        redirect(base_url()."index.php/empresas/perfil_detalle/".$this->input->post('empresa_id'));
    }
    
    public function perfil_pdf(){
        $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));
        
        $this->load->view('templates/header_sin_menu');
        $this->load->view('empresas/perfil_pdf', $data);
        $this->load->view('templates/footer');
    }
    
    public function imagen_pdf(){        
       
        $nombre_file = sanear_string(date("Y-m-d--H-i-s")."---".$_FILES["foto"]["name"]);
        $config['upload_path'] = './images/empresa';
        $config['allowed_types'] = 'gif|jpg|png|doc|docx|pdf|txt|xls|xlsx|ppt|pptx';
        $config['file_name'] = $nombre_file;
        $config['overwrite'] =  'TRUE';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            $data_upload = $this->upload->data();
            $this->empresas_model->modificar($this->input->post('empresa_id'), array('imagen_pdf' => $nombre_file));
        }
        redirect(base_url()."index.php/empresas/perfil_pdf/".$this->input->post('empresa_id'));
    }
    
    public function guardar(){
        
        $data = array(
            'empresa' => $_POST['empresa'],
            'nombre_comercial' => $_POST['nomcom'],
            'descripcion1' => $_POST['descripcion1'],
            'ruc' => $_POST['ruc'],
            'domicilio_fiscal' => $_POST['domicilio_fiscal'],
            'telefono_fijo' => $_POST['telefono_fijo'],
            'telefono_fijo2' => $_POST['telefono_fijo2'],
            'telefono_movil' => $_POST['telefono_movil'],
            'telefono_movil2' => $_POST['telefono_movil2'],
            'activo' => $_POST['activo'],      
               
        );
//                
        $this->empresas_model->save($data);
//        $this->select();
        
        echo "Hola recon:".$_POST['empresa'];
    }
    
    public function modificar(){
        $data['empresa'] = $this->empresas_model->select(1);
        
        $this->accesos_model->menuGeneral();
        $this->load->view('empresas/basic_update', $data);
        $this->load->view('templates/footer');
    }



    public function modificar_g(){

        if($_POST['empresa']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Razon Social"]);
            exit();
        }

         if($_POST['nomcom']=='')
                {
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Nombre Comercial"]);
                    exit();
                }

        if($_POST['ruc']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Ruc"]);
            exit();
        }

        if($_POST['domicilio_fiscal']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Dirección"]);
            exit();
        }

       

                if($_POST['dep']=='')
                {
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Departamento"]);
                    exit();
                }

                if($_POST['pro']=='')
                {
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Provincia"]);
                    exit();
                }

                if($_POST['dis']=='')
                {
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Distrito"]);
                    exit();
                }

                if($_POST['urb']=='')
                {
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Urbanización"]);
                    exit();
                }

                if($_POST['ubigeo']=='')
                {
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar Ubigeo"]);
                    exit();
                }

       
        
        $data = array(
            'empresa' => $_POST['empresa'],
            'descripcion1' => $_POST['empresa'],
            'ruc' => $_POST['ruc'],
            'domicilio_fiscal' => $_POST['domicilio_fiscal'],
            'correo' => $_POST['correo'],
            'activo' => 'activo',
            'telefono_movil' => $_POST['telefono_movil'],
            'telefono_fijo' => $_POST['telefono_fijo'],

            'nombre_comercial' => $_POST['nomcom'],
            'departamento' => strtoupper($_POST['dep']),
            'provincia' => strtoupper($_POST['pro']),
            'distrito' => strtoupper($_POST['dis']),
            'urb' => strtoupper($_POST['urb']),
            'ubigeo' => $_POST['ubigeo'],
            'pass_certificate' => $_POST['pass_certificado']
             //'save' => 1,  
             //'sfs' => 1             
        );     
        $this->empresas_model->modificar(1, $data);

        //$carpeta = substr(base_url(),0,-strlen(SISTEMA.'/')-1).CARPETA.'/SFS_v1.2/sunat_archivos/sfs/CERT';
        $carpeta = RUTA_API.'/sfs/'.$_POST['ruc'].'/CERT/';

        opendir($carpeta);
        $destino = $carpeta.$_FILES['certificado']['name'];
        //print_r($destino);exit();
        copy($_FILES['certificado']['tmp_name'], $destino);
    
        sendJsonData(['status'=>STATUS_OK]);
        exit();
        //redirect(base_url() . "index.php/empresas/index");                
    }
    
    public function modificar_sfs(){
        $dato = $this->input->get('dato');
         $data = array(
             'sfs' => 1           
        );     
        $this->empresas_model->modificar(1, $data);

    }

    public function select(){
        echo json_encode($this->empresas_model->select());
    }
    
    



}