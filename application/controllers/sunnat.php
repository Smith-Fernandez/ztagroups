<?PHP

class Sunnat extends CI_Controller
{	
	 public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');
        $this->load->model('empresas_model');
        $this->load->model('comprobantes_model');
        $this->load->model('tipo_documentos_model');     
        $this->load->library('Sunat');
    }
	
	public function consultaComprobantes(){
		    $data['empresa'] = $this->empresas_model->select(1);
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $this->load->view('templates/header_sin_menu_white');
        $this->load->view('comprobantes/consultaComprobantes',$data);
        $this->load->view('templates/footer_sunat');
    }    

    public function consulta_sunat(){
     $error = array();
        if($_POST['numRuc'] == ''){
            $error['numRuc'] =  'falta ingresar ruc';
        }
        if($_POST['tipo_documento'] == ''){
            $error['tipo_documento'] = 'falta ingresar tipo_documento';
        }
        if($_POST['serie'] == ''){
            $error['serie'] = 'falta ingresar serie';
        }
        if($_POST['numero'] == ''){
            $error['numero'] = 'falta ingresar número';
        }
        if($_POST['fecha_de_emision'] == ''){
            $error['fecha_de_emision'] = 'falta ingresar fecha_de_emision';
        }

        if($_POST['monto'] == ''){
            $error['monto'] = 'falta ingresar monto';
        }        

        if(count($error) > 0){
            $data = ['status' => STATUS_FAIL, 'tipo' => 1, 'errores' => $error];
            echo json_encode($data);
            exit();
        }

    $fecha_de_emision = (new DateTime($_POST['fecha_de_emision']))->format('Y-m-d');
    $arrayComprobante = array("emp.ruc"  => $_POST['numRuc'],
                              "com.serie" => $_POST['serie'],
                              "com.numero" => $_POST['numero'],
                              "DATE_FORMAT(com.fecha_de_emision, '%Y-%m-%d')" => $fecha_de_emision,
                              "com.total_a_pagar" => $_POST['monto'] );

    $comprobante = $this->comprobantes_model->selectCustomizado(2,$arrayComprobante);

    $tableComprobante = '';
    if(!empty($comprobante)){

    $A4 ='<a class="show_pdf" title="ver pdf" href="#" idval="'.$comprobante["comprobante_id"].'"><img title="Ver Pdf" src="'.base_url().'/images/pdf.png"></a>';
    $ticket = '<a class="print_ticket_pdf" idval="'.$comprobante["comprobante_id"].'"  target="_blank"><span class="glyphicon glyphicon-print esunat" aria-hidn="true"></span></a>';  

    $enlace_del_xml = '<a class="_dow_xml" target="_blank"  href="'.RUTA_API.'index.php/Sunat/dowload_xml/'.$_POST['numRuc'].'/'.$_POST['tipo_documento'].'/'.$_POST['serie'].'/'.$_POST['numero'].'"><span class="glyphicon glyphicon-file esunat"></span> XML</a>';
    $enlace_del_cdr = '<a class="_dow_cdr" target="_blank" href="'.RUTA_API.'index.php/Sunat/dowload_cdr/'.$_POST['numRuc'].'/'.$_POST['tipo_documento'].'/'.$_POST['serie'].'/'.$_POST['numero'].'"><i class="glyphicon glyphicon-list-alt"></i> CDR</a>';


    $tableComprobante = '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SERIE</th>
                            <th>NUMERO</th>
                            <th>TIDO DOCUMENTO</th>
                            <th>TOTAL A PAGAR</th>
                            <th>A4</th>
                            <th>TICKET</th>
                            <th>XML</th>
                            <th>CDR</th>
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <td>'.$comprobante["comprobante_id"].'</td>
                            <td>'.$comprobante["serie"].'</td>
                            <td>'.$comprobante["numero"].'</td>
                            <td>'.$comprobante["tipo_documento"].'</td>
                            <td>'.$comprobante["total_a_pagar"].'</td>
                            <td>'.$A4.'</td>
                            <td>'.$ticket.'</td>
                            <td>'.$enlace_del_xml.'</td>
                            <td>'.$enlace_del_cdr.'</td>
                        </tr>    
                    </tbody>
                    </table>';
   }                                   
                    //print_r($tableComprobante);exit;
  $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api-seguridad.sunat.gob.pe/v1/clientesextranet/e8bcca68-0760-44e6-a6b7-7bbad7d0800d/oauth2/token/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials&scope=https%3A//api.sunat.gob.pe/v1/contribuyente/contribuyentes&client_id=e8bcca68-0760-44e6-a6b7-7bbad7d0800d&client_secret=eKiZA6rxoNuJnIjABPjx7Q%3D%3D",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            //"Cookie: TS019e7fc2=014dc399cbf45599ea47f9abf1b0a1eac3fe77bae317fecff5571a78407590bc560657c64eea952a2ef323b8795106219fb5cc3db2"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;exit;
        $response =  explode('"', $response);
        //echo $response[3];exit;

        $fecha_de_emision = (new DateTime($_POST['fecha_de_emision']))->format('d/m/Y');
        $curl_1= curl_init();                   

        $data = array("numRuc"  => $_POST['numRuc'],
                      "codComp" => $_POST['tipo_documento'],
                      "numeroSerie" => $_POST['serie'],
                      "numero" => $_POST['numero'],
                      "fechaEmision" => $fecha_de_emision,
                      "monto" => $_POST['monto']
                );
        //var_dump($data);
        $data_string = json_encode($data);

        curl_setopt_array($curl_1, array(
          CURLOPT_URL => "https://api.sunat.gob.pe/v1/contribuyente/contribuyentes/".$_POST['numRuc']."/validarcomprobante",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $data_string,
          CURLOPT_HTTPHEADER => array(
            "Content-Type:application/json",
            "Authorization: Bearer ".$response[3]           
          ),
        ));

        $response = curl_exec($curl_1);

        curl_close($curl_1);
        //var_dump($response);exit;
        $response = json_decode($response);

        if($response->success){
            $response->data->estadoCpDes = $this->sunat->estadoComprobante($response->data->estadoCp);
            $response->data->estadoRucDes = $this->sunat->estadoContribuyente($response->data->estadoRuc);
            $response->data->condDomiRucDes  =  $this->sunat->condDomiRuc($response->data->condDomiRuc);
            $response->data->tableComprobante  = $tableComprobante;

            echo json_encode(['status' => STATUS_OK, 'message' =>$response->message, 'result' => $response->data]);
        }else{
            echo json_encode(['status' => STATUS_FAIL,'tipo' => 2,'message' => $response->message]);
        }
  }

}