<style>
    #refresh img{
        margin-left: 50px;
    }
    #btn_notaPedido{
        display: none;
    }
</style>
<div class="container">
    <form id="formNotaVenta">    
        <h2>Lista de Notas: <?php echo $empresa[0]['empresa']?></h2>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-4">
                    <label>Fecha de Inicio:</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_inicio" id="fecha_inicio" value="<?PHP
                        if(isset($_POST['fecha_inicio'])){
                            echo $_POST['fecha_inicio'];
                        }else{
                            //echo date('d-m-Y');
                        }?>" placeholder="Desde">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-4">
                    <label>Fecha de Fin:</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" value="<?PHP
                        if(isset($_POST['fecha_fin'])){
                            echo $_POST['fecha_fin'];
                        }else{
                            //echo date('d-m-Y');
                        }?>" placeholder="Hasta">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <label>Cliente:</label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder="Cliente" value="<?= $cliente_select;?>">
                    <div id="data_cli">
                        <?php
                            if(isset($cliente_select_id) && ($cliente_select_id != '')){
                            echo '<input type="hidden" value="' . $cliente_select_id . '" name = "cliente_id" id = "cliente_id" >';
                            }
                        ?>
                    </div>
                </div> 
                <div class="col-xs-6 col-md-6 col-lg-2">
                    <label>Correlativo</label>
                    <input type="text" class="form-control input-sm" id="correlativo" name="correlativo">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-2">
                    <label>Usuario</label>
                    <select class="form-control" id="vendedor_id">
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($empleados as $value_empleado){?>
                        <option value="<?php echo $value_empleado['id']?>"><?php echo $value_empleado['apellido_paterno']." ".$value_empleado['apellido_materno'].", ".$value_empleado['nombre'] ?></option>
                        <?php
                           }
                        ?>
            </select>
                </div> 
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <br>
                    <input type="button" class="btn btn-primary col-xs-5 " id="btn_search" value="Buscar">
                    <input type="button" class="btn btn-primary col-xs-5 col-xs-offset-1" id="btn_limpiar" value="Limpiar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                      
                    <input type="hidden" value="<?php echo $notap_id;?>" name="notap_id" id="notap_id">
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8"><br>
                    <div id="totalRows"></div>
                    <div id="tributarios"></div>                    
                    <div id="activos"></div>
                    <div id="anulados"></div>
                    
                </div>
                          
        <div class="col-xs-12 col-md-12 col-lg-4">
            <br>
            <a  href="<?PHP echo base_url(); ?>index.php/notas/nuevo/<?php echo $empresa['id']?>" class="btn btn-success col-xs-5">Nueva Nota</a>
            <a id="exportarExcel" class="btn btn-primary col-xs-5 col-xs-offset-1"><i class="glyphicon glyphicon-save"></i> Reporte Detallado</a>
        </div>
        <div class="col-lg-4">            
        </div>
        <div class="col-lg-4">
        </div>
        <div class="col-xs-12 col-md-12 col-lg-4" >
            <br>
            <a id="exportarExcel_rd" class="btn btn-primary col-xs-5"><i class="glyphicon glyphicon-save"></i> Reporte Listado</a>                    
        </div>
    </div>
    </form>
</div>
<p class="bg-info" style="font-weight: 600;color:#000;text-align: center;padding: 10px 0;font-size: 16px;margin-top:10px;">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>


<div class="container-fluid">
    <button type="button" id="btn_notaPedido" data-toggle="modal" data-target="#myModal">MODAL</button>
     <div class="col-xs-12 form-inline col-sm-6 col-lg-2" style="padding-bottom: 1rem;margin-top: 8px;">
        <label> Ticket auto.</label>                            
        <input type="checkbox" name="ticket_auto" id="ticket_auto" <?php echo ($config->ticket_auto == 1)?"checked":"";?> style="padding-top:15px;">
    </div>
    <br>
    <div class="col-xs-12 col-md-12 col-lg-12">
        <div id="grid"></div> 
    </div>  
