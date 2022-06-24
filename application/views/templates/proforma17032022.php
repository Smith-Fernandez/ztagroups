<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title> Proforma </title>

    <style>



    .bold,b,strong{font-weight:700}

   /* body{background-repeat:no-repeat;background-position:center center;text-align:center;margin:0;font-family: Verdana, monospace}  */

    .tabla_borde{border:1px solid #666;border-radius:10px}  

    tr.border_bottom td{border-bottom:1px solid #000}  

    tr.border_top td{border-top:1px solid #666}

    td.border_right{border-right:1px solid #666}

    .table-valores-totales tbody>tr>td{border:0}  

    .table-valores-totales>tbody>tr>td:first-child{text-align:right}  

    .table-valores-totales>tbody>tr>td:last-child{border-bottom:1px solid #666;text-align:right;width:30%} 

    /*hr,img{}*/  

    table td{font-size:10px}  

   html{
    font-family:sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-size:10px;-webkit-tap-highlight-color:transparent;size: auto; /* auto is the initial value */ /* this affects the margin in the printer settings */ margin: 12mm 14mm 12mm 14mm;}    
    /*se puso una clase al logo para que no afecte a las etiquetas img*/
    .logo{

        vertical-align:middle;
   /*reduci el tamañano del height estaba en 200px lo deje en 190px*/
        height: 190px;

        width: 220;

        text-align: center;        
  /* se cabmio el lo marguenes estaba en 20px 400px -35px 10px */
        margin: 0px 400px -85px -10px;
  /* en vez de poner br se puso hr debajo del logo*/
    }  

    hr{
        margin-top:5px;
      border:0
    }      

    p{margin:0 0 10px}    



    table{background-color:transparent}  .table{width:100%;max-width:100%;margin-bottom:10px}  h6{font-weight:100;font-size:10px}

    body{
        line-height:1.42857143;
        font-family:"open sans","Helvetica Neue",Helvetica,Arial,sans-serif;
        background-color:#2f4050;
        font-size:13px;
        color:##000000;
        overflow-x:hidden
        margin: 0px;
        }

    .table>tbody>tr>td{vertical-align:top;border-top:1px solid #e7eaec;line-height:1.21857;padding:8px}      

    

    .table-valores-totales tbody>tr>td{border-top:0 none!important}



    </style>

</head>

<body class="white-bg" style="background-color: #fff">



            <table width="100%" height="190px" border="0" aling="center" cellpadding="0" cellspacing="0">

                <tbody>

                    <tr>

                        <td width="53%"  align="left">                            

                            <span><img class="logo" src="<?PHP echo FCPATH;?>images/<?php echo $empresa->foto;?>"></span><hr><hr><hr><hr><hr><hr><hr><hr>                       

                            <span><strong><?php echo $empresa->empresa?></strong></span><br>

                            <span><strong>Dirección: </strong><?php echo $empresa->domicilio_fiscal?></span><br>

                            <span><strong>Telf: </strong><?php echo $empresa->telefono_fijo?> / <?php echo $empresa->telefono_movil?></span>

                        </td>

                        <td width="2%" height="40" align="center"></td>

                        <td width="45%" valign="bottom" style="padding-left:0">

                            <div  style="border:1px solid #aaa;border-radius:10px;height: 170px;"><br>

                                <table width="100%" border="0"  cellpadding="6" cellspacing="0">

                                    <tbody>

                                    <tr>

                                        <td align="center">

                                            <span style="font-size:25px" text-align="center">R.U.C.: <?php echo $empresa->ruc?></span>

                                        </td>

                                    </tr>                                    

                                    <tr>

                                        <td align="center">                                      

                                            <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">C O T I Z A C I Ó N</span>

                                        </td>

                                    </tr>                             

                                    <tr>

                                        <td align="center">                                       

                                             <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">No.: <?php echo  str_pad($proforma->prof_correlativo, 8, "0", STR_PAD_LEFT)?></span>

                                        </td>

                                    </tr>

                                    </tbody>

                                </table>

                            </div>

                        </td>

                    </tr>

                </tbody></table>                
<hr>
            <div class="tabla_borde" >

                <table width="100%" border="0" cellpadding="5" cellspacing="0">

                    <tbody>

                    <tr>

                        <td width="50%" align="left"><strong>Razón Social:</strong>  <?php echo $cliente->razon_social?> <?php echo $cliente->nombres?></td>

                        <td width="50%" align="left"><strong><?php if($cliente->tipo_cliente_id==1):?>D.N.I<?php else:?>R.U.C<?php endif?></strong>  <?php echo $cliente->ruc?> </td>

                    </tr>

                    <tr>

                        <td width="50%" align="left">                           

                            <strong>Fecha Emisión: </strong> <?php echo $proforma->prof_doc_fecha?>  

                        </td>

                        <td width="50%" align="left"><strong>Dirección: </strong>  <?php echo $proforma->prof_direccion;?></td>

                    </tr>

                    <tr>

                        <td width="50%" align="left"><strong>Tipo Moneda: </strong> <?php echo strtoupper($proforma->moneda)?></td>

                        <td><strong>Vendedor: </strong><?php echo $empleado->apellido_paterno." ".$empleado->apellido_materno.", ".$empleado->nombre;?></td>

                    </tr>

                    <tr>

                        <td width="50%" align="left"><strong>Codición de Pago: </strong> <?= $proforma->prof_condicion_pago?></td>                        
                        <td><strong>Plazo de Entrega: </strong><?= $proforma->prof_plazo_entrega?></td>
                    </tr>            
                    </tbody></table>                                
            </div><br>

<!--codigo para validar que la proforma tiene activado el mostrar imagen  y le damos un colspan de dos-->
<?php if($proforma->prof_mostrar_imagen == 1 ){
 $numero = 2;
 }else
 {
    $numero = 1;
 }
  ?>

            <div class="tabla_borde">

                <table width="100%" border="0" cellpadding="5" cellspacing="0">

                    <tbody>

                        <tr>

                            <td align="center" class="bold">Cantidad</td>

                            <td align="center" class="bold">Unidad</td>

                            <td align="center" colspan="<?php echo $numero ?>" class="bold">Descripción</td>

                            <td align="right" class="bold">Valor Unitario</td>

                            <!--td align="center" class="bold">Desc. Uni.</td>-->

                            <td align="right" class="bold">Valor Total</td>

                        </tr>

                        <?php foreach($detalles as $item):?>

                        <tr class="border_top">                            
                            <td align="center">

                                <?php echo $item->profd_cantidad?>

                            </td>

                           <td align="center" >

                                <span><?php echo $item->medida_nombre?></span>

                            </td>

                            <td align="left" width="250px">
                              <!--este codigo es para que no se distorcione el pdf cuando la descripcion es demasiado grande-->
                         <div style="word-wrap: break-word;line-height: 1.0em">
                           <?php echo $item->profd_descripcion?>
                          </div>

                            </td>
                            <!-- codigo para mostrar la imagen le puse un td mas dentro del div-->
  <?php if($proforma->prof_mostrar_imagen == 1 && $item->prod_imagen != ''){?> 
                                 <td>
                                  <div align="right" style="display:inline-block;">       <!--insertamos la imagen-->
                                   <img  src="<?= 'images/productos/'.$item->prod_imagen;?>" width="100" height="100" ALIGN="center">
                                  <!--  <div  style="background-image: url('<?= 'images/productos/'.$item->prod_imagen;?>'); width: 100; height: 100; display: inline-block; ">-->      
                                  </div>
                                  </td>  

                                 <?PHP } elseif($proforma->prof_mostrar_imagen == 1)
                                 {?> 
                                <td></td>
                                  <?php }?>

      
                            <td align="right">

                            <?php echo $moneda->simbolo?> <?php echo number_format($item->profd_precio_unitario,2)?>

                            </td>

                            <!--<td align="center">

                                <?php echo $moneda->simbolo?> <?php echo $item->profd_descuento?>

                            </td>-->

                            <td align="right">

                                <?php echo $moneda->simbolo?> <?php echo number_format( $item->profd_subtotal,2)?>

                            </td>

                        </tr>

                        <?php endforeach?>



                    </tbody>

                </table></div>

            <table width="100%" border="0" cellpadding="0" cellspacing="0">

                <tbody><tr>

                    <td width="70%"  >
                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:12px" text-align="center"><strong>SON: <?php echo $proforma->total_letras?></strong></span><br><br>
                        <span><b>OBSERVACIONES: </b></span> 
                          <!--este codigo es para que no se distorcione el pdf cuando la descripcion es demasiado grande-->
                         <div style="word-wrap: break-word;line-height: 1.2em">
                           <?php echo $proforma->prof_doc_observacion?> 
                          </div>
                       
                     </td> 
                                       
                    <td width="60%" valign="top">

                        <br>

                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-valores-totales">

                            <tbody>
                                <tr>
                                <td align="right"><strong>Subtotal:</strong></td>
                                <td width="140" align="right"><span><?php echo $moneda->simbolo?> <?php echo number_format($proforma->prof_doc_subtotal,2)?></span></td>
                            </tr>                            
                            <tr>
                                <td align="right"><strong>IGV</strong></td>
                                <td width="140" align="right"><span><?php echo $moneda->simbolo?> <?php echo number_format($proforma->prof_doc_igv,2)?></span></td>
                            </tr>        
                            <tr>
                                <td align="right"><strong>Total a Pagar:</strong></td>
                                <td width="140" align="right"><span><?php echo $moneda->simbolo?> <?php echo number_format($proforma->prof_doc_total,2)?></span></td>
                            </tr>
                            </tbody>

                        </table>

                    </td>

                </tr>

                </tbody></table>
               

    <table width="100%">
        <tbody>
            <tr>
                <td>
                   <div align="left" style="width: 90%" class="datos_totales" style="font-size:10px">

                BANCO DE CREDITO<br>

                CUENTA EN SOLES / 4302612067-0-48<br>

                CUENTA EN SOLES CCI / 00243000261206704879<br>



                CUENTA DOLARES / 4302671835-0-74<br>

                CUENTA DOLARES CCI / 00243000267183517470<br>

                CUENTA DETRACION / 00-141-112686

            </div><br>

            <div align="center" style="width: 90%" class="datos_totales" style="font-size:10px" >
                Validez de Cotización 05 días <br>                

            </div>
            <div style="font-size:10px">
                <a href="#">ventas@ztagroup.com.pe</a><br>
                <a href="#">www.ztagroup.com.pe</a>
            </div>   
                </td>
            </tr>
        </tbody>
    </table>
              
 

</body></html>