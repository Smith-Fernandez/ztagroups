<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Proformas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('monedas_model'); 
        $this->load->model('empresas_model');
        $this->load->model('medida_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('tipo_items_model');
        $this->load->model('proformas_model');
        $this->load->model('clientes_model');        
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('transportistas_model');
        $this->load->model('igv_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');        
//
        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index()
    {
        $data['config'] = $this->db->get('comprobantes_ventas')->row();
        $data['empresa'] = $this->empresas_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('proformas/basic_index', $data);
        $this->load->view('templates/footer');      
    }
    public function nuevo()
    {
        $data = array();
        $data['medida'] = $this->medida_model->select();
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['consecutivo'] = $this->proformas_model->maximoConsecutivo();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $this->accesos_model->menuGeneral();
        $this->load->view('proformas/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function editar($idProforma) {
        
        $data['medida'] = $this->medida_model->select();
        $data['proforma'] = $this->proformas_model->select($idProforma);
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select(); 
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $this->accesos_model->menuGeneral();       
        $this->load->view('proformas/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function guardarProforma() {
        $error = array();
        if($_POST['fecha'] == '')
        {
            $error['fecha'] = 'falta ingresar fecha';
        }
        if($_POST['cliente_id'] == '')
        {
            $error['cliente_id'] = 'falta ingresar Cliente';
        }        
        if($_POST["moneda_id"] == '')
        {
            $error['moneda_id'] = 'falta ingresar moneda';
        }

        if($_POST["direccion"] == '')
        {
            $error['direccion'] = 'falta ingresar dirección';
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

        //guardamos la compra
        $result = $this->proformas_model->guardarProforma();
        
        if($result > 0)
        {
            sendJsonData(['status'=>STATUS_OK]);
            exit();
        }   

    }

    public function eliminar($idProforma)  {
        $result = $this->proformas_model->eliminar($idProforma);
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
    public function estado_igv($valor){
        //$valor = $this->input->get('valor');
        $this->db->set('pu_igv',$valor);
        $this->db->update('comprobantes_ventas');
        redirect(base_url() . "index.php/proformas/index/" . 1);
    }

    public function getMainList()
    {
        $rsDatos = $this->proformas_model->getMainList();    
        sendJsonData($rsDatos);
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->proformas_model->getMainListDetail();

        sendJsonData($rsDatos);        
    }
    public function descargarPdf($idProforma) {
        require_once (APPPATH .'libraries/Numletras.php');
        
        $rsproforma = $this->db->from("proformas as pf")

                             ->join("monedas as mon", "pf.prof_moneda_id=mon.id")
                             ->join("clientes as cli", "pf.prof_cliente_id=cli.id")                             
                             ->where("prof_id", $idProforma)
                             ->get()
                             ->row();
        
        /*formateamos fecha*/
        $rsproforma->prof_doc_fecha = (new DateTime($rsproforma->prof_doc_fecha))->format('d/m/Y');                     

        $rsDetalles =  $this->db->from("proforma_detalle")
                                ->where("profd_prof_id", $idProforma)
                                ->get()
                                ->result();

        $rsproforma->detalles = $rsDetalles;                                     
        $rsempresa = $this->db->from('empresas')
                              ->where('id',1)
                              ->get()
                              ->row();

        $rscliente = $this->db->from('clientes')
                              ->where('id',$rsproforma->prof_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                              ->where("id", $rsproforma->prof_empleado_id)
                              ->get()
                              ->row();

        $rsmoneda = $this->db->from('monedas')
                              ->where('id',$rsproforma->prof_moneda_id)
                              ->get()
                              ->row();

        $rsdetalle = $this->db->from('proforma_detalle as f')
                              ->join('productos as p','p.prod_id = f.profd_prod_id','left')
                              ->join('medida m','m.medida_id = f.profd_unidad_id','left') 
                              ->where('f.profd_prof_id',$rsproforma->prof_id)
                              ->order_by('f.prof_id','ASC')
                              ->get()
                              ->result();

        $num = new Numletras();
        $totalVenta = explode(".",$rsproforma->prof_doc_total);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsmoneda->moneda;
        $rsproforma->total_letras = $totalLetras; 


        $data = [
                    "proforma"    => $rsproforma,
                    "empresa"     => $rsempresa,
                    "cliente"     => $rscliente,
                    "empleado"    => $rsEmpleado,
                    "moneda"      => $rsmoneda,
                    "detalles"      => $rsdetalle
                ];
        $html = $this->load->view("templates/proforma.php",$data,true);       


        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("Cotizacion-$rsproforma->prof_correlativo.pdf",
            array("Attachment"=>0)
        );
        // Cargamos la librería
        //$this->load->library('pdfgenerator');
        // definamos un nombre para el archivo. No es necesario agregar la extension .pdf
        //$filename = 'comprobante_pago';
        // generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel
        //$this->pdfgenerator->generate($html, $filename, true,'A4','portrait');            
        /*escribimos archivo*/
        /*$archivo = 'PROFORMA-'.$rsproforma->prof_correlativo;
        $rutaArchivoHtml = FCPATH.'files\pdf\\'.$archivo.'.html';
        $rutaArchivoPdf = FCPATH.'files\pdf\\'.$archivo.'.pdf';
        $file = fopen($rutaArchivoHtml,'w');
        fwrite($file, $html);
        fclose($file);
        /*convertimos el html en pdf*/
        /*exec('"'.FCPATH. 'wk\bin\wkhtmltopdf" '.$rutaArchivoHtml.' '.$rutaArchivoPdf);
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$archivo.'.pdf"');
        readfile($rutaArchivoPdf);
        /*aliminamos archivos creados html, pdf*/
        /*unlink($rutaArchivoHtml);
        unlink($rutaArchivoPdf);   */                     
        
        
    }

    public function buscadorCliente() {
        $abogado = $this->input->get('term');
        echo json_encode($this->clientes_model->selectAutocomplete($abogado, 'activo'));
    }

    public function ExportarExcel($idproveedor='',$correlatio='',$fecha='',$documento='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('pf.prof_cliente_id', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('pf.prof_doc_fecha', $this->uri->segment(4));
        }
        if($this->uri->segment(5)!='0') {
            $this->db->where('pf.prof_doc_numero', $this->uri->segment(5));
        }

        $this->db->where('pf.prof_estado',ST_ACTIVO);

        $result = $this->db->from("proformas pf")
                 ->join("clientes c","pf.prof_cliente_id=c.id")
                 ->join("monedas m","pf.prof_moneda_id=m.id")
                 ->get()
                 ->result();


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
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'CLIENTE')
                ->setCellValue('C1', 'DOCUMENTO')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'MONEDA')
                ->setCellValue('F1', 'SUBTOTAL')
                ->setCellValue('G1', 'IGV')
                ->setCellValue('H1', 'TOTAL');

        $spreadsheet->getActiveSheet()->setTitle('proformas');

        foreach ($result as $value) {
            $fecha = (new DateTime($value->prof_doc_fecha))->format('d/m/Y');
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->prof_id)
                        ->setCellValue('B'.$i, $value->razon_social)
                        ->setCellValue('C'.$i, $value->prof_doc_numero)
                        ->setCellValue('D'.$i, $fecha)
                        ->setCellValue('E'.$i, $value->moneda)
                        ->setCellValue('F'.$i, $value->prof_doc_subtotal)
                        ->setCellValue('G'.$i, $value->prof_doc_igv)
                        ->setCellValue('H'.$i, $value->prof_doc_total);
            $i++; 
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')                        
                        ->setCellValue('B'.$i, 'PRODUCTO')
                        ->setCellValue('C'.$i, 'PRECIO UNITARIO')
                        ->setCellValue('D'.$i, 'SUB TOTAL')
                        ->setCellValue('E'.$i, 'CANTIDAD'); 

            $dataCompras = $this->db->from('proforma_detalle pfd')
                                   ->where('pfd.profd_prof_id',$value->prof_id)
                                   ->get()
                                   ->result();
         
            $i++;
            foreach ($dataCompras as $val) {              
                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, $val->profd_descripcion)
                        ->setCellValue('C'.$i, $val->profd_precio_unitario)
                        ->setCellValue('D'.$i, $val->profd_cantidad)
                        ->setCellValue('E'.$i, $val->profd_subtotal);
                $i++;
            }

            $spreadsheet->getActiveSheet()->mergeCells('A'.$i.':H'.$i);

            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_proformas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }

    public function decargarPdf_ticket($idProforma){
        $rsProforma = $this->db->from("proformas as pro")
                           ->join("monedas as mon", "pro.prof_moneda_id=mon.id")
                           ->where("prof_id", $idProforma)
                           ->get()
                           ->row();
                           //var_dump($rsNota);exit;

        /*formateamos fecha*/
        $rsProforma->prof_doc_fecha = (new DateTime($rsProforma->prof_doc_fecha))->format("d/m/Y h:i:s");
        $rsDetalles =  $this->db->from("proforma_detalle as f")
                                ->join('productos as p','p.prod_id=f.profd_prod_id','left') 
                           
                           ->where("f.profd_prof_id", $idProforma)
                           ->get()
                           ->result(); 

        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*23;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;                                                        

        $rsProforma->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsProforma->prof_cliente_id)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente)
 
        $data = [
                    "empresa" => $rsEmpresa,
                    "proforma"    => $rsProforma,
                    "cliente" => $rsCliente,
                ];
        $html = $this->load->view("templates/proforma_ticket.php",$data,true); 
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("Proforma.NP-$idProforma.pdf",
            array("Attachment"=>0)
        );
    }



    public function comprobanteTributario(){
    
    $rsProforma = $this->proformas_model->select($this->uri->segment(3));

    //var_dump($rsNota);
    $cabecera = array();
    
    $tipo_cliente_id = $rsProforma->tipo_cliente_id;
    //P.NATURAL,PJURIDICA
    if($tipo_cliente_id == 1) $data['tipo_documento_id'] = 3;
    if($tipo_cliente_id == 2) $data['tipo_documento_id'] = 1;
    
    $cabecera['cliente_id'] = $rsProforma->prof_cliente_id;
    $cabecera['cliente_razon_social'] = $rsProforma->razon_social;
    $cabecera['moneda_id']  = $rsProforma->prof_moneda_id;
    $cabecera['total_a_pagar'] = $rsProforma->prof_doc_total;
    $cabecera['comprobante_anticipo'] = 0;
    $cabecera['observaciones'] = $rsProforma->prof_doc_observaciones;
    //$cabecera['almacen_id'] = 1; 
    //$cabecera['tipo_pago_id'] = 1;
    //var_dump($rsNota);exit;
    $data['comprobante'] = $cabecera;

    $items = array();
    foreach ($rsProforma->detalles as $value) {                
                                        
            $item['descripcion'] = $value->profd_descripcion;
            $item['producto_id'] = $value->profd_prod_id;
            $item['unidad_id'] = $value->profd_unidad_id;
            $item['cantidad'] = $value->profd_cantidad;
            $item['tipo_igv_id'] =  1;
            $item['importe'] = $value->profd_precio_unitario;
            $item['importeCosto'] = $value->profd_importeCosto;
            $item['total'] = $value->profd_total;
            $item['totalCosto'] = $value->profd_totalCosto;
            $item['totalVenta'] = $value->profd_totalVenta;
            $items[] = $item;                    
    }    

    $data['items'] = $items;
    $data['tipo_igv'] = $this->tipo_igv_model->select();
    $data['monedas']  = $this->monedas_model->select();
    $data['tipo_documentos'] = $this->tipo_documentos_model->select();    
    $data['empresa'] = $this->empresas_model->select(1);
    $data['medida'] = $this->medida_model->select();
    $data['transportistas'] = $this->transportistas_model->select();
    $data['tipo_pagos'] = $this->tipo_pagos_model->select();
    $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();


    $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();


        $this->load->view('templates/header_administrador');
        $this->load->view('comprobantes/generarComprobante',$data);
        $this->load->view('templates/footer');
    }   

}