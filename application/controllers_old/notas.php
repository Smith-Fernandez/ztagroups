<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require __DIR__ . '/../ticket/autoload.php';
use Endroid\QrCode\QrCode;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use mikehaertl\wkhtmlto\Pdf;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
class Notas extends CI_Controller
{
    public function __construct(){    
        parent::__construct();
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('almacenes_model');
        $this->load->model('monedas_model'); 
        $this->load->model('empresas_model');
        $this->load->model('empleados_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('tipo_items_model');
        $this->load->model('notas_model');
        $this->load->model('productos_model');
        $this->load->model('transportistas_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('medida_model');
        $this->load->model('igv_model');
        $this->load->model('cajas_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id  = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    public function index()    
    {
        //NOTAP_ID
        if($this->uri->segment(3) != '' )     
        $data['notap_id'] = $this->uri->segment(3);

    	$data['empresa'] = $this->empresas_model->select();
    	$data['empleados'] = $this->empleados_model->select2(3);
        $data['config'] = $this->db->get('comprobantes_ventas')->row();

        $viewContent = 'notas/basic_index';
        $rs = $this->cajas_model->ultimoRegCaja();    

        //var_dump($rs);exit;
        if(($rs->tipo_transaccion_id == 2)){
            echo '<script>alert("DEBE APERTURAR CAJA")</script>';                    
            $viewContent = 'cajas/index';
        }

        $this->accesos_model->menuGeneral();
        $this->load->view($viewContent, $data);
        $this->load->view('templates/footer');    	
    }

    //ALEXANDER FERNANDEZ 16-10-2020
    public function modal_pagoMonto(){
        $data['tipo_pagos'] =  $this->tipo_pagos_model->select();
        echo $this->load->view('notas/modal_pagoMonto',$data);
    }

    public  function SeleccionaListaPrecio(){
    $producto = $this->productos_model->select($_REQUEST['productoId']);
    $data=[

         "producto"=>$producto

         ];    
        echo $this->load->view('notas/modal_lista_precio',$data);    
    }


    //ALEXANDER FERNANDEZ 16-08-2020
    public function modal_envio_notaPedido(){          
        $data['nota'] = $this->notas_model->select($this->uri->segment(3));
        $data['tipo_documento'] = 'NOTA DE VENTA';        
        echo $this->load->view('notas/modal_envio_notaPedido',$data);
    }

    //ALEXANDER FERNANDEZ 16-08-2020
    public function modal_envio_whatsap(){

        $data['nota'] = $this->notas_model->select($this->uri->segment(3));
        echo $this->load->view("notas/modal_envio_whatsap",$data);
    }


    public function modal_envio_email(){

        $data['nota'] = $this->notas_model->select($this->uri->segment(3));
        echo $this->load->view("notas/modal_envio_email",$data);
    }


    public function modal_envio_whatsap_g(){

        $notap_id = $_POST['notap_id'];
        $telefono_movil = $_POST['telefono_movil'];        

        $nota = $this->notas_model->select($notap_id);

        if ($nota->telefono_movil_1 == '') {
            $this->db->where('ruc',$nota->ruc);
            $this->db->update('clientes',array('telefono_movil_1'=> $telefono_movil));
        }

        echo json_encode(['status' => STATUS_OK, 'msg' => 'Mensaje enviado correctamente']);
        exit();        
    }

    //ALEXANDER FERNANDEZ 04-08-2020
    public function modal_envio_email_g(){

        $notap_id = $_POST['notap_id'];
        $mailcc = $_POST['correo'];        
        //Correo de Empresa
        $correo = $this->db->from("correo")
                           ->get()
                           ->row();
        //Datos de la Empresa /
        $empresa = $this->db->from("empresas")
                            ->where("id",1)
                            ->get()
                            ->row();

        $this->load->library('email'); 
        // Configure email library
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $correo->correo_host;
        $config['smtp_port'] = $correo->correo_port;
        $config['smtp_user'] = $correo->correo_user;
        $config['smtp_pass'] = $correo->correo_pass;
        $config['smtp_crypto'] = $correo->correo_cifrado;
        $config['charset']='utf-8'; // Default should be utf-8 (this should be a text field) 
        $config['newline']="\r\n"; //"\r\n" or "\n" or "\r". DEFAULT should be "\r\n" 
        $config['crlf'] = "\r\n"; //"\r\n" or "\n" or "\r" DEFAULT should be "\r\n" 
        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        $nota = $this->notas_model->select($notap_id);
        if($nota->email == ''){
            $this->db->where('ruc', $nota->ruc);
            $this->db->update('clientes',array('email' => $mailcc));
        }                

        //echo '123';exit();
        //CREANDO PDF
        $this->create_pdf($notap_id);
        $file_pdf = APPPATH . "files_pdf/nota_venta/" .$empresa->ruc.'-NP'.$nota->notap_correlativo . ".pdf";
        //echo $file_pdf;exit;
        
        $this->email->attach($file_pdf);
        $sender_email = $correo->correo_user;
        $sender_username = $empresa->empresa;  

        // Sender email address
        $this->email->from($sender_email, $sender_username);  
        $this->email->to($mailcc);
        $this->email->cc('fernandezdelacruza@gmail.com');


        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        $reemplazar=array("", "", "", "");                                       
        $cliente_razon_social = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($nota->razon_social)));
        $cliente_razon_social = str_replace("&", "Y", trim(utf8_decode($cliente_razon_social)));

        $tipoDocumentoFormat = 'NOTA VENTA';
        $this->email->subject('COPIA '.$tipoDocumentoFormat.' '. $nota->notap_correlativo.'|'.$cliente_razon_social.'|'.$nota->ruc);

        $body  = 'Sres '.$nota->ruc.' '.$cliente_razon_social.'<br><br>';
        $body .= 'Sres '.$empresa->empresa.', '.'envía una '.$tipoDocumentoFormat.'<br><br>';

        $body .= '- TIPO: '.$tipoDocumentoFormat.'<br>';
        $body .= '- NUMERO: '.$nota->notap_correlativo.'<br>';        
        $body .= '- FECHA DE EMISIÓN: '.$nota->notap_fecha.'<br>';
        $body .= '- TOTAL: '.$nota->notap_total.'<br><br><br>';


        $body .= 'También se adjunta el archivo PDF en este email<br>';       
        $this->email->message($body);
       
        //Message in email         
        if (!$this->email->send()) {
            echo json_encode(['status'=>STATUS_FAIL,'msg'=>'Correo Invalido !']);
            exit();            
        } else {
            echo json_encode(['status'=>STATUS_OK,'msg'=>'Correo enviado con éxito !']);
            exit();            
        }
    }



    public function nuevo()
    {
    	$data = array();
    	$data['monedas'] = $this->monedas_model->select();
    	$data['empresas'] = $this->empresas_model->select();
    	$data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
    	$data['tipo_item'] = $this->tipo_items_model->select();
        $data['consecutivo'] = $this->notas_model->maximoConsecutivo()+1;
        $data['transportistas'] = $this->transportistas_model->select();
        $data['tipo_pagos']  = $this->tipo_pagos_model->select();
        $data['medida'] = $this->medida_model->select();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
        
    	$this->accesos_model->menuGeneral();
    	$this->load->view('notas/nuevo', $data);
    	$this->load->view('templates/footer');
    }
    public function editar($idNota)
    {
    	$data['nota'] = $this->notas_model->select($idNota);
        //var_dump($data['nota']);exit;       
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['transportistas'] = $this->transportistas_model->select();
        $data['tipo_pagos']  = $this->tipo_pagos_model->select();
        $data['medida'] = $this->medida_model->select();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo(); 
        $data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
        $this->accesos_model->menuGeneral();       
    	$this->load->view('notas/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function guardarNota()
    {
    	$error = array();
    	if($_POST['cliente_id'] == '')
    	{
    		$error['cliente'] = 'falta ingresar cliente';
    	}
    	if($_POST['fecha'] == '')
    	{
    		$error['fecha'] = 'falta ingresar fecha';
    	}
    	if($_POST['moneda_id'] == '')
    	{
    		$error['moneda_id'] = 'falta ingresar moneda';
    	}
    	if($_POST['direccion'] == '')
    	{
    		$error['direccion'] = 'falta ingresar direccion';
    	}


        //VALIDACION DE INGRESO DE PRODUCTOS 23-09-2020
        $idproduc = $_POST['item_id'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        $medida = $_POST['medida'];
        
        //validamos si la fecha de pago es mayor a la fecha actual y que no este vacio
        $cuota_pagoMonto = $_POST['cuota_pagoMonto'];
        
        $tipo_pagoMonto = $_POST['tipo_pagoMonto'];
        //var_dump($tipo_pagoMonto);exit;
        $z = 0;
        $tienefechapago = false;
        foreach ($tipo_pagoMonto as $value) {
            if ($value!='') {
                if ($value == 2) {
                if ($cuota_pagoMonto[$z] == '') {
                           $tieneProductos = false;
                                $msg = 'debe ingresar fecha de pago';
                                 $z++;
                                break;
                                }else
                                  {
                                   $tienefechapago = true;
                                  
                                  }
              }else{
                $tienefechapago= true;
              }
            }else
            {
                  $tienefechapago = false;
                $msg = 'hay un producto que no se ha registrado bien.';
                break;
            }
              
              $z++;
        }
         if(!$tienefechapago)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>$msg]);
            exit();            
        }


        $tieneProductos = false;
        $msg = 'no hay productos agregados.';
        $b = 0;
        foreach($idproduc as $value)
        {
            if($value!='')
            {                
               if($value==0){
                 if($descripcion[$b]==''){
                    $tieneProductos = false;
                    $msg = 'Ingrese descripción del producto.';
                    break;
                  } else if($medida[$b]==''){
                    
                     $tieneProductos = false;
                    $msg = 'Seleccione una unidad de medida.';
                    break;
                  }else{
                    $tieneProductos = true;
                  }
                }else{
                    $tieneProductos = true;
                }  
            } else {
                $tieneProductos = false;
                $msg = 'hay un producto que no se ha registrado bien.';
                break;
            }
            $b++;
        }

        if(!$tieneProductos)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>$msg]);
            exit();            
        }
        $f = 0; 
        foreach($idproduc as $value){
          if($cantidad[$f]<=0){
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'La cantidad del producto debe ser mayor a cero']);
            exit(); 
          }
          $f++;
        }
       
        $i = 0;
        foreach ($idproduc as $item) {
                    
                $dato_prod = $this->db->from('productos')
                                      ->where('prod_id',$idproduc[$i])
                                      ->get()
                                      ->row();                
                   
                    //$prod_stock = $this->productos_model->stock($idproduc[$i]);
                $prod_stock = $this->productos_model->getStockProductos($idproduc[$i],$dato_prod->prod_almacen_id);
                
                 if($dato_prod->prod_tipo==1){
                    if($_POST['notaId']==''){
                        if($cantidad[$i]==0 OR $cantidad[$i]>$prod_stock){
                           sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                             exit();  
                            } 
                        }else{
                             $this->db->where('notapd_producto_id',$idproduc[$i]);
                             $this->db->where('notapd_notap_id',$_POST['notaId']);
                             $item_producto = $this->db->get('nota_pedido_detalle')->row();

                             if($cantidad[$i]==0 OR $cantidad[$i]>($prod_stock+$item_producto->notapd_cantidad)){
                                 sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                                exit();  
                             }  
                        }                        
                    }                                   
                    $i++;                   
                }   
        
    	if(count($error) > 0)
    	{
    		$data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
    		sendJsonData($data);
    		exit();
    	}    

        if(count($_POST['descripcion'])==0)
        {
            $data = ['status'=>STATUS_FAIL, 'tipo'=>2];
            sendJsonData($data);
            exit();
        }

    	//guardamos el producto
        $notap_id = $this->notas_model->guardarNota();
        //verificamos el estado del checkbox
         $config = $this->db->get('comprobantes_ventas')->row();        
        if($config->ticket_auto == 1){
            $this->data_impresion_pos_ticket($notap_id);
        }       
        if($notap_id > 0)
        {            
            echo json_encode(['status'=>STATUS_OK,'notap_id'=> $notap_id]);
            exit();
        }   
    }

    public function eliminar($idProducto)
    {
    	$result = $this->productos_model->eliminar($idProducto);
    	if($result)
    	{
     		sendJsonData(['status'=>STATUS_OK]);
     		exit();
    	}else
    	{
    		sendJsonData(['status'=>STATUS_FAIL]);
    		exit();
    	}    	
    }
    public function getMainList(){
              
        $rsDatos = $this->notas_model->getMainList();
        sendJsonData($rsDatos);          
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->notas_model->getMainListDetail();
        sendJsonData($rsDatos);        
    }

    public function create_pdf($idNota){
        
         require_once (APPPATH .'libraries/Numletras.php');
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("clientes as cli", "np.notap_cliente_id=cli.id")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos tpg","np.notap_tipopago_id = tpg.id")
                           ->join("transportistas tra","np.notap_transportista_id = tra.transp_id")                           
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();
                
        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id','left')
                                ->join("almacenes as alm", "alm.alm_id=p.prod_almacen_id",'left')
                                ->join('medida med','med.medida_id = f.notapd_unidad_id')
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();        
                           
        $rsNota->detalles = $rsDetalles;                                     

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('nota_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.nota_id', $idNota)
                                ->get()
                                ->result();

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                      ->where("id", $rsNota->notap_empleado_insert)
                      ->get()
                      ->row();

        $rsmoneda = $this->db->from('monedas')
                             ->where('id',$rsNota->notap_moneda_id)
                             ->get()
                             ->row();

        $num = new Numletras();
        $totalVenta = explode(".",$rsNota->notap_total);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsmoneda->moneda;
        $rsNota->total_letras = $totalLetras;           
        
        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal                    
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "pagoMonto" =>  $rsPagoMonto,
                    "cliente" => $rsCliente,
                    "empleado" => $rsEmpleado,
                    "almacen_principal" => $rs_almacen_principal
                ];                       
        $html = $this->load->view("templates/nota.php",$data,true); 

        ////////////////////////////////////////
        $archivo = $rsEmpresa->ruc.'-NP'.$rsNota->notap_correlativo.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();        
        $contenido = $this->pdf->output();

        $bytes = file_put_contents(APPPATH.'files_pdf/nota_venta/'.$archivo, $contenido);
        return true;
    }

    public function decargarPdf($idNota)
    {
        require_once (APPPATH .'libraries/Numletras.php');
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("clientes as cli", "np.notap_cliente_id=cli.id")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos tpg","np.notap_tipopago_id = tpg.id")
                           ->join("transportistas tra","np.notap_transportista_id = tra.transp_id")
                                                    
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();
                
        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id','left') 
                                ->join("almacenes as alm", "alm.alm_id=p.prod_almacen_id",'left')
                                ->join('medida med','med.medida_id = f.notapd_unidad_id')                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();        
                           
        $rsNota->detalles = $rsDetalles;                                     

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('nota_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.nota_id', $idNota)
                                ->get()
                                ->result();

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                      ->where("id", $rsNota->notap_empleado_insert)
                      ->get()
                      ->row();

        $rsmoneda = $this->db->from('monedas')
                             ->where('id',$rsNota->notap_moneda_id)
                             ->get()
                             ->row();

        $num = new Numletras();
        $totalVenta = explode(".",$rsNota->notap_total);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsmoneda->moneda;
        $rsNota->total_letras = $totalLetras;           
        
        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal                      
                                               
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "pagoMonto" =>  $rsPagoMonto,
                    "cliente" => $rsCliente,
                    "empleado" => $rsEmpleado,
                    "almacen_principal" => $rs_almacen_principal
                ];                   
        $html = $this->load->view("templates/nota.php",$data,true); 

        ////////////////////////////////////////
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idNota.pdf",
            array("Attachment"=>0)
        );
    }
    public function buscador_item() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprodSC($item));
    }    

    public function anular_nota(){

        $notaId   = $this->input->get('nota_id');        
            
        //echo 1122;exit;

        $this->db->set('notap_estado', 2);
        $this->db->where('notap_id',$notaId);
        $this->db->update('nota_pedido');

        $this->notas_model->devolverStock($notaId);

        $datos['go'] = 1;
        echo json_encode($datos);   
    }

    public function quitarStock($idProducto,$cantidad)
    {
      //solo quitaremos de stock a los producto que pertenezacan a esa compra
      $this->db->where("ejm_producto_id",$idProducto);
      $this->db->where('ejm_almacen_id',$this->session->userdata('almacen_id'));
      $this->db->where('ejm_estado',ST_PRODUCTO_VENDIDO);
      $ejm = $this->db->get('ejemplar')->result();

      for($x=0;$x<$cantidad;$x++) {
           $this->db->where('ejm_id',$ejm[$x]->ejm_id);
           $this->db->set("ejm_estado", ST_PRODUCTO_DISPONIBLE);
           $this->db->update("ejemplar");  
      }                                
    }

    public function exportarExcel()
    {
        require_once (APPPATH .'libraries/Numletras.php');

        
        if($_GET['cliente']!='undefined'){
            $this->db->where('notap_cliente_id',$_GET['cliente']);
        }  
       
        if($_GET['fecha_inicio'] != 'null'){
            $fecha_inicio =  (new DateTime($_GET['fecha_inicio']))->format('Y-m-d');
            $this->db->where("DATE(com.notap_fecha) >=", $fecha_inicio);
        }

        if($_GET['fecha_fin'] != 'null'){
            $fecha_fin =  (new DateTime($_GET['fecha_fin']))->format('Y-m-d');
            $this->db->where("DATE(com.notap_fecha) <=", $fecha_fin);
        }

        if($_GET['correlativo']!='null'){        
            $this->db->where('notap_correlativo',$_GET['correlativo']);
        }

        if($_GET['vendedor_id'] != '')
            $this->db->where('emp.id',$_GET['vendedor_id']);        

        if($this->session->userdata('accesoEmpleado') != '')
            $this->db->where('emp.id',$this->session->userdata('empleado_id'));     


        $this->db->where('com.notap_almacen', $this->session->userdata('almacen_id'));
        $resultComprobantes = $this->db->from("nota_pedido com")
                                       ->join("nota_pedido_detalle i","com.notap_id=i.notapd_notap_id")
                                       ->join("productos pro","pro.prod_id=i.notapd_producto_id","left")
                                       ->join("categoria c","pro.prod_categoria_id=c.cat_id","left")
                                       ->join("medida m","pro.prod_medida_id=m.medida_id","left")
                                       ->join("empleados emp","emp.id = com.notap_empleado_insert")                                       
                                       ->join("monedas mon","mon.id = com.notap_moneda_id")
                                       ->order_by('com.notap_id DESC, i.notapd_id')
                                       ->get()
                                       ->result();        

        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $i=2;                                    

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Detalle');
        $objPHPExcel->addSheet($myWorkSheet, 1);
        $objPHPExcel->setActiveSheetIndex(1);


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
         

        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("T1")->getFont()->setBold(true);
     
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "CORRELATIVO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "FECHA"); 
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "VENDEDOR"); 
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "DOCUMENTO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "NUM. DOC.");      
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "CLIENTE"); 
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "CODIGO");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "CATEGORIA");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "UNIDAD/MEDIDA");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "DESCRIPCION");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "PRECIO UNITARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "CANTIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "IMPORTE TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "MONEDA");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "TIPO PAGO");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "UTILIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "TRANSPORTISTA");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "PLACA");        
        $objPHPExcel->getActiveSheet()->setCellValue('S1', "ALMACEN");
        $objPHPExcel->getActiveSheet()->setCellValue('T1', "ESTADO");
        $objPHPExcel->getActiveSheet()->setCellValue('U1', "OBSERVACION");

        $suma_totalSoles = 0;
        $suma_totalDolares = 0;
        foreach ($resultComprobantes as $value) {           

            /*datos cliente*/
            $this->db->where('id', $value->notap_cliente_id);
            $this->db->from('clientes');
            $queryCliente = $this->db->get();
            $rsCliente = $queryCliente->row();

            // tipo documento cliente
            $tipo_dcli = 'DNI';
            if (strlen($rsCliente->ruc)>8) {
                $tipo_dcli = 'RUC';
            }           

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsTransportista = $this->db->from('transportistas')
                                        ->where('transp_id', $value->notap_transportista_id)
                                        ->get()
                                        ->row();            
            
            //ALEXANDER FERNANDEZ 01-11-2020
            $rsPagos = $this->db->select('tpg.tipo_pago')
                               ->from('nota_pagos cmp')
                               ->join('tipo_pagos tpg','tpg.id = cmp.tipo_pago_id')
                               ->where('cmp.nota_id', $value->notap_id)
                               ->get()
                               ->result();
                               //var_dump($rsPago);exit;
            $rsPago = '';
            foreach ($rsPagos as  $value_1) {
                $rsPago = $rsPago.','.$value_1->tipo_pago;
            } 

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsUtilidad = $this->db->select('SUM(notapd_totalVenta) totalVenta ,SUM(notapd_totalCosto) totalCosto')
                                    ->from('nota_pedido_detalle')
                                    ->where('notapd_notap_id',$value->notap_id)
                                    ->group_by('notapd_notap_id')
                                    ->get()
                                    ->row();   

            if($value->notap_estado != 1){
                $value->notapd_total = '0.00';
                $rsUtilidad->totalVenta = 0.00;
                $rsUtilidad->totalCosto = 0.00;

            }

            //CONDICION PARA MOSTRAR ESTADOS 29-03-2021
            if ($value->notap_estado == 1) {
                $value->notap_estado = 'GENERADO';
            }else if($value->notap_estado == 2){
                $value->notap_estado =  'ANULADO';
            }else if($value->notap_estado == 3){
                $value->notap_estado =  'TRIBUTARIO';
            }                                                                        


            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.$i, $value->notap_correlativo)
                        ->setCellValue('B'.$i, $value->notap_fecha)
                        ->setCellValue('C'.$i, $value->apellido_paterno." ".$value->apellido_materno.", ".$value->nombre)
                        ->setCellValue('D'.$i, $tipo_dcli)
                        ->setCellValue('E'.$i, $rsCliente->ruc)
                        ->setCellValue('F'.$i, $rsCliente->razon_social)
                        ->setCellValue('G'.$i, $value->prod_codigo)
                        ->setCellValue('H'.$i, $value->cat_nombre)
                        ->setCellValue('I'.$i, $value->medida_nombre)
                        ->setCellValue('J'.$i, $value->notapd_descripcion)
                        ->setCellValue('K'.$i, $value->notapd_precio_unitario)
                        ->setCellValue('L'.$i, $value->notapd_cantidad)                    
                        ->setCellValue('M'.$i, $value->notapd_total)
                        ->setCellValue('N'.$i, $value->moneda)
                        ->setCellValue('O'.$i, $rsPago)
                        ->setCellValue('P'.$i, $rsUtilidad->totalVenta - $rsUtilidad->totalCosto)
                        ->setCellValue('Q'.$i, $rsTransportista->transp_nombre)
                        ->setCellValue('R'.$i, $rsCliente->placa)
                        ->setCellValue('S'.$i, $this->session->userdata('almacen_nom'))
                        ->setCellValue('T'.$i, $value->notap_estado)
                        ->setCellValue('U'.$i, $value->notap_observaciones);  


            if($value->notap_moneda_id == 1){//SOLES
                    $suma_totalSoles = $suma_totalSoles + $value->notapd_total;
            } else if($value->notap_moneda_id == 2){//DOLARES
                    $suma_totalDolares = $suma_totalDolares + $value->notapd_total;
            }                        
            $i++;             
        }

         $objPHPExcel->getActiveSheet()->getStyle('L' . ($i +1 ))->getFont()->setBold(true);
         $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i +1 ), 'TOTAL SOLES');
         $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i +1 ), $suma_totalSoles);

         $objPHPExcel->getActiveSheet()->getStyle('L' . ($i + 2 ))->getFont()->setBold(true);
         $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i + 2 ), 'TOTAL DOLARES');
         $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i + 2 ), $suma_totalDolares);


        $filename = 'Nota_pedido---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


     public function exportarExcel_rd()
    {
        require_once (APPPATH .'libraries/Numletras.php');

        
        if($_GET['cliente']!='undefined'){
            $this->db->where('notap_cliente_id',$_GET['cliente']);
        }  
       
        if($_GET['fecha_inicio'] != 'null'){
            $fecha_inicio =  (new DateTime($_GET['fecha_inicio']))->format('Y-m-d');
            $this->db->where("DATE(com.notap_fecha) >=", $fecha_inicio);
        }

        if($_GET['fecha_fin'] != 'null'){
            $fecha_fin =  (new DateTime($_GET['fecha_fin']))->format('Y-m-d');
            $this->db->where("DATE(com.notap_fecha) <=", $fecha_fin);
        }

        if($_GET['correlativo']!='null'){        
            $this->db->where('notap_correlativo',$_GET['correlativo']);
        }

        if($_GET['vendedor_id'] != '')
            $this->db->where('emp.id',$_GET['vendedor_id']);        

        if($this->session->userdata('accesoEmpleado') != '')
            $this->db->where('emp.id',$this->session->userdata('empleado_id')); 


        $this->db->where('com.notap_almacen', $this->session->userdata('almacen_id'));        
        $resultComprobantes = $this->db->from("nota_pedido com")                                                                                                               
                                       ->join("empleados emp","emp.id=com.notap_empleado_insert")       
                                       ->join("monedas mon","mon.id = com.notap_moneda_id")
                                       ->order_by('com.notap_id DESC')
                                       ->get()
                                       ->result();        

        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $i=2;                                    

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Detalle');
        $objPHPExcel->addSheet($myWorkSheet, 1);
        $objPHPExcel->setActiveSheetIndex(1);


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
         

        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
     
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "CORRELATIVO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "FECHA"); 
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "VENDEDOR"); 
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "DOCUMENTO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "NUM. DOC.");      
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "CLIENTE");         
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "IMPORTE TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "MONEDA");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "TIPO PAGO");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "UTILIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "TRANSPORTISTA");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "PLACA");        
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "ALMACEN");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "ESTADO");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "OBSERVACION");

        $suma_totalSoles = 0;
        $suma_totalDolares = 0;
        foreach ($resultComprobantes as $value) {           

            /*datos cliente*/
            $this->db->where('id', $value->notap_cliente_id);
            $this->db->from('clientes');
            $queryCliente = $this->db->get();
            $rsCliente = $queryCliente->row();

            // tipo documento cliente
            $tipo_dcli = 'DNI';
            if (strlen($rsCliente->ruc)>8) {
                $tipo_dcli = 'RUC';
            }            

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsTransportista = $this->db->from('transportistas')
                                        ->where('transp_id', $value->notap_transportista_id)
                                        ->get()
                                        ->row();            
            
            //ALEXANDER FERNANDEZ 01-11-2020
            $rsPagos = $this->db->select('tpg.tipo_pago')
                               ->from('nota_pagos cmp')
                               ->join('tipo_pagos tpg','tpg.id = cmp.tipo_pago_id')
                               ->where('cmp.nota_id', $value->notap_id)
                               ->get()
                               ->result();
                               //var_dump($rsPago);exit;
            $rsPago = '';
            foreach ($rsPagos as  $value_1) {
                $rsPago = $rsPago.','.$value_1->tipo_pago;
            } 

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsUtilidad = $this->db->select('SUM(notapd_totalVenta) totalVenta ,SUM(notapd_totalCosto) totalCosto')
                                    ->from('nota_pedido_detalle')
                                    ->where('notapd_notap_id',$value->notap_id)
                                    ->group_by('notapd_notap_id')
                                    ->get()
                                    ->row();

            if($value->notap_estado != 1){//TRIBUTARIO O ANULADO
                $value->notap_total = '0.00';
                $rsUtilidad->totalVenta = 0.00;
                $rsUtilidad->totalCosto = 0.00;
            }      

            //CONDICION PARA MOSTRAR ESTADOS 29-03-2021
            if ($value->notap_estado == 1) {
                $value->notap_estado = 'GENERADO';
            }else if($value->notap_estado == 2){
                $value->notap_estado =  'ANULADO';
            }else if($value->notap_estado == 3){
                $value->notap_estado =  'TRIBUTARIO';
            }

            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.$i, $value->notap_correlativo)
                        ->setCellValue('B'.$i, $value->notap_fecha)
                        ->setCellValue('C'.$i, $value->apellido_paterno." ".$value->apellido_materno.", ".$value->nombre)
                        ->setCellValue('D'.$i, $tipo_dcli)
                        ->setCellValue('E'.$i, $rsCliente->ruc)
                        ->setCellValue('F'.$i, $rsCliente->razon_social)                        
                        ->setCellValue('G'.$i, $value->notap_total)
                        ->setCellValue('H'.$i, $value->moneda)                        
                        ->setCellValue('I'.$i, $rsPago)
                        ->setCellValue('J'.$i, $rsUtilidad->totalVenta - $rsUtilidad->totalCosto)
                        ->setCellValue('K'.$i, $rsTransportista->transp_nombre)
                        ->setCellValue('L'.$i, $rsCliente->placa)
                        ->setCellValue('M'.$i, $this->session->userdata('almacen_nom'))
                        ->setCellValue('N'.$i, $value->notap_estado)
                        ->setCellValue('O'.$i, $value->notap_observaciones);

            if($value->notap_moneda_id == 1){//SOLES
                    $suma_totalSoles = $suma_totalSoles + $value->notap_total;
            } else if($value->notap_moneda_id == 2){//DOLARES
                    $suma_totalDolares = $suma_totalDolares + $value->notap_total;
            }                        
            $i++;            
        }

         $objPHPExcel->getActiveSheet()->getStyle('F' . ($i +1 ))->getFont()->setBold(true);
         $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i +1 ), 'TOTAL SOLES');
         $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i +1 ), $suma_totalSoles);

         $objPHPExcel->getActiveSheet()->getStyle('F' . ($i +2 ))->getFont()->setBold(true);
         $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i +2 ), 'TOTAL DOLARES');
         $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i +2 ), $suma_totalDolares);


        $filename = 'Nota_pedido---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function exportarExcelFormatoVendedor(){
        
        if($_GET['cliente']!='undefined'){
            $this->db->where('notap_cliente_id',$_GET['cliente']);
        }                  
       
        $fecha_inicio = '';
        $fecha_fin = '';
        $label_inicio = '';
        $label_fin = '';
        if(($_GET['fecha_inicio']!='null') && ($_GET['fecha_fin']!='null')) {
            $fecha_inicio = (new DateTime($_GET['fecha_inicio']))->format("Y-m-d");          
            $fecha_fin = (new DateTime($_GET['fecha_fin']))->format("Y-m-d");          
            $this->db->where('DATE_FORMAT(notap_fecha, "%Y-%m-%d") >= ', $fecha_inicio);
            $this->db->where('DATE_FORMAT(notap_fecha, "%Y-%m-%d") <= ', $fecha_fin);
            
            $label_inicio = 'Fecha Desde:';
            $label_fin = 'Fecha Hasta:';
        } 

        if($_GET['correlativo']!='null'){        
            $this->db->where('notap_correlativo',$_GET['correlativo']);
        }
        
        //CONDICION SI USUARIO ES VENDEDOR        
        $vendedor_id = ($this->session->userdata('tipo_empleado_id') == 20) ? $this->session->userdata('empleado_id') : $_GET['vendedor_id'];           
        
        $datos = $this->empleados_model->reporteVendedor($vendedor_id, $fecha_inicio, $fecha_fin, $cliente_id);        
         
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");                                            

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Detalle');
        $objPHPExcel->addSheet($myWorkSheet, 1);
        $objPHPExcel->setActiveSheetIndex(1);
     
        $objPHPExcel->getActiveSheet()->setCellValue('A1', $label_inicio);
        $objPHPExcel->getActiveSheet()->setCellValue('B1', $fecha_inicio);
        $objPHPExcel->getActiveSheet()->setCellValue('A2', $label_fin);
        $objPHPExcel->getActiveSheet()->setCellValue('B2', $fecha_fin);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A4', "N."); 
        $objPHPExcel->getActiveSheet()->setCellValue('B4', "VENDEDOR"); 
        $objPHPExcel->getActiveSheet()->setCellValue('C4', "CANTIDAD"); 
        $objPHPExcel->getActiveSheet()->setCellValue('D4', "UNIDAD"); 
        $objPHPExcel->getActiveSheet()->setCellValue('E4', "PRODUCTO"); 
        
        $i=5;
        foreach ($datos as $value) {           

            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.$i, $i - 1)
                        ->setCellValue('B'.$i, $value['apellido_paterno']." ".$value['apellido_materno'].", ".$value['nombre'])
                        ->setCellValue('C'.$i, $value['cantidad'])
                        ->setCellValue('D'.$i, $value['medida_nombre'])
                        ->setCellValue('E'.$i, $value['prod_nombre']);
            $i++;
        }

        $filename = 'Reporte Vendedor---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function decargarPdf_ticket($idNota){
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("clientes as cli", "np.notap_cliente_id=cli.id")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos as tpg", "np.notap_tipopago_id=tpg.id")
                           ->join('transportistas tra','np.notap_transportista_id = tra.transp_id')
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();
                           //var_dump($rsNota);exit;

        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id','left') 
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;                           



        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('nota_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.nota_id', $idNota)
                                ->get()
                                ->result();        

        $rsNota->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente)

        //ALEXANDER FERNANDEZ DE LA CRUZ 30-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "pagoMonto" =>  $rsPagoMonto,
                    "cliente" => $rsCliente,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/nota_ticket.php",$data,true); 
        