</div>
<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">


     // FECHA JAVASCRIPT            
        var fecha = new Date(); //Fecha actual
        var mes = fecha.getMonth()+1; //obteniendo mes
        var dia = 1; //obteniendo dia
        var ano = fecha.getFullYear(); //obteniendo año
        if(dia<10)
          dia='0'+dia; //agrega cero si el menor de 10
        if(mes<10)
          mes='0'+mes //agrega cero si el menor de 10
          fecha_inicio =dia+"-"+mes+"-"+ano;

          ultimoDiaMes = new Date(ano, mes, 0);
          fecha_fin = ultimoDiaMes.getDate()+"-"+mes+"-"+ano;
            
          $("#fecha_inicio").val(fecha_inicio);
          $("#fecha_fin").val(fecha_fin);
            //var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
          $("#fecha_inicio").datepicker({
                dateFormat: 'dd-mm-yy' ,                
                firstDay: 1
          });

          $("#fecha_fin").datepicker({
                dateFormat: 'dd-mm-yy',                
                firstDay: 1
          });          

    $('#exportarExcel').click(function(){
                console.log($("#cliente_id").val());
                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var correlativo = ($("#correlativo").val() =='' ) ? null :  $("#correlativo").val();
                var fecha_inicio = ( $("#fecha_inicio").val() == "") ?  null : $("#fecha_inicio").val();
                var fecha_fin = ( $("#fecha_fin").val() == "") ?  null : $("#fecha_fin").val();
                var vendedor_id = $("#vendedor_id").val();
                                
                    var url = '<?PHP echo base_url() ?>index.php/notas/exportarExcel?cliente=' + cliente_id + '&fecha_inicio=' +  fecha_inicio + '&fecha_fin=' +  fecha_fin + '&correlativo=' + correlativo + '&vendedor_id=' + vendedor_id;                            
                
                window.open(url, '_blank');
            });

     $('#exportarExcel_rd').click(function(){
                console.log($("#cliente_id").val());
                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var correlativo = ($("#correlativo").val() =='' ) ? null :  $("#correlativo").val();
                var fecha_inicio = ( $("#fecha_inicio").val() == "") ?  null : $("#fecha_inicio").val();
                var fecha_fin = ( $("#fecha_fin").val() == "") ?  null : $("#fecha_fin").val();
                var vendedor_id = $("#vendedor_id").val();
                                
                    var url = '<?PHP echo base_url() ?>index.php/notas/exportarExcel_rd?cliente=' + cliente_id + '&fecha_inicio=' +  fecha_inicio + '&fecha_fin=' +  fecha_fin + '&correlativo=' + correlativo + '&vendedor_id=' + vendedor_id;                            
                
                window.open(url, '_blank');
            });

    
    /*button buscar*/
    $("#btn_search").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    /*button limpiar*/
    $("#btn_limpiar").click(function(e){
        e.preventDefault();
        $("#formNotaVenta")[0].reset();
        $("#cliente_id").val('');
        dataSource.read();
    });    
    // AUTOCOMPLETE CLIENTE
    $(document).on('ready',function() {
        $("#cliente").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_cliente',
            minLength: 2,
            select: function(event, ui) {
                var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);
            }
        });

    // FECHA JAVASCRIPT
    $("#fecha").datepicker();
    $("#fecha_inicio").datepicker();
    $("#fecha_fin").datepicker();
                     
    });  

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/notas/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            cliente_search:function(){
                                return $("#cliente_id").val();
                            },
                            correlativo_search:function(){
                                return $("#correlativo").val();
                            },                             
                            vendedor_id:function(){
                            return $("#vendedor_id").val();
                            },
                            fecha_inicio:function(){
                            return $("#fecha_inicio").val();
                            },
                            fecha_fin:function(){
                            return $("#fecha_fin").val();
                            }                            
                        }
                    }
                }
            },
            schema:{
                data:'data',
                total:'rows'
            },
            pageSize: 20,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true
                             
    });
    $("#grid").kendoGrid({
        dataSource: dataSource,        
        height: 550,
        sortable: true,
        pageable: true,
        columns: [
                    {field:'notap_correlativo',title:'Nº PEDIDO',width:'60px'},
                    {hidden:'totalGenerado',title:'Nº PEDIDO',width:'60px',template:"#= totalGenerado #"},
                    {hidden:'totalAnulado',title:'Nº PEDIDO',width:'60px',template:"#=totalAnulado #"},
                    {hidden:'totalTributario',title:'Nº PEDIDO',width:'60px',template:"#=totalTributario #"},
                    {field:'razon_social',title:'CLIENTE',width:'160px'},
                    {field:'ruc',title:'RUC/DNI',width:'70px'},
                    {field:'notap_fecha',title:'FECHA',width:'60px'},                    
                    {field:'moneda',title:'MONEDA',width:'60px'},                    
                    {field:'notap_total',title:'TOTAL',width:'60px'},
                    {field:'empleado',title:'USUARIO',width:'60px'},
                    {field:'boton_editar', title:'Editar',width:'40px',template:"#= boton_editar #"},
                    {field:'btn_ticket_imp', title:'Ticket',width:'40px',template:"#= btn_ticket_imp #"},
                    {field:'btn_ticket', title:'Ticket',width:'40px',template:"#= btn_ticket #"},
                    {field:'boton_pdf', title:'A4',width:'40px',template:"#= boton_pdf #"},
                    {field:'boton_popup', title:'ENVIAR',width:'40px',template:"#= boton_popup #"},
                    {field:'boton_cTributario', title:'Convertir Tributario',width:'60px',template:"#= boton_cTributario #"},                                        
                      {
                        title:'ESTADO',
                        width:'100px',
                        template:"#= btn_action #",
                        attributes: {
                          "class": "gridcell",
                          style: "overflow: inherit;position:relative;"
                        }
                    }
        ],
        detailTemplate: '<div class="lista_notas"></div>',
        detailInit: detailInit,        
        dataBound:function(e){
            
            

            if($("#notap_id").val() != '')
            $('#btn_notaPedido').trigger('click');
            
            var anulados = 0;
            var activos = 0;
            var tributario = 0;
            var data = dataSource.data();
            $.each(data,function(e, row){
                if(row.notap_estado == 2)//ANULADO
                {
                    $('tr[data-uid="' + row.uid + '"] ').css("background-color", "rgb(241, 204, 206)");
                    anulados++;                    
                }
                if(row.notap_estado == 3)
                {
                    $('tr[data-uid="' + row.uid + '"] ').css("background-color", "#d9edf7"); 
                    tributario++;                   
                }
                
            });
             
            // SMITH FERNANDEZ   15/04/2021
             //capturar el evento para crear pdf
                 $(".show_pdf").click(function(e) {               
                var idNota = $(this).data("id");
             javascript:window.open("<?PHP echo base_url()?>index.php/notas/decargarPdf/"+idNota,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            }); 

            //capturar el evento para ticket una ventana emergente
                 $(".show_pdf_ticket").click(function(e) {               
                var idNota = $(this).data("id");
             javascript:window.open("<?PHP echo base_url()?>index.php/notas/decargarPdf_ticket/"+idNota,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            }); 



             
           
            //TOTAL DE COMPROBANTES 17-02-2021 - ALEXANDER FERNANDEZ
            var totalRecords = dataSource.total();
            $("#totalRows").html('');
            $("#totalRows").html('<p style="text-align:center;font-size:16px;background:#343333;color: white"><b>TOTAL COMPROBANTES: '+totalRecords+'</b></p>');
            
            // forzando el boton generado
            $(".btn-generado").click(function(e){
               var activos = $(this).data('id');
            $("#activos").html('');
            $("#activos").html('<p  style="text-align:center;font-size:16px;background-color:#5cb85c;color:white"><b>TOTAL ACTIVOS : '+activos+'</b></p>');
            });
            $('.btn-generado').trigger('click');

            // forzando boton tributario
            $(".btn-Tributario").click(function(e){
               var tributario = $(this).data('id');
            $("#tributarios").html('');
            $("#tributarios").html('<p  style="text-align:center;font-size:16px;background-color:#337ab7;color:white"><b>TOTAL TRIBUTARIOS : '+tributario+'</b></p>');
            });
            $('.btn-Tributario').trigger('click');

            // forzando boton anulados
             $(".btn-Anulado").click(function(e){
               var anulados = $(this).data('id');
            $("#anulados").html('');
            $("#anulados").html('<p style="text-align:center;font-size:16px;background-color:#E74C3C;color:white;"><b>TOTAL ANULADOS : '+anulados+'</b></p>');
            });
            $('.btn-Anulado').trigger('click');
            
            //modificar nota
            $(".btn-editar").click(function(e){
               var idNota = $(this).data('id');
               location.href="<?php echo base_url()?>index.php/notas/editar/"+idNota;
            });
             
            $(".btn-anular").click(function(e){//ANULAR NOTA DE PEDIDO - 27-03-2021
               var idNota = $(this).data('id');
               if(confirm("¿Eliminar nota de pedido?")){
                location.href="<?php echo base_url()?>index.php/notas/anular_nota/"+idNota;
               }               
            });          
        }
    }); 

    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_notas").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/notas/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                notap_id:e.data.notap_id
                            }
                        }
                    }
                },
                schema:{
                    data:'data',
                    total:'rows'
                },                
                serverPaging: true,
                serverSorting: true,
                serverFiltering: true,
                pageSize: 7,
            },
            scrollable: false,
            sortable: true,
            pageable: true,
            columns: [
                { field: "notapd_descripcion", title:"PRODUCTO", width: "120px" },
                { field: "notapd_precio_unitario", title:"PRECIO UNITARIO", width: "50px" },
                { field: "notapd_cantidad", title:"CANTIDAD",width:"70px" },
                { field: "notapd_total", title:"SUB TOTAL",width:"70px" }
            ],
            dataBound:function(e){                
            }
        });
    }

    //MODAL DE ENVIO DE NOTAPEDIDO 03-08-2020 ALEXANDER FERNANDEZ
    $(document).on('click',"#btn_notaPedido",function(){
        var notap_id =  $("#notap_id").val();
        $("#myModal").load('<?= base_url()?>index.php/notas/modal_envio_notaPedido/'+notap_id,{}); 

    });


     $(".btn-anular").click(function(e){//ANULAR NOTA DE PEDIDO - 27-03-2021
               var idNota = $(this).data('id');
               if(confirm("¿Eliminar nota de pedido?")){
                location.href="<?php echo base_url()?>index.php/notas/anular_nota/"+idNota;
                dataSource.read();
               }               
    });  
     

    function send_anulacionPASSWORD(nota_id){    
            $.confirm({
                title: 'Anular Comprobante!',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Ingrese Password</label>' +
                '<input type="password" placeholder="Password" class="password form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Confirmar',
                        btnClass: 'btn-blue',
                        action: function () {
                            var password = this.$content.find('.password').val();

                            $.getJSON("<?PHP echo base_url(); ?>index.php/notas/anular_nota",{nota_id,password})
                             .done(function(json){

                                if(json['password'] == -1){
                                        toast("error",6000,'CONTRASEÑA INCORRECTA');
                                } else {
                                    if(json['go'] == 1){
                                        toast("info",1000, 'COMPROBANTE ANULADO');                                                                                                                  
                                        dataSource.read();
                                    }}
                            });
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {                    
                }
            });        
        }

    function send_anulacionPASSWORD_2(nota_id){                

        $.getJSON("<?PHP echo base_url(); ?>index.php/notas/anular_nota",{nota_id})
                             .done(function(json){                                
                                    if(json['go'] == 1){
                                        toast("info",1000, 'COMPROBANTE ANULADO');    
                                        dataSource.read();
            }
        });
    }                   

        /*Imprimir ticket*/                        
    var PrintWindow;
            function openWindow(id) {
              PrintWindow = window.open('<?PHP echo base_url() ?>index.php/notas/data_impresion_pos_ticket/'+id,'popup', 'width=400px,height=800px');
              setTimeout(function() {closeOpenedWindow();}, 400);
            }
            function closeOpenedWindow() {
              PrintWindow.close();
    }


    ///TICKET AUTO
    $("#ticket_auto").click(function(){        
        if( $(this).is(':checked') ) {
            var valor = 1;
        }else {
            var valor = 0;
        }
        window.location.href = "<?PHP echo base_url()?>index.php/notas/ticket_auto/"+valor;
    });
</script>
