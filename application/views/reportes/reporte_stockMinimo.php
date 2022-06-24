<style type="text/css">
	
	label{
		width: 100%;
	}
</style>

<br><br>
<script type="text/javascript">
	
	$(document).ready(function(){
		$("#fecha_desde").datepicker();
		$("#fecha_hasta").datepicker();		

		$("#producto").autocomplete({
			source: '<?= base_url()?>index.php/comprobantes/buscador_itemC',
			minLength: 2,
			select: function(event,ui){
				data_pro = '<input type="hidden" name="producto_id" id="producto_id" value="'+ ui.item.id+'">';
				$("#data_pro").html(data_pro);
			}
		});
	});
	
</script>
<div class="container-fluid">
	<h3>STOCK MINIMO</h3><br>
	<form id="formReporte">
	<div class="col-xs-2 col-md-2 col-lg-2"></div>
	<div class="col-xs-8 col-md-8 col-lg-8">
		<div class="row text-left" >
	<div class="row">
		<div class="col-xs-6 col-md-3 col-lg-4">
			<label>Categoria
			<select class="form-control" name="categoria" id="categoria">
				<option value="">SELECCIONE</option>
				<?PHP foreach($categoria as $value){?>
				<option value="<?= $value->cat_id?>"><?= $value->cat_nombre?></option>
				<?PHP }?>
			</select>		
			</label>
		</div>	
		<div class="col-xs-6 col-md-3 col-lg-4">
			<label>Producto
				<input type="text" class="form-control" name="producto" id="producto">
				<div id="data_pro"><input type="hidden" name="producto_id" id="producto_id" value=""></div>
			</label>
		</div>				
		<div class="col-xs-6 col-md-2 col-lg-1">
			<label>&nbsp;
				<button id="buscarReporte" type="button" class="btn btn-primary btn-block">BUSCAR</button>
			</label>
		</div>	
		<div class="col-xs-6 col-md-2 col-lg-1">
			<label>&nbsp;<br>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
			</label>
		</div></div>
	</div>
</div>
	</form>
</div>
<div class="container-fluid"> 
	<br><br>
	<div class="col-xs-2 col-md-2 col-lg-2"></div>
	<div class="col-xs-8 col-md-8 col-lg-8">
		<div id="contenido"></div>
	</div>
</div>


<script type="text/javascript">
	
	$(document).ready(function(){

		function buscarReporte(){
			$.ajax({
				url: '<?= base_url()?>index.php/reportes/reporte_stockMinimo_bs',
				dataType: 'HTML',
				method: 'POST',
				data: $("#formReporte").serialize(),
				success: function(response){
					$("#contenido").html(response);
				}
			});
		}
		buscarReporte();


		$(document).on("click","#buscarReporte",function(){
			buscarReporte();
		});

		$('#exportar_repo').click(function() {        
        datos = $("#formReporte").serialize();   

        categoriaText = ($("#categoria option:selected").val() != '') ? $("#categoria option:selected").text() : '';
        productoText = ($("#producto_id").val() != '') ? $("#producto").val() : '';
                        
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarStockMinimo?'+datos+'&categoriaText='+categoriaText+'&productoText='+productoText;
        window.open(url, '_blank');

    });
});
</script>