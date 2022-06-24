<html>
    <head>
        <style>
            html, body {
                margin: 0 2px;
                padding: 0;
                font-family: sans-serif;
            }
            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }
            .datos_titulo1{
                font-size: 5px;
                text-align: center;
                line-height: 1em;                
            }            

            .tabla_cabecera{
                font-size: 5px;                
            }
            .tabla_datos{
                font-size: 4px;
                text-align: center;
            }
            .tabla_datos_cantidad{
                font-size: 5px;
                text-align: center;
            }
            .datos_totales{
                font-size: 5px;                                
            }
             .tabla_cabecera2{
                font-size: 4px;                
            }
            .datos_totales_bold{
                font-size: 6px;
                font-weight: bold;                  
            }
            .datos_cabecera{
                font-size: 5px;                
                text-align: center;       
                line-height: 1em;         
            }            
            .datos_cabecera_bold{
                font-weight: bold;
            }
            .datos_cliente{
                text-align: left;
                margin-left: 4px;                
                font-size: 4.5px;                
                line-height: 1em;                
            }
            .logo{
                margin: -10px 10px -15px 0px;
            }
        </style>
        <title>Sistema de Ventas</title>
    </head>
    <body>
<?php 
 switch ($comprobante->tipo_documento_id) {
    case 1:
        $tipo_documento = "FACTURA";
        break;
    case 3:
        $tipo_documento = "BOLETA";
        break;
    case 7:
        $tipo_documento = "NOTA DE CREDITO";
        $data['tipo_nota'] = $this->tipo_ncreditos_model->select($data['comprobante']['tipo_nota_id']);
        $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
        break;
    case 8:
        $tipo_documento = "NOTA DE DEBITO";
        $data['tipo_nota'] = $this->tipo_ndebitos_model->select($data['comprobante']['tipo_nota_id']);
        $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
        break;
    }
    $tipopago ="";
    $data['tipo_documento'] = $tipo_documento;  
    $ruta_foto = base_url()."images/".$empresa->foto;
 ?>
        
        <img src="<?php echo "images/".$empresa->foto;?>" height="80" width="100%" style="text-align:center;" border="0" class="logo">
        <span id="height-container">
            <p class ="datos_titulo1 cabecera">
                <span class="datos_cabecera_bold"><?php echo $empresa->empresa?></span><br>
                <span class="datos_cabecera_bold">RUC : <?php echo $empresa->ruc?></span><br>
                <span class="datos_cabecera"><?php echo $empresa->domicilio_fiscal?></span><br>                
                <span class="datos_cabecera_bold">WWW.ZTAGROUP.COM.PE</span><br>
                ----------------------------------------------------------------------------<br>
                <b><?php echo $data['tipo_documento']; ?>&nbsp;&nbsp;<?php echo $comprobante->serie?>-<?php echo str_pad($comprobante->numero, 8, "0", STR_PAD_LEFT)?></b><br>
                Fecha/hora emision: <?php echo $comprobante->fecha_de_emision;?><br>                
                Fecha vencimiento: <?php echo $comprobante->fecha_de_vencimiento;?><br>                
                Vendedor : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>
                Transportista : <?php echo$comprobante->transp_nombre?> <br>
                ----------------------------------------------------------------------------<br></p>
                <p class="datos_cliente"> CLIENTE :
                <?php echo $comprobante->razon_social?> <?php echo $comprobante->nombres?><br><?php if($comprobante->tipo_cliente_id==1):?><?php else:?><?php endif?><?php echo "RUC:  ". $comprobante->ruc?><br>
                DIRECCION:<?php echo "  ". $comprobante->domicilio1?><br>
                ---------------------------------------------------------------------------------<br>
                </p>                
            </p>            
            <table width="100%">
                <thead>
                    <tr>
                        <th width="8" align="center" class="tabla_cabecera">CANT.</th>
                        <th class="tabla_cabecera">PRODUCTO</th>
                        <th width="13" align="center" class="tabla_cabecera">P/U</th>
                        <th width="13" align="center" class="tabla_cabecera">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detalles as $item){
                            //$total = ($comprobante->incluye_igv==1) ? $item->total : $item->subtotal ;
                        $total = $item->total;
                            $tipopago = $item->tipo_pago;?>
                    <tr>
                        <td class="tabla_datos_cantidad"><?php echo $item->cantidad?></td>
                        <td class="tabla_cabecera"><?php echo $item->descripcion?></td>
                        <td class="tabla_datos_cantidad"><?php  echo $item->importe?></td>
                        <td class="tabla_datos_cantidad"><?php  echo $total?></td>
                    </tr>
                    <?php                     
                    }
                    ?>
                </tbody>
            </table>
            <p align="center" class ="datos_titulo1">
            ----------------------------------------------------------------------------<br>
            </p>
            <table>
                <tr>
                    <td class="datos_totales">Op. Gravadas:</td>
                    <td class="datos_totales"><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_gravada?></td>
                </tr>
                <tr>
                    <td class="datos_totales">Op. Inafectas:</td>
                    <td class="datos_totales"><?php echo $comprobante->simbolo." ".$comprobante->total_inafecta?></td>
                </tr>
                <tr>
                    <td class="datos_totales">Op. Exonerada:</td>
                    <td class="datos_totales"><?php echo $comprobante->simbolo." ".$comprobante->total_exonerada?></td>
                </tr>

                <tr>
                    <td class="datos_totales">IGV (18%):</td>
                    <td class="datos_totales"><?php echo $comprobante->simbolo." ".$comprobante->total_igv?></td>
                </tr>
                <?php if($comprobante->total_icbper > 0):?>
                 <tr>
                    <td class="datos_totales">ICBPER:</td>
                    <td class="datos_totales"><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_icbper?></td>
                </tr>  
                <?php endif?>
                <tr>
                    <td class="datos_totales_bold">IMPORTE TOTAL:</td>
                    <td class="datos_totales_bold"><?php echo $comprobante->simbolo." ".$comprobante->total_a_pagar?></td>
                </tr>
                <tr>
                    <td class="datos_totales_bold">MEDIO PAGO</td>
                </tr>                
                <?PHP foreach($pagoMonto as $value){?>                                                                                
                    <tr>
                        <td class="datos_totales"><?= $value->tipo_pago?></td>
                        <td class="datos_totales"><?= $value->monto?></td>
                    </tr>                                        
                <?PHP }?>
                <!--<tr>
                    <td class="datos_totales">Tipo Pago:</td>
                    <td class="datos_totales"><?php echo $comprobante->tipo_pago;?></td>
                </tr>-->
                <?PHP if($comprobante->placa != NULL){?>                
                <tr>
                    <td class="datos_totales">PLACA :</td>
                    <td class="datos_totales"><?php echo $comprobante->placa?></td>
                </tr>
                <?PHP }?>
                <tr>
                    <td class="datos_totales">SON :</td>
                    <td class="datos_totales"><?php echo $comprobante->total_letras?></td>
                </tr>
                <tr>
                    <td class="datos_totales">OBSERVACIONES :</td>
                    <td class="datos_totales"><?php echo $comprobante->notas?></td>
                </tr>                
            </table> 
             <?php if ($comprobante->retencion == 1) { ?>
           <table>
                <tr>
                    <td colspan="3" class="datos_totales" style="text-align: center;"><b>INFORMACION DE LA RETENCION </b></td>  
                </tr>
                <tr>
                    <th class="tabla_cabecera2" width="8" align="center">BASE IMPONIBLE</th>
                    <th class="tabla_cabecera2" align="center">% DE RETENCION</th>
                    <th class="tabla_cabecera2" align="center">MONTO</th>
                </tr>
                <tr>
                    <td class="tabla_datos_cantidad"><?php echo $comprobante->total_a_pagar ?></td>
                    <td class="tabla_datos_cantidad"><?php echo number_format($comprobante->porcentaje_de_retencion,2) ?></td>
                    <td class="tabla_datos_cantidad"><?php echo number_format($comprobante->total_retencion,2) ?></td>
                </tr>
            </table> 
         <?php   } ?>
          <?php if ($comprobante->detraccion == 1) { ?>
           <table>
                <tr>
                    <td colspan="3" class="datos_totales" style="text-align: center;"><b>INFORMACION DE LA DETRACCION </b></td>  
                </tr>
                <tr>
                    <th class="tabla_cabecera2" width="8" align="center">BASE IMPONIBLE</th>
                    <th class="tabla_cabecera2" align="center">% DE DETRACCION</th>
                    <th class="tabla_cabecera2" align="center">MONTO</th>
                </tr>
                <tr>
                    <td class="tabla_datos_cantidad"><?php echo $comprobante->total_a_pagar ?></td>
                    <td class="tabla_datos_cantidad"><?php echo number_format($comprobante->porcentaje_de_detraccion,2) ?></td>
                    <td class="tabla_datos_cantidad"><?php echo number_format($comprobante->total_detraccion,2) ?></td>
                </tr>
            </table> 
         <?php   } ?>

           <div>
            <b style="font-size:5px">Condición de Pago: <?php echo $pagoMonto[0]->tipo_pago; ?></b><br>
            <b style="font-size:5px">Monto Neto Pendiente de Pago: <?php echo number_format($total_pago,2); ?></b><br style="margin-bottom:0.5px"/>
            <?php if ($value->fecha_cuota !="") { ?>
            <?php
            $i = 1; 
             foreach($pagoMonto as $value){?>
             <?php $date1 = new DateTime($value->fecha_de_emision);
             $date2 = new DateTime($value->fecha_cuota);
             $diff= $date1->diff($date2);
             $diff = $date1->diff($date2);?>
             
            <b style="font-size:4px"> Cuota 0<?php echo $i?>  / Fecha: <?php echo (new DateTime($value->fecha_cuota))->format("d/m/Y");?> 
                / Monto: <?php
                if ($comprobante->retencion == 1 || $comprobante->detraccion == 1) {
                     echo $value->monto_neto;
                 } else{
                    echo $value->monto; 
                 }
               
                ?> </b><?php $i++; } ?>
            <!--<?php if ($value->fecha_cuota !="") { ?>
            <b style="font-size:4px"> Fecha: <?php echo (new DateTime($value->fecha_cuota))->format("d/m/Y");?> /</b> <?php } ?>
            <?php if ($value->fecha_cuota !="") { ?>
            <b style="font-size:4px"> Monto: <?php echo $value->monto;?> </b><br> <?php } ?>-->
        <?php } ?>
        </div>           
            <p align="center"><img width="40" height="40" src="<?PHP echo $rutaqr?>"></p>
            <p align="center" class ="datos_titulo1"><?php echo $certificado?></p>
        </span>
        <div align="center" class="datos_totales">
                    EMITIDO MEDIANTE PROVEEDOR
                    AUTORIZADO POR LA SUNAT
                    RESOLUCION N.° 097- 2012/SUNAT
                </div>
    </body>
</html>