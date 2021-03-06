<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>

    .bold,b,strong{font-weight:700}
    body{background-repeat:no-repeat;background-position:center center;text-align:center;margin:0;font-family: Verdana, monospace}  
    .tabla_borde{border:1px solid #666;border-radius:10px}  
    tr.border_bottom td{border-bottom:1px solid #000}  
    tr.border_top td{border-top:1px solid #666}
    td.border_right{border-right:1px solid #666}
    .table-valores-totales tbody>tr>td{border:0}  
    .table-valores-totales>tbody>tr>td:first-child{text-align:right}  
    .table-valores-totales>tbody>tr>td:last-child{border-bottom:1px solid #666;text-align:right;width:30%}  
    hr,img{border:0}  
    table td{font-size:10px}  
    html{font-family:sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-size:10px;-webkit-tap-highlight-color:transparent}
    a{background-color:transparent}  
    a:active,a:hover{outline:0}  
    img{vertical-align:middle}  
    hr{height:0;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;margin-top:20px;margin-bottom:20px;border-top:1px solid #eee}  
    table{border-spacing:0;border-collapse:collapse}
    @media print{blockquote,img,tr{page-break-inside:avoid}*,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}blockquote{border:1px solid #999}img{max-width:100%!important}p{orphans:3;widows:3}.table{border-collapse:collapse!important}.table td{background-color:#fff!important}}  
    a,a:focus,a:hover{text-decoration:none}  
    /*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}  
    a{color:#428bca;cursor:pointer}  */
    a:focus,a:hover{color:#2a6496}  
    a:focus{outline:dotted thin;outline:-webkit-focus-ring-color auto 5px;outline-offset:-2px}  
    h6{font-family:inherit;line-height:1.1;color:inherit;margin-top:10px;margin-bottom:10px}  
    p{margin:0 0 10px}  
    blockquote{padding:5px 10px;margin:0 0 20px;border-left:5px solid #eee}  
    table{background-color:transparent}  .table{width:100%;max-width:100%;margin-bottom:20px}  h6{font-weight:100;font-size:10px}  
    body{line-height:1.42857143;font-family:"open sans","Helvetica Neue",Helvetica,Arial,sans-serif;background-color:#2f4050;font-size:13px;color:#676a6c;overflow-x:hidden}  
    .table>tbody>tr>td{vertical-align:top;border-top:1px solid #e7eaec;line-height:1.42857;padding:8px}  
    .white-bg{background-color:#fff}  
   
    .table-valores-totales tbody>tr>td{border-top:0 none!important}


    </style>
</head>
<body class="white-bg" style="background-color: #fff">
<table width="100%">
    <tbody><tr>
        <td>
            <table width="100%" height="220px" border="0" aling="center" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                    <td width="53%"  align="left">
                        <!--<span><img src="<?PHP echo base_url();?>images/pelota-semilla-jd-thuds.jpg" height="80" style="text-align:center;" border="0"></span>-->
                        <!--<span><img src="<?PHP echo FCPATH;?>images/<?php echo $empresa->foto;?>" height="160" width="380" style="text-align:center;" border="0"></span><br>-->
                        <span><img src="<?PHP echo FCPATH;?>images/logo01.jpeg?>" height="150" width="380" style="text-align:center;" border="0"></span><br>
                        <div style="height: 2px"></div>
                        <!--<span><strong><?php echo $empresa->empresa?></strong></span><br>-->
                        <span><strong>MULTICAR</strong></span><br>                        
                        <!--<span><strong>Direcci??n: </strong><?php echo $empresa->domicilio_fiscal?></span><br>-->
                        <span><strong>Direcci??n: Urb. E. L??pez Albujar D-8 II etapa SAn Antonio - Moquegua</span><br>
                        <!--<span><strong>Telf: </strong><?php echo $empresa->telefono_fijo?> / <?php echo $empresa->telefono_movil?></span><br>-->
                        <span><strong>Telf: </strong>958512122</span><br>
                        <?php
                            if($almacen_principal->ver_direccion_comprobante == 1){?>
                            <span><strong>Direcci??n almac??n:</strong><?php echo $almacen_principal->alm_direccion?></span><br>
                        <?php }?>
                    </td>
                    <td width="2%" height="40" align="center"></td>
                    <td width="45%"  valign="bottom" style="padding-left:0">
                        <div  style="border:1px solid #aaa;border-radius:10px;height: 180px;">
                            <table width="100%" border="0" height="220" cellpadding="6" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td align="center">
                                        <!--<span style="font-size:25px" text-align="center">R.U.C.: <?php echo $empresa->ruc?></span>-->
                                        <span style="font-size:25px" text-align="center">R.U.C.: 10443962889</span>
                                    </td>
                                </tr>                                    
                                <tr>
                                    <td align="center">
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">N O T A</span>
                                        <br>
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">D E  V E N T A</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center">
                                         <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center"><?php echo "NP-".str_pad($nota->notap_correlativo, 8, "0", STR_PAD_LEFT)?></span>
                                    </td>
                                </tr>
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
                        <td width="60%" align="left"><strong>Raz??n Social:</strong>  <?php echo $cliente->razon_social?> <?php echo $cliente->nombres?></td>
                        <td width="40%" align="left"><strong><?php if($cliente->tipo_cliente_id==1):?>D.N.I<?php else:?>R.U.C<?php endif?></strong>  <?php echo $cliente->ruc?> </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left">
                            <strong>Fecha: </strong><?php echo $nota->notap_fecha?>                          
                        </td>
                        <td width="40%" align="left"><strong>Direcci??n: </strong>  <?php echo $nota->notap_cliente_direccion?></td>
                    </tr>
                    <!-- esto es para nota 
                    <tr>
                        <td width="60%" align="left"><strong>Tipo Doc. Ref.: </strong>  {{ doc.tipDocAfectado|catalog('01') }}</td>
                        <td width="40%" align="left"><strong>Documento Ref.: </strong>  {{ doc.numDocfectado }}</td>
                    </tr>-->
                    
                    <tr>
                        <td width="60%" align="left"><strong>Tipo Moneda: </strong> <?php echo strtoupper($nota->moneda)?></td>
                        <td><strong>Vendedor: </strong><?php echo $empleado->apellido_paterno." ".$empleado->apellido_materno.", ".$empleado->nombre;?></td>
                    </tr>
                    <tr>
                        <!--<td width="60%" align="left"><strong>Tipo Pago: </strong> <?php echo strtoupper($nota->tipo_pago)?></td>-->
                        <td><strong>Transportista: </strong><?php echo $nota->transp_nombre?></td>
                    </tr>                    
                    </tbody></table>
            </div><br>
            
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                        <tr>
                            <td align="center" class="bold">Cantidad</td>
                            <td align="center" class="bold">Unid. Med.</td>
                            <td align="center" class="bold">Descripci??n</td>
                            <td align="center" class="bold">Valor Unitario</td>
                            <!--<td align="center" class="bold">Desc. Uni.</td>-->
                            <td align="center" class="bold">Valor Total</td>
                        </tr>
                        <?php foreach($nota->detalles as $item):?>
                        <tr class="border_top">
                            <td align="center">
                                <?php echo $item->notapd_cantidad?>
                            </td>
                            <td align="center">
                                <span><?php echo $item->medida_nombre?></span><br>
                            </td>
                            <td align="center" width="300px">
                                <span><?php echo $item->notapd_descripcion?></span><br>
                            </td>
                            <td align="center">
                            	<?php echo $comprobante->simbolo?> <?php echo $item->notapd_precio_unitario?>
                            </td>
                           <!-- <td align="center">
                            	<?php echo $comprobante->simbolo?> <?php echo $item->notapd_descuento?>
                            </td>   -->                         
                            <td align="center">
                            	<?php echo $comprobante->simbolo?> <?php echo $item->notapd_total?>
                            </td>
                        </tr>
                   		<?php endforeach?>

                    </tbody>
                </table></div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td width="50%" >
                         <span><b>OBSERVACIONES: </b></span><?php echo $nota->notap_observaciones?> 
                     </td>                        
                    <td width="50%" valign="top">
                        <br>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-valores-totales">
                            <tbody>
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

                        	<?php //if($nota->notap_subtotal > 0):?>
                            <!--<tr class="border_bottom">
                                <td align="right"><strong>Op. Gravadas:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $nota->notap_subtotal?></span></td>
                            </tr>-->
                            <?php //endif?>

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

                            <?php //if($nota->notap_igv > 0):?>
                            <!--<tr>
                                <td align="right"><strong>IGV:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $nota->notap_igv?></span></td>
                            </tr>-->
                            <?php //endif?>

                        	<?php if($comprobante->total_otros_cargos > 0):?>
                            <tr>
                                <td align="right"><strong>Otros Cargos:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_otros_cargos?></span></td>
                            </tr>
                            <?php endif?>

                            <?php if($comprobante->total_descuentos > 0):?>
                            <tr class="border_bottom">
                                <td align="right"><strong>Total Descuento:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_descuentos?></span></td>
                            </tr>
                            <?php endif?> 

                            <tr>
                                <td align="right"><strong>Total a Pagar:</strong></td>
                                <td width="120" align="right"><span><?php echo $comprobante->simbolo?> <?php echo $nota->notap_total?></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><br><b>MEDIO PAGO</b></td>
                        <?PHP foreach($pagoMonto as $value){?>                                        
                            <table border="0">
                                <tr>
                                    <td width="50"><b><?= $value->tipo_pago?></b></td>
                                    <td width="50"><b><?= $value->monto?></b></td>
                                </tr>
                            </table>
                        <?PHP }?>
                </tr>
                </tbody></table>
            <br>                
            <div align="center" style="width: 90%" class="datos_totales" >
                V??lido s??lo para entrega  de productos pedir su boleta o factura
            </div>
        </td>
    </tr>
    </tbody></table>
</body></html>