//        $this->load->library('pdfgenerator');
//        $filename = 'comprobante_pago';
//        $this->pdfgenerator->generate($html, $filename, true,'A4','portrait');
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idNota.pdf",
            array("Attachment"=>0)
        );
    }


    public function comprobanteTributario(){
    
    $rsNota = $this->notas_model->select($this->uri->segment(3));    

    //var_dump($rsNota);
    $cabecera = array();
    //VALIDANDO QUE NO VIENE UNA BUSQUEDA 0 DEVELVE STOCK, 1 NO DEVUELE
    $cabecera['notap_id'] = ($this->uri->segment(4) == 1) ? '' : $rsNota->notap_id;            
    $tipo_cliente_id = $rsNota->tipo_cliente_id;
    //P.NATURAL,PJURIDICA
    if($tipo_cliente_id == 1) $data['tipo_documento_id'] = 3;
    if($tipo_cliente_id == 2) $data['tipo_documento_id'] = 1;
    
    $cabecera['cliente_id'] = $rsNota->notap_cliente_id;
    $cabecera['cliente_razon_social'] = $rsNota->razon_social;
    $cabecera['cliente_domicilio'] = $rsNota->notap_cliente_direccion;
    $cabecera['moneda_id']  = $rsNota->notap_moneda_id;
    $cabecera['total_a_pagar'] = $rsNota->notap_total;
    $cabecera['comprobante_anticipo'] = 0;
    $cabecera['observaciones'] = $rsNota->notap_observaciones;
    $cabecera['almacen_id'] = $rsNota->notap_almacen; 
    $cabecera['transportista_id'] = $rsNota->notap_transportista_id;    
    $cabecera['tipo_pago_id'] = $rsNota->notap_tipopago_id;
    $cabecera['placa'] = $rsNota->placa;

    //var_dump($rsNota);exit;
    $data['comprobante'] = $cabecera;

    $items = array();
    foreach ($rsNota->detalles as $value) {                
                                        
            $item['descripcion'] = $value->notapd_descripcion;
            $item['producto_id'] = $value->notapd_producto_id;
            $item['cantidad'] = $value->notapd_cantidad;
            $item['tipo_igv_id'] =  1;
            $item['importe'] = $value->notapd_precio_unitario;
            $item['importeCosto'] = $value->notapd_importeCosto;
            $item['total'] = $value->notapd_total;
            $item['totalCosto'] = $value->notapd_totalCosto;
            $item['totalVenta'] = $value->notapd_totalVenta;
            $items[] = $item;                    
    }

    $data['items'] = $items;
    $data['tipo_igv'] = $this->tipo_igv_model->select();
    $data['monedas']  = $this->monedas_model->select();
    $data['tipo_documentos'] = $this->tipo_documentos_model->select();    
    $data['empresa'] = $this->empresas_model->select(1);
    $data['transportistas'] = $this->transportistas_model->select();
    $data['tipo_pagos'] = $this->tipo_pagos_model->select();
    $data['medida'] = $this->medida_model->select();
    $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();


    $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();


        $this->load->view('templates/header_administrador');
        $this->load->view('comprobantes/generarComprobante',$data);
        $this->load->view('templates/footer');
    }


    /* DETALLE DE CAMBIO      / AUTOR               / FECHA
     *-----------------------------------------------------------   
     * GUARDAR CLIENTE        / ALEXADER FERNANDEZ / 14-04-2021 */

    public function comprobanteNotaVenta(){

    $rsNota = $this->notas_model->select($this->uri->segment(3));    
    $cabecera = array();
    
    //$cabecera['prof_id'] = $this->uri->segment(3);
    $cabecera['cliente_id'] = $rsNota->notap_cliente_id;
    $cabecera['cliente_razon_social'] = $rsNota->razon_social;
    $cabecera['cliente_domicilio'] = $rsNota->notap_cliente_direccion;
    $cabecera['moneda_id']  = $rsNota->notap_moneda_id;
    $cabecera['total_a_pagar'] = $rsNota->notap_total;
    $cabecera['comprobante_anticipo'] = 0;
    $cabecera['observaciones'] = $rsNota->notap_observaciones;
    
    $data['nota'] = $cabecera;
    $data['consecutivo'] = $this->notas_model->maximoConsecutivo()+1;
   
    $items = array();
    foreach ($rsNota->detalles as $value) {
                                        
            $item['descripcion'] = $value->notapd_descripcion;
            $item['producto_id'] = $value->notapd_producto_id;
            $item['unidad_id'] = $value->notapd_unidad_id;
            $item['cantidad']  = $value->notapd_cantidad;
            $item['tipo_igv_id'] = 1;
            $item['importe'] = $value->notapd_precio_unitario;
            $item['importeCosto'] = $value->notapd_importeCosto;
            $item['total'] = $value->notapd_total;
            $item['totalCosto'] = $value->notapd_totalCosto;
            $item['totalVenta'] = $value->notapd_totalVenta;
            $items[] = $item;
    }    

    $data['items'] = $items;
    $data['tipo_igv'] = $this->tipo_igv_model->select();
    $data['monedas']  = $this->monedas_model->select();
    $data['medida']   = $this->medida_model->select();
    $data['tipo_documentos'] = $this->tipo_documentos_model->select();    
    $data['empresa'] = $this->empresas_model->select(1);
    $data['transportistas'] = $this->transportistas_model->select();
    $data['tipo_pagos']   = $this->tipo_pagos_model->select();
    $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();

    $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();
        $this->load->view('templates/header_administrador');
        $this->load->view('notas/generarNotaVenta',$data);
        $this->load->view('templates/footer');
    }

    public function sanear_string($string) {

        $string = trim(utf8_encode($string));
//        $string = str_replace(
//            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
//            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
//            $string
//        );
        $string = str_replace(
                array('à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

//        $string = str_replace(
//            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
//            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
//            $string
//        );
        $string = str_replace(
                array('è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

//        $string = str_replace(
//            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
//            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
//            $string
//        );
        $string = str_replace(
                array('ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

//        $string = str_replace(
//            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
//            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
//            $string
//        );
        $string = str_replace(
                array('ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

//        $string = str_replace(
//            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
//            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
//            $string
//        );        
        $string = str_replace(
                array('ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

//        $string = str_replace(
//            array('ñ', 'Ñ', 'ç', 'Ç'),
//            array('n', 'N', 'c', 'C',),
//            $string
//        );
        $string = str_replace(
                array('ç', 'Ç'), array('c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
//        $string = str_replace(
//            array("\\", "¨", "º", "-", "~",
//                 "#", "@", "|", "!", "\"",
//                 "·", "$", "%", "&", "/",
//                 "(", ")", "?", "'", "¡",
//                 "¿", "[", "^", "`", "]",
//                 "+", "}", "{", "¨", "´",
//                 ">", "< ", ";", ",", ":",
//                 ".", " "),
//            '',
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "|", "!", "\"",
            "·", "&", "/",
            "(", ")", "'", "¡",
            "¿", "[", "^", "`", "]",
            "}", "{", "¨", "´"
                ), '', $string
        );
        $string = str_replace(
                array("\n"
                ), ' ', $string
        );
        return $string;
    }  


public function data_impresion_pos_ticket($idNota)
  {
        //echo $idNota;exit;
        $this->load->library('numletras');
        
        $nota_id = $idNota;        
        
        $rsEmpresa =  $this->db->from('empresas')
                               ->get()
                               ->row();
        $rsNota = $this->notas_model->select($idNota);

        //total a pagar en letras
        $num = new Numletras();
        $totalVenta = explode(".",$rsNota->notap_total);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 ';
        $rsNota->total_letras = $totalLetras;
               

        $tipo_documento = 'NOTA PEDIDO';
        $data['tipo_documento'] = 'NOTA PEDIDO';

        $documento ='';
        if (strlen($rsNota->ruc) == 8) {
            $documento ="DNI";
        } else {
            $documento ="RUC";
        }
        
        $connector = new WindowsPrintConnector("POSS");//$connector = new NetworkPrintConnector("192.168.1.50", 9100);        
        $printer = new Printer($connector);
        $moneda = $rsNota->simbolo;

        /*imprimeir imagen
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $logo = EscposImage::load("C:/xampp/htdocs/victor/images/logo3.png", false);
            $imgModes = array(
                Printer::IMG_DEFAULT,                
            );
            foreach ($imgModes as $mode) {
                $printer->bitImage($logo, $mode);
            }
        } catch (Exception $e) {/* $printer->text($e->getMessage() . "\n");  }*/

        try {

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setFont(Printer::FONT_B);          
                       

            //$printer->text("\n" . $rsEmpresa->empresa . "\n");
            //$printer->text("\n" . "RUC " . $rsEmpresa->ruc . "\n");
            //$printer->text("\n" . $rsEmpresa->domicilio_fiscal . "\n");
            $printer->text($rsEmpresa->empresa . "\n");
            $printer->text("******************************************************" . "\n");
            
            $printer->setFont(Printer::FONT_A); 
            $printer->text($data['tipo_documento']."\n");
            $printer->text("NP"."-".$rsNota->notap_correlativo."\n");
            $printer->text("\n");
            
            $printer->setFont(Printer::FONT_B); 
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            date_default_timezone_set("America/Lima");
            $printer->text("Fecha Emisión: ");
            $printer->text($rsNota->notap_fecha."        Hora Emisión: ".date("h:i A") . "\n");
            $printer->text("Responsable: ");
            $printer->text($this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno')."\n");

            $printer->text("****************************************************************" . "\n");
            $printer->text("Cliente: ");
            $printer->text($rsNota->razon_social."\n");
            $printer->text("Tipo Documento: ");
            $printer->text($documento."  ".$rsNota->ruc."\n");
            $printer->text("Dirección: ");
            $printer->text($rsNota->domicilio1."\n");
            $printer->text("****************************************************************" . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //$printer->text("CANT    DESCRIPCION          P/U            SUBTOTAL.\n");
            $printer->text("DESCRIPCION PRODUCTO                                PRECIO TOTAL\n");
            $printer->text("****************************************************************" . "\n");

            /* A partir de aca se imprimen los productos */                    
            /*Alinear a la izquierda para la cantidad y el nombre*/
            
            $desc = 0;
            $tipopago ="";
            $fila = 1;
            foreach ($rsNota->detalles as $row) {

                if($fila != 1){
                  $printer->text("------------------------------------------------------" . "\n");
                }
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                //if($row->notapd_producto_id != 0){
                //    $prod_codigo = $row->prod_codigo'];
                //}else{
                //    $prod_codigo = "00000";
                //}
                
            
                $printer->text($row->notapd_descripcion.'  '.$row->notapd_unidad_id);
                $printer->text("\n"); 
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $subtotal = $row->notapd->total;
               
                $printer->text($row->notapd_cantidad.' x '.number_format($row->notapd_precio_unitario,2).'  '.number_format($row->notapd_total,2));

                $desc += number_format($row->notapd_descuento,2);
                //$tipopago = $row['tipo_pago'];
                $printer->text("\n");
                $fila++;            
            }

            
            $printer->text("****************************************************************" . "\n");            
            
            
            $printer->text("IMPORTE TOTAL :");
            for($i = strlen(number_format($rsNota->notap_total,2)); $i < 20; $i++ ){
                $printer->text(" ");
            }            
            $printer->text($moneda." ". number_format($rsNota->notap_total,2) . "\n");
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("SON: ".$rsNota->total_letras. " ".$rsNota->moneda ."\n");

            
            $printer->text("Forma de pago: ");           
            //$printer->text($tipopago."  ");
            $printer->text($moneda." ". number_format($rsNota->notap_total,2) . "\n");
            $printer->text(" "."\n");                       
            
            /*$printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($certificado."\n");
            $printer->text("\n");            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA !  \n");*/
            //$printer->feed(1);
            $printer->cut();
            $printer->pulse();
            $printer->close();
                                    
        } finally {
            $printer->close();
        }
        //sendJsonData(['status'=>STATUS_OK,]);
        //exit(); 
    }

    public function ticket_auto($valor){        
        $this->db->set('ticket_auto',$valor);
        $this->db->update('comprobantes_ventas');
        redirect(base_url() . "index.php/notas/index/" . 1);
    }

}