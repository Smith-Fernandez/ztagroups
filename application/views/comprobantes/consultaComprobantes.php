<style type="text/css">
	
	.row{
		padding-top: 20px;
	}
</style>


<script type="text/javascript">
	$(document).ready(function(){
		$("#fecha_de_emision").datepicker();
	});	
</script>


<?PHP if($viene_de != 1){?>
<div class="container-fluid"><br><br><br>
	<div class="col-xs-2 col-md-2 col-lg-2">
	</div>
	<div class="col-xs-10 col-md-10 col-lg-10">
		<a href="<?PHP echo base_url() ?>" class="btn btn-success btn-sm" role="button">&nbsp;&nbsp;ATRÁS&nbsp;&nbsp;</a>
	</div>	
</div>
<?PHP }?>
<div class="container"><br><br><br><br><br><br>
	
	<h2>CONSULTA DE COMPROBANTES SUNAT</h2><br><br>
  
      <form id="formCajaMov">
	<div class="row">
	<div class="col-xs-4 col-md-4 col-lg-4">
		<label>Número de RUC del emisor *</label>
	</div>
		<div class="col-xs-6 col-md-6 col-lg-6">
				<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="numRuc" id="numRuc" placeholder="RUC">
		</div>
	</div>
	<div class="row">
	<div class="col-xs-4 col-md-4 col-lg-4">
		<label>Tipo de comprobante *</label>
	</div>	
	<div class="col-xs-6 col-md-6 col-lg-6">
		 <select  class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="tipo_documento" id="tipo_documento">
            <?PHP foreach ($tipo_documentos as $value) { 
            	if($value['id'] < 11) {?>
            		<option value = "<?PHP echo $value['codigo'];?>" <?php echo ($value['id'] == 1)?"selected":"";?>><?PHP echo $value['tipo_documento']?></option>
            <?PHP }}?>
          </select>
	</div>
	</div>
	<div class="row">		
	<div class="col-xs-4 col-md-4 col-lg-4">
		<label>Serie y número de comprobante *</label>
	</div>
	<div class="col-xs-3 col-md-3 col-lg-3">		
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="serie" id="serie" placeholder="Serie">
	</div>
	<div class="col-xs-3 col-md-3 col-lg-3">					
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="numero" id="numero" placeholder="Numero">		
	</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-md-4 col-lg-4">			 
			<label>Fecha de emisión *</label>
		</div>
		<div class="col-xs-6 col-md-6 col-lg-6">			
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="fecha_de_emision" id="fecha_de_emision" placeholder="Fecha de emisión">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-md-4 col-lg-4">			 
			<label> Importe total **</label>
		</div>
		<div class="col-xs-6 col-md-6 col-lg-6">
				<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="monto" id="monto" placeholder="Importe total">			
		</div>
	</div><br><br>
	<div class="row">
		<button type="button" id="btnBuscar" class="btn btn-primary btn-block btn-lg">CONSULTAR COMPROBANTE</button>
	</div>
</form>
</div>

<br><br><br>
<br><br><br>
<div class="container-fluid">
	<div id="totalRows"></div>
	<div id="tableComprobante"></div>
</div>


<script type="text/javascript">
	$("#btnBuscar").on("click",function(){

			$.ajax({
				url: '<?= base_url()?>index.php/sunnat/consulta_sunat',
				dataType: 'JSON',
				method: 'POST',
				data: $("#formCajaMov").serialize(),
				success: function(response){

					if(response.status == -1)//STATUS_FAIL
  					{
  						if(response.tipo == '1')
  						{
  							var errores = response.errores;
  							toast('error', 1500, 'Faltan ingresar datos.');
  							$.each(errores, function(index, value){
  								$("#"+index).parent().addClass('has-error');
  							});
  						}
			            if(response.tipo == '2')
			            {                
			                $("#totalRows").html('<p style="text-align:center;font-size:30px;background:#d9534f;color: white"><b>'+response.message+'</b></p>');
			            }
  					}
					if(response.status == 2){												
						$("#totalRows").html(response.result.estadoCpDes);
						$("#tableComprobante").html(response.result.tableComprobante);
					}
				}

			});
		});



	$("#serie").change(function () {		
            var tipo_documento = $("#tipo_documento option:selected").val();
            var serie = $("#serie").val();
           

            if (serie == 1) {
                $("#lbl_DNI_RUC").text('DNI');
                $("#ruc").attr("placeholder","DNI");
                $("#ruc").attr("maxlength","8");

                $("#lbl_RAZ_APE").text('Nombres');
                $("#razon_social").attr("placeholder","Nombres");
                $("#nombres").show();
            }else{
                $("#lbl_DNI_RUC").text('RUC');
                $("#ruc").attr("placeholder","RUC");
                $("#ruc").attr("maxlength","11");
                
                $("#lbl_RAZ_APE").text('Razon Social');
                $("#razon_social").attr("placeholder","razon_social");
                $("#nombres").hide();
            }

        });


	$(document).on('click','.print_ticket_pdf',function() {		
                var _val = $(this).attr("idval");
                javascript:window.open('<?PHP echo base_url() ?>index.php/download/downloadPdfTicket/'+_val+'','','width=750,height=600,scrollbars=yes,resizable=yes');            
                var id= $(this).attr('idval');                
    });
    $(document).on('click','.show_pdf',function(e) {               
                var _val = $(this).attr("idval");
                javascript:window.open('<?PHP echo base_url() ?>index.php/download/downloadPdf/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');               
    });

</script>	