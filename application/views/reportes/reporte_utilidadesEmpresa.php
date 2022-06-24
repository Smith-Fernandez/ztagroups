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
	});
	

</script>
<div class="container">
	<h3>REPORTE UTILIDADES</h3><br>
	<form id="formReporte">
	<div class="row">
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Fecha Desde
				<input class="form-control" name="fecha_desde" id="fecha_desde">
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Fecha Hasta
				<input class="form-control" name="fecha_hasta" id="fecha_hasta">
			</label>
		</div>	
		<div class="col-xs-2 col-md-2 col-lg-2">
			<label>&nbsp;
				<button id="buscarReporte" type="button" class="btn btn-primary btn-block">BUSCAR</button>
			</label>
		</div>	
		<div class="col-xs-2 col-md-2 col-lg-2">
			<label>&nbsp;<br>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
			</label>
		</div>
	</div>		
	</form>
</div>
<div class="container-fluid"> 
	<br><br>
	<div id="contenido"></div>
</div>


<script type="text/javascript">
	
	$(document).ready(function(){


		function buscarReporte(){
			$.ajax({
				url: '<?= base_url()?>index.php/reportes/reporte_utilidadesEmpresa_bs',
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
               
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarReporteUtilidad?'+datos;
        window.open(url, '_blank');

    });

});
</script>