<?php
class Notas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('clientes_model');
    }

    public function select($id = '', $activo = '')
    {
        $rsNota = $this->db->select("np.*,cli.*,mon.*,em.nombre as vendedor_nombre,em.apellido_paterno as vendedor_apellido")
                           ->from("nota_pedido as np")
                           ->join("clientes as cli", "np.notap_cliente_id=cli.id")
                           ->join("empleados as em", "np.notap_vendedor=em.id")
                           ->join("monedas mon","np.notap_moneda_id = mon.id")
                           ->where("np.notap_id", $id)
                           ->get()
                           ->row();
                                                           
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format('d-m-Y'); 
        //obtenemos los detalles
        $rsDetalles = $this->db->from("nota_pedido_detalle")
                               ->where("notapd_notap_id", $id)
                               ->get()
                               ->result();
        $rsNota->detalles = $rsDetalles;       

        //echo count($rsDetalles);exit;
        return $rsNota;                   
    }  

    public function guardarNota()
    {      
      $mostrar_imagen = ($_POST['mostrar_imagen'] == 'on') ? 1 : 0;
         //REGISTRO DE CLIENTE API   
         if($_POST['cliente_id'] == 'jApi'){ //REGISTRA CLIENTE RUC
                $this->db->where('ruc',$_POST['ruc_sunat']);
                $dato_sunat_cliente = $this->db->get('clientes')->row();
                if(empty($dato_sunat_cliente->ruc)){
                    $id = $this->clientes_model->obtener_codigo();
                    $data = array(
                        'id' => $id,
                        'ruc' => $_POST['ruc_sunat'],
                        'razon_social' => strtoupper($_POST['razon_sunat']),
                        'domicilio1' => strtoupper($_POST['direccion']),                        
                        'empresa_id' => 1,
                        'activo' => 'activo',
                        'empleado_id_insert' => $this->session->userdata('empleado_id'),
                        'tipo_cliente_id' => 2,
                        'tipo_cliente' => 'Persona Jurídica'
                    );
                    $this->db->insert('clientes',$data);
                    $_POST['cliente_id'] = $id;
                }else{
                    $_POST['cliente_id'] = $dato_sunat_cliente->id; 
                }        
         } else if($_POST['cliente_id'] == 'nApi'){//REGISTRA CLIENTE DNI
                $this->db->where('ruc',$_POST['ruc_sunat']);
                $dato_sunat_cliente = $this->db->get('clientes')->row();
                if(empty($dato_sunat_cliente->ruc)){
                    $id = $this->clientes_model->obtener_codigo();
                    $data = array(
                        'id' => $id,
                        'ruc' => $_POST['ruc_sunat'],
                        'razon_social' => strtoupper($_POST['razon_sunat']),
                        'domicilio1' => strtoupper($_POST['direccion']),
                        'empresa_id' => 1,
                        'activo' => 'activo',
                        'empleado_id_insert' => $this->session->userdata('empleado_id'),
                        'tipo_cliente_id' => 1,
                        'tipo_cliente' => 'Persona Natural'
                    );
                    $this->db->insert('clientes',$data);
                    $_POST['cliente_id'] = $id;
                }else{
                    $_POST['cliente_id'] = $dato_sunat_cliente->id; 
                }
         }

        //ACTUALIZACION DE PLACA PARA EL CLIENTE 17/08/2020
        $this->db->where('id', $_POST['cliente_id']);
        $this->db->update("clientes", array('placa' => $_POST['placa'] ));


        //FORMATEAMOS FECHA DE EMISION
        $minutos = new DateTime();
        $fecha_de_emision = new DateTime($_POST['fecha']);
        $fecha_de_emision = $fecha_de_emision->format('Y-m-d')." ".$minutos->format('H:i:s');


        
        if($_POST['notaId'] == '')
        {
            $correlativo = $this->maximoConsecutivo();
            $dataInsert['notap_correlativo'] = $correlativo+1;
            $dataInsert['notap_fecha'] = $fecha_de_emision;
            $dataInsert['notap_cliente_id'] = $_POST['cliente_id'];
            $dataInsert['notap_tipo_cambio'] = $_POST['tipo_de_cambio'];
            $dataInsert['notap_cliente_direccion'] = $_POST['direccion'];
            $dataInsert['notap_moneda_id'] = $_POST['moneda_id'];
            $dataInsert['notap_subtotal'] = $_POST['total_gravada'];
            $dataInsert['notap_igv'] = $_POST['total_igv'];
            $dataInsert['notap_total'] = $_POST['total_a_pagar'];
            $dataInsert['notap_tipopago_id'] = $_POST['tipo_pago'];
            $dataInsert['notap_transportista_id'] = $_POST['transportista'];            
            $dataInsert['notap_observaciones'] = $_POST['observaciones'];
            $dataInsert['notap_estado'] = ST_NOTA_ACTIVA;
            $dataInsert['notap_empleado_insert'] =$this->session->userdata('empleado_id');
            $dataInsert['notap_descontar'] = $_POST['descontar_stock'];
            $dataInsert['notap_cambio'] = $_POST['cambio'];
            $dataInsert['notap_vendedor'] = $this->session->userdata('empleado_id');            
            $dataInsert['notap_almacen'] = $this->session->userdata('almacen_id');
            $dataInsert['notap_mostrar_imagen'] = $mostrar_imagen;
            $this->db->insert("nota_pedido", $dataInsert);
            $idNota = $this->db->insert_id();
        }else{
            $dataUpdate['notap_fecha'] = $fecha_de_emision; 
            $dataUpdate['notap_cliente_id'] = $_POST['cliente_id'];
            $dataUpdate['notap_tipo_cambio'] = $_POST['tipo_de_cambio']; 
            $dataUpdate['notap_cliente_direccion'] = $_POST['direccion'];  
            $dataUpdate['notap_moneda_id'] = $_POST['moneda_id'];  
            $dataUpdate['notap_subtotal'] = $_POST['total_gravada'];  
            $dataUpdate['notap_igv'] = $_POST['total_igv']; 
            $dataUpdate['notap_total'] = $_POST['total_a_pagar']; 
            $dataUpdate['notap_tipopago_id'] = $_POST['tipo_pago'];            
            $dataUpdate['notap_transportista_id'] = $_POST['transportista'];
            $dataUpdate['notap_observaciones'] = $_POST['observaciones']; 
            $dataUpdate['notap_descontar'] = $_POST['descontar_stock'];
            $dataUpdate['notap_cambio'] = $_POST['cambio'];
            $dataUpdate['notap_mostrar_imagen'] = $mostrar_imagen;
            //$dataUpdate['notap_vendedor'] = $this->session->userdata('empleado_id');
            $this->db->where("notap_id", $_POST['notaId']);
            $this->db->update("nota_pedido", $dataUpdate);
            $idNota = $_POST['notaId'];       

            if($_POST['descontar_stock']==1){
                /*primero liberamos los prodcutos con sus respectivos ejemplares*/        
                $rsDetalles = $this->db->from("nota_pedido_detalle")
                                       ->where("notapd_notap_id", $idNota)
                                       ->get()
                                       ->result();
                $i = 0;
                foreach($rsDetalles as $item) {

                  $result = $this->db->from('productos')
                               ->where('prod_id',$item->notapd_producto_id)
                               ->get()
                               ->row();
                  if($result->prod_tipo == 1 ){
                      $this->productos_model->ingresarStock($item->notapd_producto_id ,$item->notapd_cantidad, 'RESTAURAR', $idNota, 'NP', $_POST['numero'], $result->prod_almacen_id, 3);
                    }              
                } 
            }
           $this->db->where("notapd_notap_id", $idNota);
           $this->db->delete('nota_pedido_detalle');

           //DELETE NOTA_PAGO
           $this->db->where("nota_id", $idNota);
           $this->db->delete('nota_pagos');                        
        }    

        //INSERTAR COMPROBANTES PAGOS 16-10-2020
        $tipo_pagoMonto = $_POST['tipo_pagoMonto'];
        $importe_pagoMonto  = $_POST['importe_pagoMonto'];
        $observacion_pagoMonto = $_POST['observacion_pagoMonto'];
       //guardamos la fecha de pago
        $cuota_pagoMonto = $_POST['cuota_pagoMonto'];
       
        $minutos = new DateTime();
        $pagoMonto = array();
        $i=0;
        foreach ($tipo_pagoMonto as $value) {
          if ($cuota_pagoMonto[$i]!= "") {
           $fecha_cuota= new DateTime($cuota_pagoMonto[$i]);
           $fecha_cuota = $fecha_cuota->format('Y-m-d')." ".$minutos->format('H:i:s');
         }else{
          $fecha_cuota= NULL;
         }
            $pagoMonto['nota_id'] = $idNota;
            $pagoMonto['tipo_pago_id'] = $tipo_pagoMonto[$i];
            $pagoMonto['monto'] = $importe_pagoMonto[$i];
            $pagoMonto['observaciones'] = $observacion_pagoMonto[$i];
            $pagoMonto['fecha_cuota'] = $fecha_cuota;


            $this->db->insert('nota_pagos',$pagoMonto);
            $i++;               
        }
        
        //ingresamos los detalle
        $cantidadIngresos = count($_POST['descripcion']);
        for($i=0;$i<$cantidadIngresos;$i++)
        {

             $result = $this->db->from('productos')
                               ->where('prod_id',$_POST['item_id'][$i])
                               ->get()
                               ->row();
            
            $prod_nombre = (empty($result)) ? $_POST['descripcion'][$i] : $result->prod_nombre;
            $prod_medida_id = (empty($result)) ? $_POST['medida'][$i] : $result->prod_medida_id;
            $dataInsertDetalle = [
                                    "notapd_descripcion"     => $prod_nombre,
                                    "notapd_unidad_id"       => $prod_medida_id,
                                    "notapd_producto_id"     => $_POST['item_id'][$i],
                                    "notapd_cantidad"        => $_POST['cantidad'][$i],
                                    "notapd_tipo_igv"        => $_POST['tipo_igv'][$i],
                                    "notapd_precio_unitario" => $_POST['importe'][$i],
                                    "notapd_importeCosto"    => $_POST['importeCosto'][$i],
                                    "notapd_descuento"       => $_POST['descuento'][$i],
                                    "notapd_igv"             => $_POST['igv'][$i],
                                    "notapd_subtotal"        => $_POST['subtotal'][$i],
                                    "notapd_total"           => $_POST['total'][$i],
                                    "notapd_totalVenta"      => $_POST['totalVenta'][$i],
                                    "notapd_totalCosto"      => $_POST['totalCosto'][$i],
                                    "notapd_notap_id"        => $idNota,
                                  ];

            $this->db->insert("nota_pedido_detalle", $dataInsertDetalle);

            if($_POST['descontar_stock'] == 1){   
                if($result->prod_tipo == 1){
                  $this->productos_model->salidaStock($_POST['item_id'][$i] ,$_POST['cantidad'][$i], 'VENTA', $idNota, 'NP', $_POST['numero'], $result->prod_almacen_id, 3);
                         
              } 
            }          
        }  
        return $idNota;
    }

    public function UpdateEstadoVendido($idproducto,$cantidad) {
        
        $resultados = $this->db->from('ejemplar')
                               ->where('ejm_producto_id',$idproducto)
                               ->where('ejm_estado',ST_PRODUCTO_DISPONIBLE)
                               ->where('ejm_almacen_id',$this->session->userdata('almacen_id'))
                               ->limit($cantidad)
                               ->get()
                               ->result()
                               ;
        foreach ($resultados as $key => $value) {
            $dataUpdateProducto = [
                        'ejm_estado' => ST_PRODUCTO_VENDIDO
                      ];
            $this->db->where('ejm_id',$value->ejm_id)
                    ->update('ejemplar',$dataUpdateProducto);
        }
        
    } 

    public function UpdateEstadoDisponible($idproducto,$cantidad) {        
        $resultados = $this->db->from('ejemplar')
                               ->where('ejm_producto_id',$idproducto)
                               ->where('ejm_estado',ST_PRODUCTO_VENDIDO)
                               ->where('ejm_almacen_id',$this->session->userdata('almacen_id'))
                               ->limit($cantidad)
                               ->get()
                               ->result();

        foreach ($resultados as $key => $value) {
            $dataUpdateProducto = [
                        'ejm_estado' => ST_PRODUCTO_DISPONIBLE
                      ];
            $this->db->where('ejm_id',$value->ejm_id)
                    ->update('ejemplar',$dataUpdateProducto);
        }
        
    }

    public function eliminar($idProducto)
    {
      $this->db->delete('productos', ['prod_id'=>$idProducto]);
      return true; 
    }   
    public function maximoConsecutivo()
    {
        //obtenemos el maximo consecutivo del las notas
        $select = $this->db->from("nota_pedido")                           
                           ->order_by("notap_id","DESC")                           
                           ->get()
                           ->row();

        $rsMayorConsecutivo = $select->notap_correlativo;
        return $rsMayorConsecutivo;
    }
    
    public function getMainList(){

        if($_POST['vendedor_id'] != '')
            $this->db->where('em.id',$_POST['vendedor_id']);

        if($this->session->userdata('accesoEmpleadoCaja') != '')
            $this->db->where('em.id',$this->session->userdata('empleado_id'));
        
        
        $select = $this->db->select('nota.*,CONCAT(em.nombre," ",em.apellido_paterno) as empleado,cli.*,mon.*',FALSE)
                           ->from("nota_pedido as nota")
                           ->join("empleados as em", "em.id=nota.notap_vendedor")
                           ->join("clientes as cli", "nota.notap_cliente_id=cli.id")
                           ->join("monedas as mon", "nota.notap_moneda_id=mon.id")        
                          // ->where("nota.notap_estado !=", ST_NOTA_ELIMINADA)
                           ->order_by("nota.notap_id", "desc");

        
       if($_POST['cliente_search'] > 0)
        {
            $select->where("nota.notap_cliente_id", $_POST['cliente_search']);
        }
        if($_POST['correlativo_search'] != '')
        {
            $select->where("nota.notap_correlativo", $_POST['correlativo_search']);
        }
        if($_POST['fecha_inicio'] != ''){
            $fecha_inicio =  (new DateTime($_POST['fecha_inicio']))->format('Y-m-d');
            $this->db->where("DATE(nota.notap_fecha) >=", $fecha_inicio);
        }

         if($_POST['fecha_fin'] != ''){
            $fecha_fin =  (new DateTime($_POST['fecha_fin']))->format('Y-m-d');
            $this->db->where("DATE(nota.notap_fecha) <=", $fecha_fin);
        }

        $selecGenerado = clone $select;
        $totalGenerado = count($selecGenerado->where('nota.notap_estado',1)->get()->result());

        $selecAnulado = clone $select;
        $totalAnulado = count($selecAnulado->where('nota.notap_estado',2)->get()->result());

        $selecTributario = clone $select;
        $totalTributario = count($selecTributario->where('nota.notap_estado',3)->get()->result());

        /*obtener el total*/
        $count = clone $select;
        $queryCount = $count->get();
        $rsCount = count($queryCount->result());

        $select->limit($_POST['pageSize'], $_POST['skip']);
        $query = $select->get();
        $rsNotas = $query->result();  

        foreach($rsNotas as $nota)
        {
            $nota->notap_fecha = (new DateTime($nota->notap_fecha))->format("d/m/Y");
            $nota->btn_ticket = '<a href="#"><img src="'.base_url().'/images/pdf.png" data-id="'.$nota->notap_id.'" class="show_pdf_ticket"></a>';
            $nota->btn_ticket_imp = '<a class="btn_ticket_imp" onclick="openWindow('.$nota->notap_id.')"><span class="glyphicon glyphicon-print esunat" aria-hidn="true"></span></a>';
           
            $nota->boton_pdf = '<a href="#"><img src="'.base_url().'/images/pdf.png" data-id="'.$nota->notap_id.'" class="show_pdf"></a>';            

            $nota->boton_popup = '<a href="'.base_url().'index.php/notas/index/'.$nota->notap_id.'"><span class="glyphicon glyphicon-file"></a>';                                                
            $nota->boton_eliminar = '<button class="btn btn btn-danger btn-sm btn-eliminar" data-id="'.$nota->notap_id.'"><i class="glyphicon glyphicon-remove"></i></button>';            


            if($nota->notap_estado == 3){//CONVERTIDO A TRIBURARIO
                $nota->btn_action ='<div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tributario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button></div>';                                     
                $nota->notap_total = '';
                $nota->btn_ticket  = '';
                $nota->boton_pdf   = '';
                $nota->boton_editar   = '';
                $nota->boton_eliminar = '';
                $nota->boton_cTributario = '';          
            } else if($nota->notap_estado == 2){//ANULADO
                $nota->boton_cTributario = '';
                $nota->boton_editar = '';
                $nota->btn_action ='<div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:#E74C3C;border:0;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anulado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button></div>';                                     
            } else{
               $nota->boton_cTributario = '<a href="'.base_url().'index.php/notas/comprobanteTributario/'.$nota->notap_id.'/0"><span class="glyphicon glyphicon-export" data-id="'.$nota->notap_id.'"></a>';
               $nota->boton_editar = '<button class="btn btn-primary btn-sm btn-editar" data-id="'.$nota->notap_id.'"><i class="glyphicon glyphicon-pencil"></i></button>';               
               $nota->btn_action ='<div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:#12AE20;border:0;"> &nbsp;&nbsp;&nbsp;&nbsp;Generado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button><ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">';
               $nota->btn_action .='<li><a onclick="send_anulacionPASSWORD_2('.$nota->notap_id.')" >X Anular </a></li>';
               $nota->btn_action .= '</ul></div>';               
            }  
             $nota->totalGenerado =  '<button class="btn-generado" data-id="'.$totalGenerado.'"><i class="glyphicon glyphicon-pencil"></i></button>';
             $nota->totalAnulado  =  '<button class="btn-Anulado" data-id="'.$totalAnulado.'"><i class="glyphicon glyphicon-pencil"></i></button>';
             $nota->totalTributario  ='<button class="btn-Tributario" data-id="'.$totalTributario.'"><i class="glyphicon glyphicon-pencil"></i></button>';                                                                                                                          
        }      

        $datos = [
              'data' => $rsNotas,
              'rows' => $rsCount
             ];

        return $datos;      
    }

    public function getMainListDetail()
    {

        $select = $this->db->from("nota_pedido_detalle")
                           ->where("notapd_notap_id", $_POST['notap_id']);
        //cantidad de registros
        $selectCount = clone $select;                               
        $rsCount = $selectCount->get()
                               ->row();
        $rsCount = count($rsCount);
        
        $rsDetalle = $select->limit($_POST['pageSize'], $_POST['skip'])
                            ->get()
                            ->result();                       
        $datos = [
                'data' => $rsDetalle,
                'rows' => $rsCount
             ];

        return $datos;       
    }


    //DEVOLVER STOCK
    public function devolverStock($idNota){
        $rsNota = $this->db->from('nota_pedido')
                           ->where('notap_id',$idNota)
                           ->get()
                           ->row();

        $rsProductos = $this->db->from('nota_pedido_detalle npd')
                                ->join("productos pro","pro.prod_id = npd.notapd_producto_id")
                                ->where('npd.notapd_notap_id',$idNota)
                                ->get()
                                ->result();

        if($rsNota->notap_descontar == 1){
          foreach ($rsProductos as $rsProducto) {                                                            
          $this->productos_model->ingresarStock($rsProducto->notapd_producto_id ,$rsProducto->notapd_cantidad, $concepto = 'RESTAURAR STOCK',$comprobante,'NP',$rsNota->notap_correlativo,$rsProducto->prod_almacen_id);
        }}        
    }   

    public function masVendidos(){
        $data['masVendidos'] = $this->productos_model->masVendidos();
        $this->accesos_model->menuGeneral();
        $this->load->view('productos/masVendidos', $data);
        $this->load->view('templates/footer');  
    } 
}