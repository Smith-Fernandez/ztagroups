<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
    @page teacher {
         size: A4 portrait;
         margin: 2cm;
        }

        .teacherPage {
              page: teacher;              
              page-break-after: always;
    }
    .bold,b,strong{font-weight:700}
    /*body{background-repeat:no-repeat;background-position:center center;text-align:center;margin:0;font-family: Verdana, monospace}  */
    .tabla_borde{border:2px solid #aaa;border-radius:8px}  
    tr.border_bottom td{border-bottom:1px solid #000}  
    tr.border_top td{border-top:2px solid #aaa}
    td.border_right{border-right:1px solid #666}
    .table-valores-totales tbody>tr>td{border:0}  
    .table-valores-totales>tbody>tr>td:first-child{text-align:right}  
    .table-valores-totales>tbody>tr>td:last-child{border-bottom:1px solid #666;text-align:right;width:30%}  
    hr,img{border:0}  
    table td{font-size:10px}  
    /*html{font-family:sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-size:10px;-webkit-tap-highlight-color:transparent}*/        
    hr{height:0;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;margin-top:20px;margin-bottom:20px;border-top:1px solid #eee}  
    table{border-spacing:0;border-collapse:collapse}    
    h6{font-family:inherit;line-height:1.1;color:inherit;margin-top:10px;margin-bottom:10px}  
    p{margin:0 0 10px}    
    table{background-color:transparent}  .table{width:100%;max-width:100%;margin-bottom:20px}  h6{font-weight:100;font-size:10px}  
    body{line-height:1.42857143;font-family:"open sans","Helvetica Neue",Helvetica,Arial,sans-serif;background-color:#2f4050;font-size:13px;color:#676a6c;overflow-x:hidden}  
    .table>tbody>tr>td{vertical-align:top;border-top:1px solid #e7eaec;line-height:1.42857;padding:8px}  
    .white-bg{background-color:#fff}  
 
    .table-valores-totales tbody>tr>td{border-top:0 none!important}


    </style>
</head>
<body class="white-bg" style="background-color: #fff">

<table width="100%" >
    <tbody><tr>
        <td >
            <table width="100%" height="220px" border="0" aling="center" cellpadding="0" cellspacing="0" >
                <tbody>
                    <tr>
                    <td width="53%"  align="left">

                        <!--<span><img src="<?PHP echo base_url();?>images/pelota-semilla-jd-thuds.jpg" height="80" style="text-align:center;" border="0"></span>-->
                        <span><img src="<?PHP echo FCPATH;?>images/<?php echo $empresa->foto;?>" height="160" width="380" style="text-align:center;" border="0"></span><br>
                        <div style="height: 2px"></div>
                        <!--<span><strong><?php echo $empresa->empresa?></strong></span><br>
                        <span><strong>Direcci??n: </strong><?php echo $empresa->domicilio_fiscal?></span><br>
                        <span><strong>Telf: </strong><?php echo $empresa->telefono_fijo?> / <?php echo $empresa->telefono_movil?></span>-->
                    </td>
                    <td width="2%" height="40" align="center"></td>
                    <td width="45%"  valign="bottom" style="padding-left:0">
                        <div  style="border:2px solid #aaa;border-radius:10px;height: 180px;">
                            <table width="100%" border="0"  cellpadding="6" cellspacing="0">
                                <tbody>
                                <tr>
                                 <td align="center">
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center"></span><BR>
                                </tr>                                    
                                <tr>

                                    <td align="center">
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">COMPRA</span><BR>
                                      <?php if($comprobante->tipo_documento_id==9){?>  
                                           <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">NOTA DE CREDITO</span>
                                      <?php }else if($comprobante->tipo_documento_id==10){ ?>  
                                             <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">NOTA DE DEBITO</span>
                                      <?php }else{ ?>  
                                         <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center"><?php echo strtoupper($comprobante->tipo_documento) ?></span>
                                      <?php } ?>  
                                        
                                        <br>
                                        <!--<span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">E L E C T R ?? N I C A</span>-->
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center">
                                         <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">No.: <?php echo $comprobante->serie?>-<?php echo $comprobante->numero?></span>
                                    </td>
                                </tr>
                                <!--<tr>
                                    <td align="center">
                                        Nro. R.I. Emisor: <span></span>
                                    </td>
                                </tr>-->
                                </tbody></table>
                        </div>
                    </td>
                </tr>

                </tbody></table>
                <br>
            <div class="tabla_borde" >
                
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td width="60%" align="left"><strong>Proveedor:</strong>  <?php echo $comprobante->prov_razon_social?> </td>
                        <td width="40%" align="left"><strong>R.U.C</strong>  <?php echo $comprobante->prov_ruc?> </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left">
                            <strong>Fecha Emisi??n: </strong><?php echo $comprobante->fecha_de_emision?>  
                            
                            <br><br><strong>Fecha Vencimiento: </strong><?php echo $comprobante->fecha_de_vencimiento?> 
                        	
                            
                        </td>
                        <td width="40%" align="left"><strong>Direcci??n: </strong>  <?php echo $comprobante->prov_direccion?></td>



                    </tr>
                    <?php if($relacionado->id>0):?>
                    <tr>
                        <td width="60%" align="left"><strong>Tipo Doc. Ref.: </strong> <?php echo ($relacionado->tipo_documento_id==1)?"FACTURA":"BOLETA";?> </td>
                        <td width="40%" align="left"><strong>Documento Ref.: </strong>  <?php echo $relacionado->serie?>-<?php echo $relacionado->numero?></td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Fecha Doc. Ref.: </strong> <?php echo (new DateTime($relacionado->fecha_de_emision))->format("d/m/Y");?> </td>
                        <td width="40%" align="left"></td>
                    </tr>
                    <?php endif?>
                    
                    <tr>
                        <td width="60%" align="left"><strong>Tipo Moneda: </strong> <?php echo strtoupper($comprobante->moneda)?></td>
                        <td width="60%" align="left"><!--<strong>Tipo de Pago: </strong><?php echo $comprobante->tipo_pago?>--></td>

                       
                    </tr>
                    
                    <tr>
                    <!--<?php if ($configuracion->numero_guia): ?>                            
                        <td width="60%" align="left"><strong>Guia: </strong><?php echo $comprobante->numero_guia_remision?></td>
                    <?php endif ?>-->
                    <?php if ($comprobante->numero_guia_remision!=''): ?>                            
                        <td width="60%" align="left"><strong>N?? Guia: </strong><?php echo strtoupper($comprobante->numero_guia_remision);?></td>
                    <?php endif ?>
                    <?php if ($configuracion->numero_pedido): ?>                            
                        <td width="40%" align="left"><strong>N?? Pedido: </strong><?php echo $comprobante->numero_pedido?></td>
                    <?php endif ?>
                    <?php if ($configuracion->condicion_venta): ?>     
                        <?php if($comprobante->condicion_venta!=''): ?>                       
                            <td width="60%" align="left"><strong>Condici??n de Venta: </strong><?php echo $comprobante->condicion_venta?>
                         <?php endif ?>
                    <?php endif ?>
                    </tr>

                    <tr>
                        
                         <?php if ($configuracion->orden_compra): ?>
                            <?php if($comprobante->orden_compra!=''):?>
                            <td width="40%" align="left"><strong>O/C: </strong>  <?php echo $comprobante->orden_compra?></td>
                            <?php endif ?>
                        <?php endif ?>
                        </td>
                        <td width="40%" align="left"></td>
                    </tr>                    
                    
                    </tbody></table>
            </div><br>
            
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="7" cellspacing="0">
                    <tbody>
                        <tr>
                            <td align="center" class="bold">Cantidad</td>
                            <td align="center" class="bold" style="border-left: 2px solid #aaa;">Unid. Medida</td>
                            <!--<td align="center" class="bold">C??digo</td>-->
                            <td align="center" class="bold" style="border-left: 2px solid #aaa;">Descripci??n</td>
                            <td align="center" class="bold" style="border-left: 2px solid #aaa;">Valor Unitario</td>
                            <!--<td align="center" class="bold">Desc. Uni.</td>-->
                            <td align="center" class="bold" style="border-left: 2px solid #aaa;">Valor Total</td>
                        </tr>
                        <?php $i = 0;$y=0;foreach($detalles as $item):
                          if(($i != 0) && (($y == 0) && ($i%22 == 0)) OR (($y != 0) && ($i%34 == 0))) {

                            echo '</tbody></table></div></div><div class="teacherPage"><div class="tabla_borde"><table width="100% border="0" cellpadding="7" cellspacing="0"><tbody>';
                            $y++;$i=0;                            
                          }?>
                            
                        <tr class="border_top">
                            <td align="center" width="50px">
                                <?php echo $item->cantidad?>
                            </td>
                            <td align="center" width="50px" style="border-left: 2px solid #aaa;">
                                <?php echo $item->medida_nombre?>
                            </td>
                         <!--<td align="center" >
                                <span><?php echo $item->prod_codigo?></span><br>
                            </td>-->
                            <td align="left" width="310px" style="border-left: 2px solid #aaa;">
                              
                                <?php $lineas = count(explode("\n", $item->descripcion));?>
                           <?php echo $item->descripcion?>
                            </td>
                            <td align="center" width="80px" style="border-left: 2px solid #aaa;">
                            	<?php echo $comprobante->simbolo?> <?php echo $item->importe?>
                            </td>
                            <!--<td align="center">
                            	<?php echo $comprobante->simbolo?> <?php echo $item->descuento?>
                            </td>-->                            
                            <td align="center" width="80px" style="border-left: 2px solid #aaa;">
                                <!--<?php $total = ($comprobante->incluye_igv==1) ? $item->total : $item->subtotal ; ?>-->
                                 <?php $total = $item->total; ?>
                            	<?php echo $comprobante->simbolo?> <?php echo $total?>
                            </td>
                        </tr>
                   		<?php $i++;endforeach?>

                    </tbody>
                </table></div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td width="50%" valign="top">
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tbody>
                            <tr>
                                <td colspan="4">
                                    <br>
                                    <br>
                                    <span style="font-family:Tahoma, Geneva, sans-serif; font-size:12px" text-align="center"><strong>SON: <?php echo $comprobante->total_letras?></strong></span>
                                    <br>
                                    <br>
                                    <?php if(count($anticipos)>0):?>
                                    <strong>Informaci??n Adicional</strong>
                                    <?php endif?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    
                        <?php if(count($anticipos)>0):?>
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tbody>
                            <tr>
                                <td>
                                    <br>
                                    <strong>Anticipo</strong>
                                    <br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 10px;">
                            <tbody>
                            <tr>
                                <td width="30%"><b>Nro. Doc.</b></td>
                                <td width="70%"><b>Total</b></td>
                            </tr>
                            <?php foreach($anticipos as $item):?>
                            <tr class="border_top">
                                <td width="30%"><?php echo $item->serie?>-<?php echo $item->numero?></td>
                                <td width="70%"><?php echo $comprobante->simbolo?> <?php echo $item->total_a_pagar?></td>
                            </tr>
                        	<?php endforeach?>
                            </tbody>
                        </table>
                    	<?php endif?>
                    </td>
                    <td width="50%" valign="top">
                        <br>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-valores-totales">
                            <tbody>

                         <?php if($comprobante->tipo_documento_id != 0){?>      
                            <?php if($comprobante->total_anticipos > 0):?>
                            <tr class="border_bottom">
                                <td align="right"><strong>Total Anticipo:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_anticipos?></span></td>
                            </tr>
                        	<?php endif?>

                            <?php if($comprobante->descuento_global > 0):?>
                            <tr class="border_bottom">
                                <td align="right"><strong>Descuento Global:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->descuento_global?></span></td>
                            </tr>
                            <?php endif?>                            

                        	<?php if($comprobante->total_gravada > 0):?>
                            <tr class="border_bottom">
                                <td align="right"><strong>Op. Gravadas:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_gravada?></span></td>
                            </tr>
                            <?php endif?>

                            <?php if($comprobante->total_inafecta > 0):?>
                            <tr class="border_bottom">
                                <td align="right"><strong>Op. Inafectas:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_inafecta?></span></td>
                            </tr>
                            <?php endif?>

                            <?php if($comprobante->total_exonerada > 0):?>
                            <tr class="border_bottom">
                                <td align="right"><strong>Op. Exoneradas:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_exonerada?></span></td>
                            </tr>
                            <?php endif?>

                            <?php if($comprobante->total_igv > 0):?>
                            <tr>
                                <td align="right"><strong>IGV:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_igv?></span></td>
                            </tr>
                            <?php endif?>

                            <!--
                            <tr>
                                <td align="right"><strong>ISC:</strong></td>
                                <td width="120" align="right"><span></span></td>
                            </tr>
                        	-->
                        	<?php if($comprobante->total_otros_cargos > 0):?>
                            <tr>
                                <td align="right"><strong>Otros Cargos:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_otros_cargos?></span></td>
                            </tr>
                            <?php endif?>
                            
                            
                        <?php } ?>
                            <tr>
                                <td align="right"><strong>Total a Pagar:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_a_pagar?></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody></table>
            <br>
                
           <!-- <div>
                <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #666; margin: 10px 0; padding: 0;">
                
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="20%" align="center">  
                                <?php $lineas = count(explode("\n", $comprobante->notas));?>
                                <textarea style="width: 100%;border:0;overflow: hidden;color:#5F6A6A;" rows="<?php echo $lineas;?>"><?php echo $comprobante->notas?></textarea>                              
                               
                            </td>
                          
                        </tr>
                    </tbody>
                </table>

            </div> -->
             <div>

                <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #666; margin: 10px 0; padding: 0;">

                

                <table width="100%" border="0" cellpadding="0" cellspacing="0">

                    <tbody>

                        <tr>

                            <td width="80%" align="left">  

                              <b>OBSERVACIONES:</b> <?php $lineas = count(explode("\n", $comprobante->notas));?>

                              <?php echo $comprobante->notas?>   <br><br><br><br>                           

                               

                            </td>

                        </tr>                            

                        <tr>

                            <td align="center">                                

                                <img src="<?PHP echo $rutaqr?>" style="width:2cm;height: 2cm;">

                                <br>                                

                                <?php echo $certificado?>

                            </td>

                        </tr>

                    </tbody>

                </table><br>
                <!--
                <div align="center">

                    EMITIDO MEDIANTE PROVEEDOR

                    AUTORIZADO POR LA SUNAT

                    RESOLUCION N.?? 097- 2012/SUNAT

                </div>-->

            </div>
         </td>
    </tr>
    </tbody></table>

   
</body>



</html>