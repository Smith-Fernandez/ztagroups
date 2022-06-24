<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>

    html{ margin: 20px 20px}
    .bold,b,strong{font-weight:700}
    .tabla_borde{border:2px solid #aaa;border-radius:8px}   

    hr{height:0;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;margin-top:20px;margin-bottom:20px;border-top:1px solid #eee}  
    table{font-size:10px;line-height:0.4;border-spacing:0;border-collapse:collapse}

    h6{font-family:inherit;line-height:1.1;color:inherit;margin-top:10px;margin-bottom:10px}  
    p{margin:0 0 10px}  
    
    body{font-family:"open sans","Helvetica Neue",Helvetica,Arial,sans-serif;background-color:#2f4050;font-size:13px;color:#676a6c;overflow-x:hidden;}  
    .table>tbody>tr>td{border-top:1px solid #e7eaec;padding:5px}
    .white-bg{background-color:#fff}        
    .table-cabecera {line-height:1.3}
    .table-pagmonto {line-height:2.3;border-collapse:separate;border:solid black 0px;
                    border-radius:10px;text-align: center;}
    .table-credito{line-height:1.2;
                    text-align: left;}
    
    .producto_cabecera{ background-color: #7DABF7;color: #FFF; text-align: center;font-weight: bold;}
    .border_bottom{text-align: right;}
    .valores_totales{line-height:1.1;padding:2px;border-collapse:collapse}
    .pie_de_pagina{line-height: 1.1; text-align: right;}
    .monto_a_pagar{font-weight: bold;font-size: 12px}
    .producto_detalle{line-height: 0.9}
    </style>
</head>
<body class="white-bg" style="background-color: #fff;">
<!--<table width="100%" border="0">
    <tbody><tr>
        <td> -->
            <table width="100%" height="220px" border="0" aling="center" cellpadding="0" cellspacing="0" class="table-cabecera">
                <tbody>
                    <tr>
                    <td width="54%"  align="left">                        
                      <img src="<?PHP FCPATH;?>images/<?php echo $empresa->foto;?>" height="160" width="380" style="text-align:center;" ><br>
                        <div style="height: 2px"></div>
                        <span><strong><?php echo $empresa->empresa?></strong></span><br>
                        <span><strong>Dirección: </strong><?php echo $empresa->domicilio_fiscal?></span><br>                                                
                    </td>
                    <td width="2%"  align="center"></td>
                    <td width="44%"  valign="bottom" style="padding-left:0;">
                        <div  style="border:2px solid #aaa;border-radius:10px;height: 160px">
                            <table width="100%" border="0"  cellpadding="14" cellspacing="0">
                                <tr>
                                    <td align="center"><br><br><br><br>
                                        <span style="font-size:25px" text-align="center">R.U.C.: <?php echo $empresa->ruc?></span>
                                    </td>
                                </tr>  
                                <tr>
                                    <td align="center">
                                      <?php if($comprobante->tipo_documento_id == 9 or $comprobante->tipo_documento_id == 7){?>
                                           <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">NOTA DE CREDITO</span>
                                      <?php }else if($comprobante->tipo_documento_id==10 or $comprobante->tipo_documento_id==8){ ?>  
                                             <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">NOTA DE DEBITO</span>
                                      <?php }else{ ?>  
                                         <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center"><?php echo strtoupper($comprobante->tipo_documento) ?></span>
                                      <?php } ?>  
                                        <br><br><br><br><br><br>
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">E L E C T R Ó N I C A</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                         <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">No.: <?php echo $comprobante->serie?>-    <?php echo str_pad($comprobante->numero, 8, "0", STR_PAD_LEFT)?></span>
                                    </td>
                                </tr>                                
                               </table>
                        </div>
                        <div style="height: 25px;"></div> 
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span><strong>Telf: </strong><?php echo $empresa->telefono_fijo?> / <?php echo $empresa->telefono_movil?></span><br>
                        <?php
                            if($almacen_principal->ver_direccion_comprobante == 1){?>
                            <span><strong>Dirección almacén:</strong><?php echo $almacen_principal->alm_direccion?></span><br>
                        <?php }?> 
                    </td>
                </tr>
                </tbody></table>
                <br><br>
            <div class="tabla_borde" >


                <table width="100%" border="0" cellpadding="6" cellspacing="0" style="padding-top: 5px">
                    <tbody>
                    <tr>
                        <td width="60%" align="left" colspan="2"><strong>Razón Social:</strong>  <?php echo $comprobante->razon_social?> <?php echo $comprobante->nombres?></td>
                        <td width="40%" align="left"><strong><?php if($comprobante->tipo_cliente_id==1):?>D.N.I<?php else:?>R.U.C<?php endif?></strong>  <?php echo $comprobante->ruc?> </td>
                    </tr>
                    <tr>
                        <td width="40%" align="left" colspan="3"><strong>Dirección: </strong><div style="word-wrap: break-word;line-height: 1.5em">  <?php echo $comprobante->direccion_cliente?></td>
                    </tr>
                    <tr>
                        <td width="60%" align="left">
                            <strong>Fecha Emisión: </strong><?php echo $comprobante->fecha_de_emision?>                                                        
                        </td>
                                               
                        <!--<td>
                            <strong>Placa: </strong><?php echo $comprobante->placa?>           
                        </td>-->
                        
                    </tr>
                    <?php if($relacionado->id>0):?>
                    <tr>
                        <td width="60%" align="left"><strong>Tipo Doc. Ref.: </strong> <?php echo ($relacionado->tipo_documento_id==1)?"FACTURA":"BOLETA";?> </td>                        
                        <td width="40%" align="left"><strong>Documento Ref.: </strong>  <?php echo $relacionado->serie?>-<?php echo $relacionado->numero?></td>
                        <td width="60%" align="left"><strong>Fecha Doc. Ref.: </strong> <?php echo (new DateTime($relacionado->fecha_de_emision))->format("d/m/Y");?> </td>
                        <td width="40%" align="left"></td>
                    </tr>                    
                    <?php endif?>
                    
                    <tr>
                        <td width="60%" align="left"><strong>Tipo Moneda: </strong> <?php echo strtoupper($comprobante->moneda)?></td>
                        <!--<td width="60%" align="left"><strong>Transportista: </strong> <?php echo strtoupper($comprobante->transp_nombre)?></td> -->
                        <td>&nbsp;</td>
                        <td width="60%" align="left">
                            <strong>Fecha Vencimiento: </strong><?php echo $comprobante->fecha_de_vencimiento?>           
                        </td>                            
                    </tr>                    
                    <tr>
                    <!--<?php if ($configuracion->numero_guia): ?>                            
                        <td width="60%" align="left"><strong>Guia: </strong><?php echo $comprobante->numero_guia_remision?></td>
                    <?php endif ?>-->
                    <?php if ($comprobante->numero_guia_remision!=''): ?>
                        <td width="60%" align="left"><strong>N° Guia: </strong><?php echo strtoupper($comprobante->numero_guia_remision);?></td>
                    <?php endif ?>
                    <?php if ($configuracion->numero_pedido): ?>                            
                        <td width="40%" align="left"><strong>Nº Pedido: </strong><?php echo $comprobante->numero_pedido?></td>
                    <?php endif ?>
                    <?php if ($configuracion->condicion_venta): ?>     
                        <?php if($comprobante->condicion_venta!=''): ?>                       
                            <!--<td width="60%" align="left"><strong>Condición de Venta: </strong><?php echo $comprobante->condicion_venta?>
                         <?php endif ?>
                    <?php endif ?>                    
                         <?php if ($configuracion->orden_compra): ?>
                            <?php if($comprobante->orden_compra!=''):?>
                            <td width="40%" align="left"><strong>O/C: </strong>  <?php echo $comprobante->orden_compra?></td>
                            <?php endif ?>
                        <?php endif ?>
                        </td>-->
                        <td width="40%" align="left"></td>
                    </tr>                    
                    
                    </tbody></table>
            </div><br>
            
            <div class="">

                <table width="750px" border="0" cellpadding="7" cellspacing="0">
                    <tbody>
                        <tr class="producto_cabecera">
                            <td>Cantidad</td>
                            <td>Unid. Med.</td>
                            <!--<td align="center" class="bold">Código</td>-->
                            <td>Descripción</td>
                            <td>Valor U.</td>
                            <!--<td align="center" class="bold">Desc. Uni.</td>-->
                            <td>Valor Total</td>
                        </tr>
                        <?php foreach($detalles as $item):?>
                        <tr class="border_top">
                            <td align="center" width="10%">
                                <?php echo $item->cantidad?>
                            </td>
                            <td align="center" width="10%">
                                <?php echo $item->medida_codigo_unidad?>
                            </td>                         
                            <td align="left" width="60%" class="producto_detalle">                              
                               <?php $lineas = count(explode("\n", $item->descripcion));?>
                               <div style="word-wrap: break-word;line-height: 0.8em">
                               <!-- el nl2br para que respete el salto de linea-->     
                                 <span><?php echo $item->descripcion?></span>
                            </div>
                               
                            </td>
                            <td align="right" width="10%">
                                <?php echo $comprobante->simbolo?> <?php echo $item->importe?>
                            </td>                            
                            <td align="right" width="10%">
                                <!--<?php $total = ($comprobante->incluye_igv==1) ? $item->total : $item->subtotal ; ?>-->
                                 <?php $total = $item->total; ?>
                                <?php echo $comprobante->simbolo?> <?php echo $total?>
                            </td>
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table></div><br>

                <hr style="height: 1px; border: 0; border-top: 1px solid #666; margin: 0px 0;">
                <div class="">

            <table width="100%" border="0" cellpadding="0" cellspacing="0">

                <tbody><tr>

                    <td width="50%" valign="top">

                        <table width="100%" border="0" cellpadding="5" cellspacing="0">

                            <tbody>

                            <tr>

                                <td colspan="4">

                                    <br>

                                    <br>
                                    <br><br><br>
                                    <span style="font-family:Tahoma, Geneva, sans-serif; font-size:12px" text-align="center"><strong>SON: <?php echo $comprobante->total_letras?></strong></span>

                                    <br><br><br>
                                    

                                    <?php if(count($anticipos)>0):?>

                                    <strong>Información Adicional</strong>

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

                                <td width="70%"><?php echo $comprobante->simbolo?> <?php echo number_format($item->total_a_pagar,2)?></td>

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

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_anticipos,2)?></span></td>

                            </tr>

                            <?php endif?>



                            <?php if($comprobante->descuento_global > 0):?>

                            <tr class="border_bottom">

                                <td align="right"><strong>Descuento Global:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->descuento_global,2)?></span></td>

                            </tr>

                            <?php endif?>                                                       

                            <tr class="border_bottom">

                                <td align="right"><strong>Op. Gravadas:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_gravada,2)?></span></td>

                            </tr>                            

                            <tr class="border_bottom">

                                <td align="right"><strong>Op. Inafectas:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_inafecta,2)?></span></td>

                            </tr>

                            <tr class="border_bottom">

                                <td align="right"><strong>Op. Exoneradas:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_exonerada,2)?></span></td>

                            </tr>                            

                            <?php if($comprobante->total_igv > 0):?>

                            <tr>

                                <td align="right"><strong>IGV:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_igv,2)?></span></td>

                            </tr>

                            <?php endif?>



                             <?php if($comprobante->total_icbper > 0):?>

                            <tr>

                                <td align="right"><strong>ICBPER:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_icbper,2)?></span></td>

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

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_otros_cargos,2)?></span></td>

                            </tr>

                            <?php endif?>

                        <?php } ?>

                            <tr>

                                <td align="right"><strong>Total a Pagar:</strong></td>

                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_a_pagar,2)?></span></td>

                            </tr>

                            </tbody>

                        </table>

                    </td>

                </tr>

                </tbody>
            </table>

            <br><b>FORMA PAGO: <?php echo $pagoMonto[0]->tipo_pago; ?></b><br>
            <?php $i=1; ?>
                    <?php if($variablePM ==2){ ?>
                <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #666; margin: 10px 0; padding: 0;">

           
                
               
                    <table class="table-credito" align="left">
                        <tr>
                    
                        <td><B>INFORMACIÓN DEL CRÉDITO</B></td>
                        </tr>
                        <tr>
                       <td><B style="font-size: 10px;">MONTO NETO PENDIENTE DE PAGO:  <?php echo   $comprobante->simbolo?> <?php echo $comprobante->total_a_pagar ?></B></td>
                        </tr>
                        <tr>
                       <td><B style="font-size: 10px;">TOTAL DE CUOTAS:<?php echo count($pagoMonto)?></B></td>
                         <?php } 
                         else {
                            }?>
                        </tr>
                    </table>                
                

                
                    <table border="1" class="table-pagmonto" align="right">
                                            <?php if($variablePM ==2){ ?>

                                            <tr>
                                                <td>Cuotas</td>
                                                <td>Fecha vencimiento</td>
                                                <td>Monto</td>
                                                <td>Condición pago</td>
                                            </tr>

                                            <?php  } ?>
                                            

                                            <?php foreach($pagoMonto as $value){?>

                                            <tr>
                                                <?php  if ($value->fecha_cuota !="") {  ?>
                                                <td width="50"><b><?= "Cuota 0".$i?></b></td>
                                                 
                                                    //echo $value->monto." ".$comprobante->moneda;
                                                           }else{?>
                                                           <!--<td width="40" align="center"><b>
                                                          <?php  echo $value->monto; ?> 
                                                             </b></td>-->

                                                          <?php } ?></b>
                                            <!--CAMBIO PARA MOSTAR FECHA DE PAGO--> 
                                            <?php if ($value->fecha_cuota !="") {?>   
                                                <td width="80"><b>
                                                           <?php  echo ' '.(new DateTime($value->fecha_cuota))->format("d/m/Y");
                                                         }?></b></td> 
                                                       
                                                <?php if ($value->fecha_cuota !="") { ?>
                                                <td><b><?php echo $value->monto; ?></b></td>
                                                    <td width="90">
                                                        <!--<?php $date1 = new DateTime($value->fecha_de_emision);
                                                            $date2 = new DateTime($value->fecha_cuota);
                                                            $diff= $date1->diff($date2);
                                                            //$diff->format('%R%a días');
                                                            $diff = $date1->diff($date2);
               
                                                         ?>-->
                                                            <?php
                                                            //$comprobante->fecha_de_emision = (new DateTime($comprobante->fecha_de_emision))->format("d/m/Y");
                                                             $date1 = new DateTime($comprobante->fecha_de_emision);
                                                            $date2 = new DateTime($value->fecha_cuota);
                                                            $diff= $date1->diff($date2);
                                                            $comprobante->ndias = $diff->days; 
                                                            //var_dump($comprobante->ndias); exit(); ?>
                                                            
                                                      <b> Crédito a <?php echo $comprobante->ndias;?> dias</b>  
                                                    </td>
                                               <?php }  ?>                                     

                                            </tr>
                                            <?PHP $i++; }?>
                    </table>
                
            
            <br><br><br><br><br><br><br><br>
                                    
                                        
                                        

                                    
                         
                         
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

                    RESOLUCION N.° 097- 2012/SUNAT

                </div>-->


            </div>  
        </body>
        </html>