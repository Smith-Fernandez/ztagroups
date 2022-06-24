<style type="text/css">
	label{
		width: 100%;
	}
	.ui-autocomplete { z-index:2147483647; }
</style>
<script type="text/javascript">
	
	$("#producto_c").autocomplete({
		source : '<?= base_url()?>index.php/comprobantes/buscador_item',
		minLength: 2,
		select: function(event,ui){

			var data_pro =  '<input type="hidden" id="producto_c_id" name="producto_c_id" value="'+ui.item.id+'">';
			$("#data_pro").html(data_pro);
		}
	});
</script>
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<div class="modal-title"><h3>PRODUCTO KIT</h3></div>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</button>
		</div>

		<div class="modal-body">
			<form id="formPresentacion">
			<div class="form-group">
				<div class="col-md-6">
				<label>PRODUCTO
					<input type="text" class="form-control" name="producto_c" id="producto_c">
					<div id="data_pro"><input type="hidden" id="producto_c_id" name="producto_c_id"></div>
				</label>
				</div>
				<div class="col-md-3">
					<label>CANTIDAD
					<input class="form-control" type="text" name="cantidad" id="cantidad" value="1">
					</label>
				</div>				
				<div class="col-md-3">
					<br>
					<a class="btn btn-success btn-xs btn_nueva_presentacion" href="#">AGREGAR
					</a>
				</div>				
			</div>
			<input type="hidden" name="idProducto" id="idProducto" value="<?= $producto->prod_id;?>">
			<input type="hidden" name="idView" id="idView" value="<?= $idView;?>">
			<input type="hidden" name="deleteKit" id="deleteKit" value="<?= $productoKit;?>">
		</form>
		<br><br>
		<div class="form-group" style="margin-top: 50px">
			<form id="formProductoKit">
			<div id="tablePresentacion">
			</div>		
			</form>	
		</div>	


</div>
		<div class="modal-footer">
		</div>
	</div>
</div>

<script type="text/javascript">

//$(document).ready(function(){


	function refescar() {
        var tabla = $('#tabla > tbody > tr');
        $.each(tabla,function(indice,value){   
            var parent = $(this); 
            console.log(parent);    
            cmp.calcular(parent);    
        });
    }

	function selectPresentacion(){

		var idProducto = $("#idProducto").val();		
		$.ajax({
			url :'<?= base_url();?>index.php/productos/selectPresentacion',
			dataType: 'HTML',
			data: $("#formPresentacion").serialize(),
			method: 'POST',
			success: function(data){						
				$("#tablePresentacion").html(data);
              // este codigo es para actualizar el importe del total del producto que esta abajo
				var totalProductoKit = $("#totalProductoKit").val();
				$('[name="item_id[]"][value='+idProducto+']').closest('.cont-item').find('.importe').val(totalProductoKit);
				refescar();				
				//newArray = $("#formProductoKit").serializeArray();			
				//_item  = $('#tabla');					            
	            //_item.find('.descripcion-item').val('<input type="text" id="kit" value= "'+newArray+'">');
			}
		});		
	}

	selectPresentacion();

	$(".btn_nueva_presentacion").click(function(){
		$.ajax({
			url :'<?= base_url();?>index.php/productos/guardarPresentacion',
			dataType: 'HTML',
			data: $("#formPresentacion").serialize(),
			method: 'POST',
			success: function(data){						
				//$("#tablePresentacion").html(data);	
				$("#formPresentacion")[0].reset();
				selectPresentacion();
				 // para que actualize el precio kit cada vez que agrega un producto
				 dataSource.read();
			}
		})
	});

	


	//ELIMINAR PRESENTACION VENTA 31-07-2021 - ALEXANDER FERNÁNDEZ
	$(document).on("click",".btn_eliminar_presentacionVenta",function(){

		//idPresentacion = $(this).data("id");
		var idProducto = $("#idProducto").val();
		$(this).parent().parent().addClass("eliminado");
            //alert($(this).parent());

				//var acumuladoDelete = 0;
				var taskArray = new Array();
				$(".eliminado").each(function() {					
				 	taskArray.push($(this).find('.btn_eliminar_presentacionVenta').data("id"));
				 	console.log($(this).parent().parent()[0]);
				});

				$(".eliminado").css("display","none");
				//console.log(taskArray);
				console.log(taskArray.join(','));
				taskArray = taskArray.join(',');

				
				$("#deleteKit").val(taskArray);

				
				var _item = $('[name="item_id[]"][value='+idProducto+']').closest('.cont-item');
				_item.find('.productoKit').val(taskArray);  
				console.log(_item);
				selectPresentacion();					
	});

       
//});


</script>
