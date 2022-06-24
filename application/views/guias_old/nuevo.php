

<style type="text/css">
   .material-switch > input[type="checkbox"] {        
    display: none;  
    height: 0;
}
    .material-switch > label {
    cursor: pointer;
    height: 0px;
    position: relative; 
    width: 50px;  
}
    .material-switch > label::before {
    background: rgb(0, 0, 0);
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    content: '';
    height: 16px;
    margin-top: 25px;
    position:absolute;
    opacity: 0.3;
    transition: all 0.4s ease-in-out;
    width: 50px;
}
    .material-switch > label::after {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    content: '';
    height: 24px;
    left: -4px;
    margin-top: 25px;
    position: absolute;
    top: -4px;
    transition: all 0.3s ease-in-out;
    width: 24px;
}
    .material-switch > input[type="checkbox"]:checked + label::before {
    background: inherit;
    opacity: 0.5;
}
    .material-switch > input[type="checkbox"]:checked + label::after {
    background: inherit;
    left: 30px;
}

    /* Agregando Inputs */
    .input-group {width: 100%;}
    .input-group-addon { min-width: 180px;text-align: right;}    
    
    /* Estilos Comprobante*/
    .clase_tahoma{
        /*font-family: Helvetica, Verdana, Segoe, sans-serif;
        //font-family: italic normal 400 16px/22px Arial, Verdana, Sans-serif;*/
        font-size: 13px;
    }    
    .panel-title{
        font-size: 13px;
        font-weight: bold;
    }    
    
    
    /* AGREGADO VALIDA */    
    .fila-base{
        display: none;        
    }                	
     /* SPAN */     
        #btnEdit{
          margin:-10px 0 0 20px;
        }        
</style>
<div id="mensaje"></div>
     
