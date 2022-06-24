<?PHP

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reportes extends CI_Controller
{
	
	public function __construct()
	{
		date_default_timezone_set('America/Lima');
		parent::__construct();
		date_default_timezone_set('America/Lima');
        $this->load->model('comprobantes_model');
        $this->load->model('items_model');
        $this->load->model('igv_model');
        $this->load->model('icbper_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');        
        $this->load->model('accesos_model');
        $this->load->model('clientes_model');
        $this->load->model('monedas_model');
        $this->load->model('empleados_model');
        $this->load->model('empresas_model');
        $this->load->model('tipo_cambio_model');
        $this->load->model('ser_nums_model');
        $this->load->model('comprobante_anulados_model');
        $this->load->model('cuentas_model');
        $this->load->model('variables_diversas_model');
        $this->load->model('ticket_model');
        $this->load->model('productos_model');
        $this->load->model('categoria_model');
        $this->load->model('medida_model');
        $this->load->model('resumenes_model');
        $this->load->model('tipo_clientes_model');
        $this->load->model('cajas_model');
        $this->load->model('almacenes_model');
        $this->load->model('notas_model');
        $this->load->model('transportistas_model');
        $this->load->model('reportes_model');
        $this->load->library('pdf');
	}
	

	public function index(){

		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_utilidadesEmpresa', $data);
		$this->load->view('templates/footer');
	}

	public function reporte_utilidadesEmpresa(){		

		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_utilidadesEmpresa', $data);
		$this->load->view('templates/footer');
	}



	public function reporte_utilidadesEmpresa_bs(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();


		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);


		//var_dump($results);exit;
		$contenido ='<table class="table table-streap">
						 <tr>
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th>CANTIDAD</th>
							<th>TOTAL VENTAS</th>
							<th>TOTAL COMPRAS</th>
							<th>UTILIDAD</th>
							<th>COMISION NETA</th>
							<th>UTILIDAD NETA</th>
						</tr>';


		$rsTotal_venta = 0;
		$rsTotal_compra = 0;
		$rsTotal_utilidad = 0;
		$rsTotal_comision = 0;
		$rsTotal_utilidadNeta = 0;		
		foreach($reporteTotal  as $value) { 		
			$contenido .=	'<tr>
								<td>'.$value['prod_codigo'].'</td>
								<td>'.$value['prod_nombre'].'</td>
								<td>'.$value['cantidad'].'</td>
								<td>'.$value['total_venta'].'</td>
								<td>'.$value['total_compra'].'</td>
								<td>'.$value['utilidad'].'</td>
								<td>'.$value['comision'].'</td>
								<td>'.$value['utilidadNeta'].'</td>
							</tr>';

				$rsTotal_venta += $value['total_venta'];
				$rsTotal_compra += $value['total_compra'];
				$rsTotal_utilidad += $value['utilidad'];
				$rsTotal_comision += $value['comision'];
				$rsTotal_utilidadNeta += $value['utilidadNeta'];
		 }

		 	$contenido .= '<tr>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>TOTAL</td>
		 					<td>'.$rsTotal_venta.'</td>
		 					<td>'.$rsTotal_compra.'</td>
		 					<td>'.$rsTotal_utilidad.'</td>
		 					<td>'.$rsTotal_comision.'</td>
		 					<td>'.$rsTotal_utilidadNeta.'</td>
		 					</tr>';

			$contenido .= '</table>';
		echo $contenido;
	}


	public function reporte_comisionVendedor(){

		$data['vendedores'] = $this->empleados_model->select2(3);
		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_comisionVendedor',$data);
		$this->load->view('templates/footer');
	}

	public function reporte_comisionVendedor_bs(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();

		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);
		//var_dump($rsReporte_nv);exit;
		
		//var_dump($results);exit;
		$contenido ='<table class="table table-streap">
						 <tr>
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th>CANTIDAD</th>
							<th>IMPORTE NETO</th>
							<th>COMISION %</th>
							<th>COMISION S/ NETA</th>							
						</tr>';


		$rsTotal_venta = 0;				
		$rsTotal_utilidad = 0;

		foreach($reporteTotal  as $value) { 		
			$contenido .=	'<tr>
								<td>'.$value['prod_codigo'].'</td>
								<td>'.$value['prod_nombre'].'</td>
								<td>'.$value['cantidad'].'</td>
								<td>'.$value['total_venta'].'</td>
								<td>'.$value['comision_porcentaje'].'</td>
								<td>'.$value['comision'].'</td>								
							</tr>';

				$rsTotal_venta += $value['total_venta'];
				$rsTotal_comision += $value['comision'];
		 }

		 $contenido .= '<tr>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>TOTAL</td>
		 					<td>'.$rsTotal_venta.'</td>
		 					<td>&nbsp;</td>
		 					<td>'.$rsTotal_comision.'</td>
		 				</tr>';


		 $contenido .= '</table>';
		echo $contenido;
	}


	public function reporte_liquidacionReparto(){


		$data['vendedores'] = $this->empleados_model->select2(3);
		$data['transportistas'] = $this->transportistas_model->select();

		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_liquidacionReparto',$data);
		$this->load->view('templates/footer');	
	}

	public function reporte_liquidacionReparto_bs(){


		$rsReporte_ct = $this->reportes_model->reporte_liquidacionReparto_ct();
		$rsReporte_nv = $this->reportes_model->reporte_liquidacionReparto_nv();
		

		$reporteTotal = $this->reporte_liquidacionReparto_format($rsReporte_nv,$rsReporte_ct);
		
		$contenido ='<table class="table table-streap">
						 <tr>
							<th>FECHA DE EMISION</th>
							<th>NUMSER</th>
							<th>CLIENTE</th>
							<th>VENDEDOR</th>
							<th>TIPO PAGO</th>
							<th>SUBTOTAL</th>
							<th>TOTAL IGV</th>
							<th>TOTAL A PAGAR</th>							
						</tr>';
		$i=0;
	
		
		$sumTotal_subTotal = 0;
		$sumTotal_igv = 0;
		$sumTotal_total_a_pagar = 0;
		foreach($reporteTotal  as $key => $value) { 
			$subtotal = 0;
			$total_igv = 0;
			$total_a_pagar = 0;			

			 $rowKey = implode(array_keys($value));
			 //var_dump($row);			 
			 $contenido .=  '<tr><td colspan="8">'.$key.' '.$rowKey.'</td></tr>';
			
			foreach ($value[$rowKey] as $value_1) {

				//var_dump($value_1);exit();				
			$contenido .=	'<tr>								
								<td class="col-sm-1">'.$value_1['fecha_de_emision'].'</td>
								<td class="col-sm-1">'.$value_1['numser'].'</td>
								<td class="col-sm-3">'.$value_1['cliente_razon_social'].'</td>
								<td class="col-sm-2">'.$value_1['vendedor'].'</td>
								<td class="col-sm-2">'.$value_1['tipo_pago'].'</td>
								<td class="col-sm-1">'.$value_1['subtotal'].'</td>
								<td class="col-sm-1">'.$value_1['total_igv'].'</td>
								<td class="col-sm-1">'.$value_1['total_a_pagar'].'</td>								
							</tr>';

			    $subtotal += $value_1['subtotal'];
				$total_igv += $value_1['total_igv'];
				$total_a_pagar += $value_1['total_a_pagar'];
			$i++;	
		 }			 
		 $contenido .=  '<tr>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td><b>TOTAL: '.strtoupper($key).' SERIE '.$rowKey.'</b></td>
		 					<td><b>'.$subtotal.'</b></td>
		 					<td><b>'.$total_igv.'</b></td>
		 					<td><b>'.$total_a_pagar.'</b></td>
		 				</tr>';
		 $contenido .= '</table><br><table class="table table-streap">';	 
		 	$sumTotal_subTotal += $subtotal;
			$sumTotal_igv += $total_igv;
			$sumTotal_total_a_pagar += $total_a_pagar;
		}


		$contenido .=  '<tr>
		 					<td class="col-sm-1">&nbsp;</td>
		 					<td class="col-sm-1">&nbsp;</td>
		 					<td class="col-sm-3">&nbsp;</td>
		 					<td class="col-sm-2">&nbsp;</td>
		 					<td class="col-sm-2"><b>TOTALES VENTAS:</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_subTotal.'</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_igv.'</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_total_a_pagar.'</b></td>
		 				</tr>';	
			$contenido .= '</table>';
		echo $contenido;		
	}


	public function reporte_repartoTransportista(){

		$data['transportistas'] = $this->transportistas_model->select();
		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_repartoTransportista',$data);
		$this->load->view('templates/footer');		
	}

	public function reporte_repartoTransportista_bs(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();

		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);		
		
		//var_dump($results);exit;
		$contenido ='<table class="table table-streap">
						 <tr>
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th>CANTIDAD</th>									
						</tr>';

		foreach($reporteTotal  as $value) { 		
			$contenido .=	'<tr>
								<td>'.$value['prod_codigo'].'</td>
								<td>'.$value['prod_nombre'].'</td>
								<td>'.$value['cantidad'].'</td>															
							</tr>';
		 }
			$contenido .= '</table>';
		echo $contenido;	
        
    }



	public function reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct){


		$arrayTotal = array_merge($rsReporte_nv,$rsReporte_ct);
		//var_dump($arrayTotal);exit;		
			//$a = array();
			$results = array();
			foreach ($arrayTotal as $value)
			{
				$rsProducto = $this->productos_model->select($value['prod_id']);

			     if(!isset($results[$value['prod_id']]) )
			     {
			     	$results[$value['prod_id']]['prod_nombre'] = '';
			     	$results[$value['prod_id']]['prod_codigo'] = '';
			        $results[$value['prod_id']]['cantidad'] = 0;
			        $results[$value['prod_id']]['total_venta'] = 0;
			        $results[$value['prod_id']]['total_compra'] = 0;
			        $results[$value['prod_id']]['utilidad'] = 0;
			        $results[$value['prod_id']]['comision'] = 0;
			     }

			     $results[$value['prod_id']]['prod_nombre']  = $value['prod_nombre'];
			     $results[$value['prod_id']]['prod_codigo']  = $value['prod_codigo'];
			     $results[$value['prod_id']]['cat_nombre']  = $value['cat_nombre'];
			     $results[$value['prod_id']]['lin_nombre']  = $value['lin_nombre'];
			     $results[$value['prod_id']]['mar_nombre']  = $value['mar_nombre'];
			     $results[$value['prod_id']]['cantidad']    += $value['cantidad'];
			     $results[$value['prod_id']]['total_venta'] += $value['total_venta'];
			     $results[$value['prod_id']]['total_compra']+= $value['cantidad']*$rsProducto->prod_precio_compra;

			     $utilidad = $value['total_venta'] - $value['cantidad']*$rsProducto->prod_precio_compra;
			     $results[$value['prod_id']]['utilidad'] += $utilidad;

			     $results[$value['prod_id']]['comision_porcentaje'] = $rsProducto->prod_comision_vendedor;

			     $comision = $value['total_venta']*($rsProducto->prod_comision_vendedor/100);
			     $results[$value['prod_id']]['comision'] += $comision;
			     $results[$value['prod_id']]['utilidadNeta'] += $utilidad - $comision;
			}

		$reporteTotal = $results;
		return $reporteTotal;

	}



	public function reporte_liquidacionReparto_format($rsReporte_nv,$rsReporte_ct){		

		$arrayTotal = array_merge($rsReporte_nv,$rsReporte_ct);

		$array = array();
		$i=0;
		foreach ($arrayTotal as $value) {
			
			$array[$value['tipo_documento']][$value['serie']][$i]['fecha_de_emision'] =  $value['fecha_de_emision'];
			$array[$value['tipo_documento']][$value['serie']][$i]['cliente_razon_social'] =  $value['cliente_razon_social'];
			$array[$value['tipo_documento']][$value['serie']][$i]['vendedor'] =  $value['vendedor'];
			$array[$value['tipo_documento']][$value['serie']][$i]['numser'] =  $value['numser'];
			$array[$value['tipo_documento']][$value['serie']][$i]['serie'] =  $value['serie'];
			$array[$value['tipo_documento']][$value['serie']][$i]['numero'] =  $value['numero'];
			$array[$value['tipo_documento']][$value['serie']][$i]['tipo_pago'] =  $value['tipo_pago'];
			$array[$value['tipo_documento']][$value['serie']][$i]['total_igv'] =  $value['total_igv'];
			$array[$value['tipo_documento']][$value['serie']][$i]['total_a_pagar'] =  $value['total_a_pagar'];
			$array[$value['tipo_documento']][$value['serie']][$i]['total_costo'] =  $value['total_costo'];
			$array[$value['tipo_documento']][$value['serie']][$i]['utilidad'] =  $value['total_a_pagar'] - $value['total_costo'];			

			$subtotal = $value['total_a_pagar'] - $value['total_igv'];
			$array[$value['tipo_documento']][$value['serie']][$i]['subtotal'] =  $subtotal;


			$i++;
		}

		//var_dump($array);exit();
		$reporteTotal = $array;

		return $reporteTotal;
	}	


	public function reporte_totalVentas(){

		$data['vendedores'] = $this->empleados_model->select2(3);
		$data['transportistas'] = $this->transportistas_model->select();

		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_totalVentas',$data);
		$this->load->view('templates/footer');

	}


	public function reporte_totalVentas_bs(){

		$rsReporte_ct = $this->reportes_model->reporte_liquidacionReparto_ct();
		$rsReporte_nv = $this->reportes_model->reporte_liquidacionReparto_nv();
		

		$reporteTotal = $this->reporte_liquidacionReparto_format($rsReporte_nv,$rsReporte_ct);
		
		$contenido ='<table class="table table-streap">
						 <tr>
							<th>FECHA DE EMISION</th>
							<th>NUMSER</th>
							<th>CLIENTE</th>
							<th>VENDEDOR</th>
							<th>TIPO PAGO</th>
							<th>SUBTOTAL</th>
							<th>TOTAL IGV</th>
							<th>TOTAL VENTA</th>							
							<th>TOTAL COSTO</th>
							<th>UTILIDAD</th>
						</tr>';
		$i=0;
	
		
		$sumTotal_subTotal = 0;
		$sumTotal_igv = 0;
		$sumTotal_total_a_pagar = 0;
		$sumTotal_total_costo = 0;
		$sumTotal_utilidad = 0;
		foreach($reporteTotal  as $key => $value) { 
			$subtotal = 0;
			$total_igv = 0;
			$total_a_pagar = 0;			
			$total_costo = 0;
			$utilidad = 0;

			 $rowKey = implode(array_keys($value));
			 //var_dump($row);			 
			 $contenido .=  '<tr><td colspan="10">'.$key.' '.$rowKey.'</td></tr>';
			
			foreach ($value[$rowKey] as $value_1) {

				//var_dump($value_1);exit();				
			$contenido .=	'<tr>								
								<td class="col-sm-1">'.$value_1['fecha_de_emision'].'</td>
								<td class="col-sm-1">'.$value_1['numser'].'</td>
								<td class="col-sm-2">'.$value_1['cliente_razon_social'].'</td>
								<td class="col-sm-2">'.$value_1['vendedor'].'</td>
								<td class="col-sm-1">'.$value_1['tipo_pago'].'</td>
								<td class="col-sm-1">'.$value_1['subtotal'].'</td>
								<td class="col-sm-1">'.$value_1['total_igv'].'</td>
								<td class="col-sm-1">'.$value_1['total_a_pagar'].'</td>								
								<td class="col-sm-1">'.$value_1['total_costo'].'</td>
								<td class="col-sm-1">'.$value_1['utilidad'].'</td>
							</tr>';

			    $subtotal += $value_1['subtotal'];
				$total_igv += $value_1['total_igv'];
				$total_a_pagar += $value_1['total_a_pagar'];
				$total_costo   += $value_1['total_costo'];
				$utilidad   += $value_1['utilidad'];
			$i++;	
		 }			 
		 $contenido .=  '<tr>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td><b>TOTAL: '.strtoupper($key).' SERIE '.$rowKey.'</b></td>
		 					<td><b>'.$subtotal.'</b></td>
		 					<td><b>'.$total_igv.'</b></td>
		 					<td><b>'.$total_a_pagar.'</b></td>
		 					<td><b>'.$total_costo.'</b></td>
		 					<td><b>'.$utilidad.'</b></td>
		 				</tr>';
		 $contenido .= '</table><br><table class="table table-streap">';	 
		 	$sumTotal_subTotal += $subtotal;
			$sumTotal_igv += $total_igv;
			$sumTotal_total_a_pagar += $total_a_pagar;
			$sumTotal_total_costo += $total_costo;
			$sumTotal_utilidad += $utilidad;
		}


		$contenido .=  '<tr>
		 					<td class="col-sm-1">&nbsp;</td>
		 					<td class="col-sm-1">&nbsp;</td>
		 					<td class="col-sm-2">&nbsp;</td>
		 					<td class="col-sm-2">&nbsp;</td>
		 					<td class="col-sm-1"><b>TOTALES VENTAS:</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_subTotal.'</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_igv.'</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_total_a_pagar.'</b></td>		 					
		 					<td class="col-sm-1"><b>'.$sumTotal_total_costo.'</b></td>
		 					<td class="col-sm-1"><b>'.$sumTotal_utilidad.'</b></td>
		 				</tr>';	
			$contenido .= '</table>';
		echo $contenido;		
	}


	public function reporte_stockMinimo(){

		$data['categoria']  = $this->categoria_model->select();
		$data['vendedores'] = $this->empleados_model->select2(3);
		$data['transportistas'] = $this->transportistas_model->select();

		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_stockMinimo',$data);
		$this->load->view('templates/footer');
	}


	public function reporte_stockMinimo_bs(){	
		$reporteTotal = $this->reportes_model->reporte_stockMinimo();
		
		//var_dump($results);exit;
		$contenido ='<table class="table table-streap">
						 <tr>
						 	<th>CATEGORIA</th>
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th>CANTIDAD</th>	
							<th>STOCK MINIMO</th>
						</tr>';

		foreach($reporteTotal  as $value) { 		
			$contenido .=	'<tr>
								<td>'.$value['cat_nombre'].'</td>
								<td>'.$value['prod_codigo'].'</td>
								<td>'.$value['prod_nombre'].'</td>
								<td>'.$value['prod_stock'].'</td>
								<td>'.$value['prod_cantidad_minima'].'</td>
							</tr>';
		 }
			$contenido .= '</table>';
		echo $contenido;	
	}

	//ALEXANDER FERNANDEZ 06-10-2020
	public function reporte_ventasCategoria(){

		$data['vendedores'] = $this->empleados_model->select2(3);
		$data['almacenes'] = $this->almacenes_model->select();
		$this->load->view('templates/header_administrador');
		$this->load->view('reportes/reporte_ventasCategoria',$data);
		$this->load->view('templates/footer');
	}

	public function reporte_ventasCategoria_bs(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();


		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);


		//var_dump($results);exit;
		$contenido ='<table class="table table-streap">
						 <tr>
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th>CATEGORIA</th>
							<th>LINEA</th>
							<th>MARCA</th>
							<th>CANTIDAD</th>
							<th>TOTAL VENTAS</th>							
						</tr>';


		$rsTotal_venta = 0;
		$rsTotal_compra = 0;
		$rsTotal_utilidad = 0;
		$rsTotal_comision = 0;
		$rsTotal_utilidadNeta = 0;		
		foreach($reporteTotal  as $value) { 		
			$contenido .=	'<tr>
								<td>'.$value['prod_codigo'].'</td>
								<td>'.$value['prod_nombre'].'</td>
								<td>'.$value['cat_nombre'].'</td>
								<td>'.$value['lin_nombre'].'</td>
								<td>'.$value['mar_nombre'].'</td>
								<td>'.$value['cantidad'].'</td>
								<td>'.$value['total_venta'].'</td>													
							</tr>';

				$rsTotal_cantidad += $value['cantidad'];
				$rsTotal_venta  += $value['total_venta'];
				$rsTotal_compra += $value['total_compra'];
											
		 }

		 	$contenido .= '<tr>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>
		 					<td>TOTAL</td>
		 					<td>&nbsp;</td>
		 					<td>&nbsp;</td>		 					
		 					<td>'.$rsTotal_cantidad.'</td>
		 					<td>'.$rsTotal_venta.'</td>
		 					</tr>';

			$contenido .= '</table>';
		echo $contenido;
		
	}


	public function exportarReporteUtilidad(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();
		//var_dump($rsReporte_nv);exit;


		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);


		$fecha_desde = $_GET['fecha_desde'];		
		$fecha_hasta = $_GET['fecha_hasta'];

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
        
        $i=6;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

       
    

        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);



        $spreadsheet->setActiveSheetIndex(0)                
                ->setCellValue('B1', 'REPORTE UTILIDADES');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta);             


        $spreadsheet->getActiveSheet()
                ->setCellValue('A5', 'CODIGO')
                ->setCellValue('B5', 'DESCRIPCION')
                ->setCellValue('C5', 'CANTIDAD')
                ->setCellValue('D5', 'TOTAL VENTAS')
                ->setCellValue('E5', 'TOTAL COMPRAS')
                ->setCellValue('F5', 'UTILIDAD')
                ->setCellValue('G5', 'COMISION NETA')
                ->setCellValue('H5', 'UTILIDAD NETA');               


        $rsTotal_venta = 0;
		$rsTotal_compra = 0;
		$rsTotal_utilidad = 0;
		$rsTotal_comision = 0;
		$rsTotal_utilidadNeta = 0;	
		foreach($reporteTotal  as $value) { 	
		 $spreadsheet->getActiveSheet()
		 				->setCellValue('A'.$i, $value['prod_codigo'])
                        ->setCellValue('B'.$i, $value['prod_nombre'])
                        ->setCellValue('C'.$i, $value['cantidad'])
                        ->setCellValue('D'.$i, $value['total_venta'])
                        ->setCellValue('E'.$i, $value['comision_porcentaje'])
                        ->setCellValue('F'.$i, $value['utilidad'])
                        ->setCellValue('G'.$i, $value['comision'])
                        ->setCellValue('H'.$i, $value['utilidadNeta']);                                                
			

				$rsTotal_venta += $value['total_venta'];
				$rsTotal_compra += $value['total_compra'];
				$rsTotal_utilidad += $value['utilidad'];
				$rsTotal_comision += $value['comision'];
				$rsTotal_utilidadNeta += $value['utilidadNeta'];
				$i++;
		 }		

		 $spreadsheet->getActiveSheet()		 				
                        ->setCellValue('C'.$i, 'TOTAL')
                        ->setCellValue('D'.$i, $rsTotal_venta)
                        ->setCellValue('E'.$i, $rsTotal_compra)
                        ->setCellValue('F'.$i, $rsTotal_utilidad)
                        ->setCellValue('G'.$i, $rsTotal_comision)
                        ->setCellValue('H'.$i, $rsTotal_utilidadNeta);           

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

	public function exportarComisionVendedor(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();

		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);		



		$fecha_desde = $_GET['fecha_desde'];		
		$fecha_hasta = $_GET['fecha_hasta'];
		$vendedorText = $_GET['vendedorText'];

		$spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i = 7;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
         

        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
       


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    

        $spreadsheet->setActiveSheetIndex(0)                
                ->setCellValue('B1', 'COMISION VENDEDOR');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta)
                ->setCellValue('A4', 'VENDEDOR')
                ->setCellValue('B4', $vendedorText);


        $spreadsheet->getActiveSheet()
                ->setCellValue('A6', 'CODIGO')
                ->setCellValue('B6', 'DESCRIPCION')
                ->setCellValue('C6', 'CANTIDAD')
                ->setCellValue('D6', 'IMPORTE NETO')
                ->setCellValue('E6', 'COMISION %')
                ->setCellValue('F6', 'COMISION S/ NETA');
                                         
               // ->setCellValue('E1', 'EMPRESA');

        $rsTotal_venta = 0;				
		$rsTotal_utilidad = 0;        
		foreach($reporteTotal  as $value) { 	
		 $spreadsheet->getActiveSheet()
		 				->setCellValue('A'.$i, $value['prod_codigo'])
                        ->setCellValue('B'.$i, $value['prod_nombre'])
                        ->setCellValue('C'.$i, $value['cantidad'])
                        ->setCellValue('D'.$i, $value['total_venta'])
                        ->setCellValue('E'.$i, $value['comision_porcentaje'])
                        ->setCellValue('F'.$i, $value['comision']);   

                $rsTotal_venta += $value['total_venta'];
				$rsTotal_comision += $value['comision'];                                            
			$i++;
		 }

		 $spreadsheet->getActiveSheet()		 				
                        ->setCellValue('C'.$i, 'TOTAL')
                        ->setCellValue('D'.$i, $rsTotal_venta)
                        ->setCellValue('E'.$i, '')
                        ->setCellValue('F'.$i, $rsTotal_comision);                    

			
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

	public function exportarLiquidacion(){

		$rsReporte_ct = $this->reportes_model->reporte_liquidacionReparto_ct();
		$rsReporte_nv = $this->reportes_model->reporte_liquidacionReparto_nv();

		$reporteTotal = $this->reporte_liquidacionReparto_format($rsReporte_nv,$rsReporte_ct);


		//var_dump($reporteTotal);exit();


		$fecha_desde = $_GET['fecha_desde'];		
		$fecha_hasta = $_GET['fecha_hasta'];
		$vendedorText = $_GET['vendedorText'];
		$transportistaText = $_GET['transportistaText'];

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
        
        $i=8;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

       
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->setActiveSheetIndex(0)                
             	->setCellValue('B1', 'LIQUIDACIÓN REPARTO');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta)
                ->setCellValue('A4', 'VENDEDOR')
                ->setCellValue('B4', $vendedorText)
                ->setCellValue('A5', 'TRANSPORTISTA')
                ->setCellValue('B5', $transportistaText);


        $spreadsheet->getActiveSheet()
                ->setCellValue('A7', 'FECHA DE EMISIÓN')
                ->setCellValue('B7', 'NUMSER')
                ->setCellValue('C7', 'CLIENTE')
                ->setCellValue('D7', 'VENDEDOR')
                ->setCellValue('E7', 'TIPO_PAGO')
                ->setCellValue('F7', 'SUBTOTAL')
                ->setCellValue('G7', 'TOTAL_IGV')
                ->setCellValue('H7', 'TOTAL A PAGAR');                
        

        $sumTotal_subTotal = 0;
		$sumTotal_igv = 0;
		$sumTotal_total_a_pagar = 0;


        foreach($reporteTotal  as $key => $value) { 	

        	$subtotal = 0;
			$total_igv = 0;
			$total_a_pagar = 0;

			$rowKey = implode(array_keys($value));			
			//FILA CABECERA			
			$spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $key.' '.$rowKey);
            $i++;
			foreach ($value[$rowKey] as $value_1) {								
				$spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value_1['fecha_de_emision'])
                        ->setCellValue('B'.$i, $value_1['numser'])
                        ->setCellValue('C'.$i, $value_1['cliente_razon_social'])
                        ->setCellValue('D'.$i, $value_1['vendedor'])
                        ->setCellValue('E'.$i, $value_1['tipo_pago'])
                        ->setCellValue('F'.$i, $value_1['subtotal'])
                        ->setCellValue('G'.$i, $value_1['total_igv'])
                        ->setCellValue('H'.$i, $value_1['total_a_pagar']);

                $subtotal += $value_1['subtotal'];
				$total_igv += $value_1['total_igv'];
				$total_a_pagar += $value_1['total_a_pagar'];

				$i++;
			}

			$spreadsheet->getActiveSheet()                        
                        ->setCellValue('E'.$i, 'TOTAL: '.strtoupper($key).' SERIE '.$rowKey)
                        ->setCellValue('F'.$i, $subtotal)
                        ->setCellValue('G'.$i, $total_igv)
                        ->setCellValue('H'.$i, $total_a_pagar);
        
            //FILA DE SUBTOTALES            		
		 	$sumTotal_subTotal += $subtotal;		 	
			$sumTotal_igv += $total_igv;
			$sumTotal_total_a_pagar += $total_a_pagar;
                        
			$i++;
		 }
		 //FILA TOTALES
		 $spreadsheet->getActiveSheet()                        
                        ->setCellValue('E'.$i, 'TOTAL VENTAS')
                        ->setCellValue('F'.$i, $sumTotal_subTotal)
                        ->setCellValue('G'.$i, $sumTotal_igv)
                        ->setCellValue('H'.$i, $sumTotal_total_a_pagar);

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


	public function exportarRepartoTransportista(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();

		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);				

		$fecha_desde = $_GET['fecha_desde'];		
		$fecha_hasta = $_GET['fecha_hasta'];		
		$transportistaText = $_GET['transportistaText'];

		$spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i=7;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
         

        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
       


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    
    	$spreadsheet->setActiveSheetIndex(0)                
             	->setCellValue('B1', 'REPARTO TRANSPORTISTA');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta)                
                ->setCellValue('A4', 'TRANSPORTISTA')
                ->setCellValue('B4', $transportistaText);


        $spreadsheet->getActiveSheet()
                ->setCellValue('A6', 'CODIGO')
                ->setCellValue('B6', 'DESCRIPCION')
                ->setCellValue('C6', 'CANTIDAD');                        
                                         
               // ->setCellValue('E1', 'EMPRESA');
		foreach($reporteTotal  as $value) { 	
		 $spreadsheet->getActiveSheet()
		 				->setCellValue('A'.$i, $value['prod_codigo'])
                        ->setCellValue('B'.$i, $value['prod_nombre'])
                        ->setCellValue('C'.$i, $value['cantidad']);                        
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


	public function exportarTotalVentas(){

		$rsReporte_ct = $this->reportes_model->reporte_liquidacionReparto_ct();
		$rsReporte_nv = $this->reportes_model->reporte_liquidacionReparto_nv();

		$reporteTotal = $this->reporte_liquidacionReparto_format($rsReporte_nv,$rsReporte_ct);


		//var_dump($reporteTotal);exit();


		$fecha_desde = $_GET['fecha_desde'];		
		$fecha_hasta = $_GET['fecha_hasta'];
		$vendedorText = $_GET['vendedorText'];
		$transportistaText = $_GET['transportistaText'];

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
        
        $i=8;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

       
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->setActiveSheetIndex(0)                
             	->setCellValue('B1', 'TOTAL VENTAS');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta)
                ->setCellValue('A4', 'VENDEDOR')
                ->setCellValue('B4', $vendedorText)
                ->setCellValue('A5', 'TRANSPORTISTA')
                ->setCellValue('B5', $transportistaText);


        $spreadsheet->getActiveSheet()
                ->setCellValue('A7', 'FECHA DE EMISIÓN')
                ->setCellValue('B7', 'NUMSER')
                ->setCellValue('C7', 'CLIENTE')
                ->setCellValue('D7', 'VENDEDOR')
                ->setCellValue('E7', 'TIPO_PAGO')
                ->setCellValue('F7', 'SUBTOTAL')
                ->setCellValue('G7', 'TOTAL_IGV')
                ->setCellValue('H7', 'TOTAL VENTA')
                ->setCellValue('I7', 'TOTAL COSTO')
                ->setCellValue('J7', 'UTILIDAD');
        

        $sumTotal_subTotal = 0;
		$sumTotal_igv = 0;
		$sumTotal_total_a_pagar = 0;
		$sumTotal_total_costo = 0;
		$sumTotal_utilidad = 0;		


        foreach($reporteTotal  as $key => $value) { 	

        	$subtotal = 0;
			$total_igv = 0;
			$total_a_pagar = 0;			
			$total_costo = 0;
			$utilidad = 0;

			$rowKey = implode(array_keys($value));			
			//FILA CABECERA			
			$spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $key.' '.$rowKey);
            $i++;
			foreach ($value[$rowKey] as $value_1) {								
				$spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value_1['fecha_de_emision'])
                        ->setCellValue('B'.$i, $value_1['numser'])
                        ->setCellValue('C'.$i, $value_1['cliente_razon_social'])
                        ->setCellValue('D'.$i, $value_1['vendedor'])
                        ->setCellValue('E'.$i, $value_1['tipo_pago'])
                        ->setCellValue('F'.$i, $value_1['subtotal'])
                        ->setCellValue('G'.$i, $value_1['total_igv'])
                        ->setCellValue('H'.$i, $value_1['total_a_pagar'])
                        ->setCellValue('I'.$i, $value_1['total_costo'])
                        ->setCellValue('J'.$i, $value_1['utilidad']);

                $subtotal += $value_1['subtotal'];
				$total_igv += $value_1['total_igv'];
				$total_a_pagar += $value_1['total_a_pagar'];
				$total_costo += $value_1['total_costo'];
				$utilidad += $value_1['utilidad'];

				$i++;
			}

			$spreadsheet->getActiveSheet()                        
                        ->setCellValue('E'.$i, 'TOTAL: '.strtoupper($key).' SERIE '.$rowKey)
                        ->setCellValue('F'.$i, $subtotal)
                        ->setCellValue('G'.$i, $total_igv)
                        ->setCellValue('H'.$i, $total_a_pagar)
                        ->setCellValue('I'.$i, $total_costo)
                        ->setCellValue('J'.$i, $utilidad);
        
            //FILA DE SUBTOTALES            		
		 	$sumTotal_subTotal += $subtotal;		 	
			$sumTotal_igv += $total_igv;
			$sumTotal_total_a_pagar += $total_a_pagar;
			$sumTotal_total_costo += $total_costo;
			$sumTotal_utilidad += $utilidad;
                        
			$i++;
		 }
		 //FILA TOTALES
		 $spreadsheet->getActiveSheet()                        
                        ->setCellValue('E'.$i, 'TOTAL VENTAS')
                        ->setCellValue('F'.$i, $sumTotal_subTotal)
                        ->setCellValue('G'.$i, $sumTotal_igv)
                        ->setCellValue('H'.$i, $sumTotal_total_a_pagar)
                        ->setCellValue('I'.$i, $sumTotal_total_costo)
                        ->setCellValue('J'.$i, $sumTotal_utilidad);

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

	public function exportarStockMinimo()
	{		
		$reporteTotal = $this->reportes_model->reporte_stockMinimo();		

		$categoriaText = $_GET['categoriaText'];		
		$productoText = $_GET['productoText'];		

		$spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i = 7;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
         

        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
       


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    

        $spreadsheet->setActiveSheetIndex(0)                
                ->setCellValue('B1', 'STOCK MINIMO');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'CATEGORIA')
                ->setCellValue('B2', $categoriaText)
                ->setCellValue('A3', 'PRODUCTO')
                ->setCellValue('B3', $productoText)
                ->setCellValue('A4', '--')
                ->setCellValue('B4', '--');


        $spreadsheet->getActiveSheet()
                ->setCellValue('A6', 'CATEGORIA')
                ->setCellValue('B6', 'CODIGO')
                ->setCellValue('C6', 'DESCRIPCION')
                ->setCellValue('D6', 'CANTIDAD')
                ->setCellValue('E6', 'STOCK MINIMO');                                         
               // ->setCellValue('E1', 'EMPRESA');

        
		foreach($reporteTotal  as $value) { 	
		 $spreadsheet->getActiveSheet()
		 				->setCellValue('A'.$i, $value['cat_nombre'])
                        ->setCellValue('B'.$i, $value['prod_codigo'])
                        ->setCellValue('C'.$i, $value['prod_nombre'])
                        ->setCellValue('D'.$i, $value['prod_stock'])
                        ->setCellValue('E'.$i, $value['prod_cantidad_minima']);
                
			$i++;
		 }

		 //$spreadsheet->getActiveSheet()		 				
                        //->setCellValue('C'.$i, 'TOTAL')
                        //->setCellValue('D'.$i, $rsTotal_venta)
                        //->setCellValue('E'.$i, '')                                         

			
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


	public function exportarReporteCategoria(){

		$rsReporte_ct = $this->reportes_model->reporteUtilidades_ct();		
		$rsReporte_nv = $this->reportes_model->reporteUtilidades_nv();
		//var_dump($rsReporte_nv);exit;


		$reporteTotal = $this->reporte_utilidadesEmpresa_format($rsReporte_nv,$rsReporte_ct);


		$fecha_desde = $_GET['fecha_desde'];		
		$fecha_hasta = $_GET['fecha_hasta'];

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
        
        $i=6;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

       
    

        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);



        $spreadsheet->setActiveSheetIndex(0)                
                ->setCellValue('B1', 'REPORTE CATEGORIA');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta);             


        $spreadsheet->getActiveSheet()
                ->setCellValue('A5', 'CODIGO')
                ->setCellValue('B5', 'DESCRIPCION')
                ->setCellValue('C5', 'CATEGORIA')
                ->setCellValue('D5', 'LINEA')
                ->setCellValue('E5', 'MARCA')
                ->setCellValue('F5', 'CANTIDAD')
                ->setCellValue('G5', 'TOTAL VENTAS');                


        $rsTotal_venta = 0;
		$rsTotal_compra = 0;
		$rsTotal_utilidad = 0;
		$rsTotal_comision = 0;
		$rsTotal_utilidadNeta = 0;	
		foreach($reporteTotal  as $value) { 	
		 $spreadsheet->getActiveSheet()
		 				->setCellValue('A'.$i, $value['prod_codigo'])
                        ->setCellValue('B'.$i, $value['prod_nombre'])
                        ->setCellValue('C'.$i, $value['cat_nombre'])
                        ->setCellValue('D'.$i, $value['lin_nombre'])
                        ->setCellValue('E'.$i, $value['mar_nombre'])
                        ->setCellValue('F'.$i, $value['cantidad'])
                        ->setCellValue('G'.$i, $value['total_venta']);
			

				$rsTotal_venta += $value['total_venta'];
				$rsTotal_cantidad += $value['cantidad'];				
				$i++;
		 }		

		 $spreadsheet->getActiveSheet()		 				
                        ->setCellValue('E'.$i, 'TOTAL')
                        ->setCellValue('F'.$i, $rsTotal_cantidad)
                        ->setCellValue('G'.$i, $rsTotal_venta);
                        

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
}