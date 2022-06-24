

<style>
    #refresh img{
        margin-left: 50px;
    }
</style>
<p class="bg-info">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<div class="container">
    <form>    
        <h2>Lista de proformas: <?php echo $empresa[0]['empresa']?></h2>

            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <label>Cliente: </label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder="Cliente" value="<?= $proveedor_select;?>">
                    <div id="data_cliente">
                        <input type="hidden" name = "cliente_id" id = "cliente_id" >
                        <?php
                            if(isset($proveedor_select_id) && ($proveedor_select_id != '')){
                            echo '<input type="hidden" value="' . $proveedor_select_id . '" name = "cliente_id" id = "cliente_id" >';
                            }
                        ?>
                    </div>
                </div>                 
                <!-- <div class="col-xs-2">
                    <label>Serie Número</label>
                    <input type="text" class="form-control input-sm" id="serie_numero" name="serie_numero">
                </div> -->
                <div class="col-xs-6 col-md-6 col-lg-2">
                    <label>Fecha</label>
                    <input type="text" class="form-control input-sm" id="fecha" name="fecha" >
                </div> 
                <div class="col-xs-6 col-md-6 col-lg-2">
                    <label>Documento</label>
                    <input type="text" class="form-control input-sm" id="documento" name="documento" >
                </div>                 
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <br>
                    <input type="button" class="btn btn-primary " id="btn_search" value="Buscar">
                    <input type="button" class="btn btn-primary " id="btn_limpiar" value="Limpiar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                      
                </div>             
            </div> 
    </form>
</div>
<div class="row" align="right" style="padding-right: 25%;">
    <div class="col-xs-12 form-inline col-sm-6 col-lg-2" style="padding-bottom: 1rem;margin-top: 8px;">
        <label> Precio Unit. incluye igv</label>                            
        <input type="checkbox" name="incluye_igv" id="incluye_igv" <?php echo ($config->pu_igv==1)?"checked":"";?> style="padding-top:15px;">
    </div>    
    <a href="<?PHP echo base_url(); ?>index.php/proformas/nuevo/<?php echo $empresa['id']?>" class="btn btn-success">Nueva proforma</a>
     <a id="exportarExcel" class="btn btn-primary"> Exportar a Excel </a>
    <!--<a id="exportarExcel" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> EXCEL</a>-->
</div>
<div class="container-fluid">
    <br>
    <div id="grid"></div>    
</div>
<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">
    //Imprimir Boleto
   jQuery(document).ready(function($) {
        $('#exportarExcel').click(function() {
            var cliente =$('#cliente_id').val();           
            var fecha = $('#fecha').val();
            var documento = $('#documento').val();

            if(cliente  ==''){
                cliente =0;
            }
            if(fecha ==''){
                fecha = 0;
            }
            if(documento ==''){
                documento = 0;
            }            
            var url ='<?PHP echo base_url() ?>index.php/proformas/ExportarExcel/'+cliente+'/'+fecha+'/'+documento+'/';
            window.open(url, '_blank');

        });

    });
    /*button buscar*/
    $("#btn_search").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    /*button limpiar*/
    $("#btn_limpiar").click(function(e){
        e.preventDefault();
        $("#cliente_id").val("");
        $("#proveedor").val("");
        $("#serie_numero").val("");
        $("#fecha").val("");
        $("#documento").val("");
        dataSource.read();
    });    
    // AUTOCOMPLETE CLIENTE
    $(document).on('ready',function() {
        $("#cliente").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/proformas/buscadorCliente',
            minLength: 2,
            select: function(event, ui) {
            	console.log(ui.id);
                var data_cliente ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $("#cliente").val(ui.item.razon_social);
                $('#data_cliente').html(data_cliente);
            }
        });

    // FECHA JAVASCRIPT
    $("#fecha").datepicker();
                     
    });  

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/proformas/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            cliente:function(){
                                return $("#cliente_id").val();
                            },
                            serie_numero:function(){
                                return $("#serie_numero").val();
                            },
                            fecha_search:function(){
                                return $("#fecha").val();
                            },
                            documento:function(){
                            	return $("#documento").val()
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
    //console.log(dataSource);

    $("#grid").kendoGrid({
        dataSource: dataSource,
        height: 550,
        sortable: true,
        pageable: true,
        columns: [
                    {field:'prof_correlativo',title:'Nº COMPRA',width:'60px'},
                    {field:'razon_social',title:'CLIENTE',width:'160px'},                    
                    {field:'prof_doc_fecha',title:'FECHA',width:'80px'},
                    {field:'moneda',title:'MONEDA',width:'60px'},
                    {field:'prof_doc_subtotal',title:'SUB TOTAL',width:'60px'},
                    {field:'prof_doc_igv',title:'IGV',width:'60px'},
                    {field:'prof_doc_total',title:'TOTAL',width:'60px'},                    
                    {field:'boton_editar', title:'&nbsp;',width:'40px',template:"#= boton_editar #"},
                    {field:'boton_eliminar', title:'&nbsp;',width:'40px',template:"#= boton_eliminar #"},
                    {field:'btn_ticket',title:'TICKET',width:'60px',template:"#= btn_ticket #"},
                    {field:'boton_pdf', title:'A4;',width:'40px',template:"#= boton_pdf #"},
                    //{field:'notap_total', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},
        ],
        detailTemplate: '<div class="lista_proformas"></div>',
        detailInit: detailInit,        
        dataBound:function(e){
            //modificar compra
            $(".btn-editar").click(function(e){
               var idProforma = $(this).data('id');
               location.href="<?php echo base_url()?>index.php/proformas/editar/"+idProforma;
            });
            //eliminar compra
            $(".btn-eliminar").click(function(e){
                var idProforma = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/proformas/eliminar/'+idProforma
                $.confirm({
                    title: 'Confirmar',
                    content: msg,
                    buttons: {
                        confirm:{
                            text:'aceptar',
                            btnClass: 'btn-blue',
                            action:function(){
                                $.ajax({
                                    url:url,
                                    dataType:'json',
                                    method:'get',
                                    success:function(response){
                                        if(response.status == STATUS_OK)
                                        {
                                            toast('success', 1500, 'proforma eliminada');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar proforma.');
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            
                        }
                    }
                });                
            });                            
        }
    }); 

    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_proformas").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/proformas/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                prof_id:e.data.prof_id
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
                { field: "profd_descripcion", title:"PRODUCTO", width: "120px" },
                { field: "profd_precio_unitario", title:"PRECIO UNITARIO", width: "50px" },
                { field: "profd_cantidad", title:"CANTIDAD",width:"70px" },
                { field: "profd_subtotal", title:"SUB TOTAL",width:"70px" }
            ],
            dataBound:function(e){
            }
        });
    }      


    ///INCLUYE IGV
    $("#incluye_igv").click(function(){
        if( $(this).is(':checked') ) {
           var valor = 1;
        }else{
           var valor = 0;
       }
      window.location.href = "<?PHP echo base_url()?>index.php/proformas/estado_igv/"+valor;                
    });             
</script>