<div class="container-fluid">
<form id="formComprobante" class="form-horizontal" role="form" autocomplete="off">
    <input type="hidden" name="guiaId" id="guiaId" value="<?php echo $guia->id?>" >
    <input type="hidden" name="facturaId" id="facturaId" value="<?php echo $guia->comprobante_id?>" >
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <div class="row">
            <div style="text-align: center"><h3>GUÍAS - <b><?= $empresa[0]['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>         
    </div>
    <div class="container" style="width: 94%;margin: 0 auto;">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Datos Guía</div>
                    </div>
                    <div class="panel-body">
                        <div class="form">
                            <div class="row">
                               <div class="col-sm-4" >
                                    <label># Factura</label>
                                    <div class="input-group">
                                        <input type="text" name="numero_factura" id="numero_factura" class="form-control" value="<?php echo $guia->numero_factura?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btn_buscar_factura"><i class="glyphicon glyphicon-search"></i></button>
                                        </span>
                                    </div>
                                    
                                </div>
                                <div class="col-sm-4">
                                    <label>Serie</label>
                                    <input type="text" name="serie" id="serie" class="form-control" value="<?php echo $guia->guia_serie?>">
                                </div>
                                <div class="col-sm-4">
                                    <label>Numero</label>
                                    <input type="text" name="numero" id="numero" class="form-control" value="<?php echo $guia->guia_numero?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Fecha Inicio Traslado</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo $guia->fecha_inicio_traslado?>">
                                </div>
                                <div class="col-sm-8">
                                    <label>Motivo Traslado</label>
                                    <select name="motivo" class="form-control">
                                    <?php foreach($motivos as $motivo):?>
                                    <option value="<?php echo $motivo->id?>" <?php if($motivo->id==$guia->motivo_traslado):?> selected <?php endif?> ><?php echo $motivo->descripcion?></option>
                                    <?php endforeach?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Datos Transporte</div>
                    </div> 
                    <div class="panel-body">
                        <div  class="form">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Ruc Transporte</label>
                                    <input type="number" name="transporte_ruc" id="transporte_ruc" class="form-control" value="<?php echo $guia->transporte_ruc?>" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Razón Social Transporte</label>
                                    <input type="text" name="transporte_razon_social" id="transporte_razon_social" class="form-control" value="<?php echo $guia->transporte_razon_social?>">
                                </div>
                            </div>
                        </div>
                    </div>               
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Datos Envío</div>
                    </div>
                    <div class="panel-body">
                        <div class="form">
                            <div class="row">
                                <div  class="col-sm-6">
                                    <label>Ruc Destinatario</label>
                                    <input list="list_ruc" type="text" name="destinatario_ruc" id="destinatario_ruc" class="form-control" value="<?php echo $guia->destinatario_ruc?>" >
                                    <datalist id="list_ruc">
                                      
                                    </datalist>
                                </div>
                                <div class="col-sm-6">
                                    <label>Razón Social Destinatario</label>
                                    <input list="list_razon" type="text" name="destinatario_razon_social" id="destinatario_razon_social" class="form-control" value="<?php echo $guia->destinatario_razon_social?>">
                                     <datalist id="list_razon">
                                      
                                    </datalist>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Punto Partida</label>
                                    <input type="text" name="partida_direccion" id="partida_direccion" class="form-control" value="<?php echo $guia->partida_direccion?>">
                                </div>
                                <div class="col-sm-6">
                                    <label>Punto Llegada</label>
                                    <input type="text" name="llegada_direccion" id="llegada_direccion" class="form-control" value="<?php echo $guia->llegada_direccion?>">
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Datos Vehìculo</div>
                    </div>
                    <div class="panel-body">
                        <div class="form">
                            <div  class="row">
                                <div class="col-sm-4">
                                    <label>Placa</label>
                                    <input type="text" name="vehiculo_placa" id="vehiculo_placa" class="form-control" value="<?php echo $guia->vehiculo_placa?>">
                                </div>
                                <div class="col-sm-4">
                                    <label>Marca</label>
                                    <input type="text" name="vehiculo_marca" id="vehiculo_marca" class="form-control" value="<?php echo $guia->vehiculo_marca?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Licencia</label>
                                    <input type="text" name="vehiculo_licencia" id="vehiculo_licencia" class="form-control" value="<?php echo $guia->vehiculo_licencia?>">
                                </div>
                                <!--<div class="col-sm-4">
                                    <label>Constancia</label>
                                    <input type="text" name="vehiculo_constancia" id="vehiculo_constancia" class="form-control" value="<?php echo $guia->vehiculo_constancia?>">
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Datos Generales</div>
                    </div>
                    <div class="panel-body">
                        <div class="form">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Peso Bruto Total</label>
                                    <input type="number" name="peso_total" id="peso_total" class="form-control" value="<?php echo $guia->peso_total?>">
                                </div>
                                <div class="col-sm-4">
                                    <label>Número de Bultos</label>
                                    <input type="number" name="numero_bultos" id="numero_bultos" class="form-control" value="<?php echo $guia->numero_bultos?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="padding-top:20px;">                
            <div class="col-lg-8">
                <div class="panel panel-info" >  
                    <div class="panel-heading">
                        <div class="panel-title">CONCEPTOS DEL COMPROBANTE</div>
                    </div>
                    <div class="panel-body">                        
                        <div class="row" id="valida">
                            <div class="col-lg-12">
                                <table id="tabla" class="table table-striped" <?php if(count($guia->detalles)==0):?> style="display:none" <?php endif?>>
                                    <thead>
                                        <tr>  
                                            <th>Codigo</th>                                              
                                            <th>Descripcion</th>                                                
                                            <th>Cant.</th>
                                            <th>Prec. Unit.</th>
                                        </tr>
                                    </thead>                    
                                    <tbody> 
                                    <?php foreach($guia->detalles as $item):?>
                                    <tr class="cont-item">
                                        <td class="col-3"><input type="text" name="codigo" class="form-control" id="codigo" value="<?php echo $item->prod_codigo?>" disabled></td>
                                        <td class="col-3"><input type="text" class="form-control descripcion-item" rows="2" id="descripcion" value="<?php echo $item->descripcion?>" name="descripcion[]"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $item->prod_id?>"></div></td>
                                        <td class="col-3"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="<?php echo $item->cantidad?>" ></td>
                                        <td class="col-3"><input type="number" id="precio" name="precio[]"  class="form-control precio" value="<?php echo $item->precio?>" ></td>
                                        <td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>
                                    </tr>
                                    <?php endforeach?>                                                                                             
                                    </tbody>                    
                                    </table>   
                                <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                            </div> 
                        </div>            
                        <div id="mostrar"></div>
                        <div id="uu"></div>
                    </div>                            
                </div>
            </div>                    
        </div>    

        <div class="row">
            <div class="input-group">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <!--<input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/> -->
                    <?php if($guia->id > 0):?>
                        <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Actualizar Guía"/>
                    <?php else:?>
                        <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Registrar Guía"/>
                    <?php endif?>
                    
                </div>
                    
        </div>
    
</form>

</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>
<script type="text/javascript">    
 
  $(function(){
     
     $("#destinatario_ruc").keyup(function(){
        var texto = $(this).val();
        $.getJSON("<?PHP echo base_url();?>index.php/guias/buscador_ruc",{texto})
         .done(function(json){
            
            var option = "";
            $.each(json,function(index,value){
               option+= '<option value="'+ value.ruc +'">';
            });

            $("#list_ruc").html(option);
           
        });

     })

     $("#destinatario_razon_social").keyup(function(){
        var texto = $(this).val();
        $.getJSON("<?PHP echo base_url();?>index.php/guias/buscador_razon",{texto})
         .done(function(json){

            var option = "";
            $.each(json,function(index,value){
               option+= '<option value="'+ value.razon_social +'">';
            });

            $("#list_razon").html(option);
           
        }) 
     });

     $("#destinatario_ruc").change(function(){
         var texto = $(this).val();
         $.getJSON("<?PHP echo base_url();?>index.php/guias/buscador_cliente_ruc",{texto})
          .done(function(json){
             $("#destinatario_ruc").val(json.ruc);
             $("#destinatario_razon_social").val(json.razon_social);
             $("#llegada_direccion").val(json.domicilio1);
          });
     });

     $("#destinatario_razon_social").change(function(){
         var texto = $(this).val();
         $.getJSON("<?PHP echo base_url();?>index.php/guias/buscador_cliente_razon",{texto})
          .done(function(json){
             $("#destinatario_ruc").val(json.ruc);
             $("#destinatario_razon_social").val(json.razon_social);
             $("#llegada_direccion").val(json.domicilio1);
          });
     });


     
  })
    



    //FUNCIONES FECHAS   
    //cmp.incluyeIgv=false;

    /*buscar item*/
    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/guias/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input   type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id" >';
                 _item.find('#data_item').html(data_item);
                 _item.find("#codigo").val(ui.item.codigo);
                 _item.find("#precio").val(ui.item.precio);
            },
            change : function(event,ui){
                if(!ui.item){
                    //si es nota no se pondrá vacio
                    var tipoDocumento = $("#tipo_documento").val();
                    if(tipoDocumento=='1' || tipoDocumento=='3')
                    {
                        this.value = '';
                        $('#item_id').val(''); 
                        $('#importe').val('');                        
                    }

                }
            }                
        });
    });     
    
    $(function(){
        $("#fecha").datepicker();
        $("#fecha_de_vencimiento").datepicker();
                

        $("#proveedor").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/compras/buscadorProveedor',
            minLength: 2,
            select: function(event, ui) {
                //console.log(ui);
                var data_prov ='<input type="hidden" value="' + ui.item.prov_id + '" name = "proveedor_id" id = "proveedor_id" >';
                $("#proveedor").val(ui.item.prov_razon_social);
                $('#data_prov').html(data_prov);
            },
            change:function(event, ui){

            }
        });        
      
    });   
    
    function serieChange(){
        /*var selec  = $("#serie option:selected").val();
        console.log(selec);
        var tipo_documento = 10;*/
        var url_ser = '<?= base_url()?>index.php/guias/selectUltimoReg';
        //alert(url_ser);
        //console.log(selec);
        $.ajax({
            url : url_ser,
            type: 'POST',
            
            dataType : 'JSON',
            success :  function(data){
                //$('#numero').val(parseInt(data.numero));
                //$('#serie').val((data.serie));
            }
        });

        
    }

    serieChange();

   //carga documentos para Notas de credito
    function cargaDocumentosNotasCredito(){
        var serie_selec = $('#serie option:selected').val();
        var cliente_id = $('#cliente_id').val();
        var facturas_cliente =
               '<label class="control-label">Documento a Modificar</label>' +
               '<select class="form-control input-sm" name="comp_adjunto" id="comp_adjunto">' +
               '</select>';
        $('#div_facturas_cliente').html(facturas_cliente);
        $("#comp_adjunto").load('<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + cliente_id + '/' + serie_selec);

        $('#tipo_ncredito').prop('disabled',false);
        $('#tipo_ndebito').prop('disabled',true);
    }

    //AGREGANDO FILA
    $(function(){       
        var fila = '<tr class="cont-item">'; 

                fila += '<td class="col-3"><input type="text" class="form-control" id="codigo" name="codigo" readonly></td>';
                fila += '<td class="col-sm-3"><input type="text" class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>';
                fila += '<td class="col-3"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" ></td>';
                fila += '<td class="col-3"><input type="number" id="precio" name="precio[]"  class="form-control precio"  ></td>';
                fila += '<td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>';

            fila += '</tr>';

        /*agregar descuento por item*/
        $("#agrega").on('click', function(){           
            agregarFila();
        });                

        //REMOVIENDO ITEMS
        $(document).on("click",".eliminar",function(){
        //$(this).parents().get(0).remove();
                $(this).parent().remove();
                if($("#tabla tbody tr").length === 0)
                    $("#tabla").css("display","none");      
                    //calcular();                
        });
        
        //Entrada Solo numeros
        $('#cantidad,#numero,#desc_uni').on('keydown',function(e){
            validNumericos(e);
        });


        //FUNCION AGREGAR FILA
        function agregarFila(){    
                $("#tabla").css("display","block");
               $("#tabla tbody").append(fila);
               //calcular();                            
               //Llamada Evento Chosen
               $('.tipo_igv').chosen({                
                   search_contains : true,
                   no_results_text : 'No se encontraton estos tags',                
               });    
        }



    });

    //buscar factura
    $("#btn_buscar_factura").click(function(e){
        e.preventDefault();
        var factura = $("#numero_factura").val();
        if(factura=='')
        {
            toast("error",1500,"Debe ingresar factura");
        }else{
            var datos={documento:factura};
            $.ajax({
                url:'<?PHP echo base_url()?>index.php/guias/buscarComprobanteGuia',
                method:'post',
                dataType:'json',
                data:datos,
                success:function(response){
                    if(response.status==STATUS_FAIL)
                    {
                        toast("error", 1500, "Documento no encontrado");
                    }else if(response.status==11){
                        toast("error", 2000, "Solo se permite facturas con stock de almacenes");
                    }else{
                        var datos = response.datos;
                        $("#partida_direccion").val(datos.partida_direccion);
                        $("#destinatario_ruc").val(datos.destinatario_ruc);
                        $("#destinatario_razon_social").val(datos.destinatario_razon_social);
                        $("#llegada_direccion").val(datos.llegada_direccion);
                        $("#facturaId").val(datos.factura_id);

                      
                        cargarProductos(datos.productos);
                    }
                }

            });
        }
    });
    function cargarProductos(productos)
    {
        var listaProductos = productos;
        
        if(listaProductos.length>0)
        {
            $("#tabla > tbody tr").remove(); 
            $.each(listaProductos, function(index, value){
                var html='';
                html += '<tr class="cont-item">';

                if(value.prod_codigo!=null){
                    html += '<td class="col-3"><input type="text" name="codigo[]" class="form-control" id="codigo" value="'+value.prod_codigo+'" readonly></td>';
                }else{
                    html += '<td class="col-3"><input type="text" name="codigo[]" class="form-control" id="codigo" value="none" readonly></td>';
                }
                    
                    html += '<td class="col-sm-3"><input type="text" class="form-control descripcion-item" rows="2" id="descripcion" value="'+value.descripcion+'" name="descripcion[]"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="'+value.prod_id+'"></div></td>';
                    html += '<td><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="'+value.cantidad+'" ></td>';
                    html += '<td><input type="number" id="precio" name="precio[]"  class="form-control precio" value="'+value.importe+'" ></td>';
                    html += '<td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>';
                html += '</tr>';
                $("#tabla > tbody").append(html);
            });
            
            $("#tabla").show();
        }
    }
    //guardar nota
    $("#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            url:'<?PHP echo base_url()?>index.php/guias/guardarGuia',
            method:'post',
            data:$("#formComprobante").serialize(),
            dataType:'json',
            beforeSend:function(){
                $(".has-error").removeClass("has-error");
            },
            success:function(response){
                if(response.status == STATUS_OK)
                {
                    toast("success",1500,"Guia registrada");
                    setTimeout(function(){
                        location.href='<?PHP echo base_url()?>index.php/guias/index';    
                    },500);
                     
                }
                if(response.status == STATUS_FAIL)
                {
                    if(response.tipo == '1')
                    {
                        toast("error",1500,"Faltan ingresar datos!");
                        var errores = response.errores;
                        $.each(errores,function(index, value){
                            $("#"+index).parent().addClass("has-error");
                        });
                    }
                    if(response.tipo == '2')
                    {
                        toast("error", 1500, response.msg);
                    }else{
                        toast("error", 1500, response.msg);
                    }
                }
            }
        });          
    });



    
</script>