<style>
    .shadow {
        box-shadow: 0px 0px 1px 0px #b3b2b2e3;
        border: .2px solid #cac8c8e3;
        border-radius: 1px;
    }
    .table-responsive table tbody tr td {
        border: .2px solid #dedede85;
        padding: 1rem;
        text-align: center;
    }
</style>
<!-- <div align="center" style="font-size: 27px">SISTEMA FACTURACIÓN ELECTRÓNICA</div> -->
<div class="container-fluid" style="margin: 0 25px;">
    <div class="row">
        <div style="font-family: tahoma; font-size: 20px" class="col-md-12">
            <span>Bienvenido:</span><?PHP echo " " . ucfirst($this->session->userdata('tipo_empleado')) . "&nbsp;&nbsp;&nbsp;" . $this->session->userdata('usuario') . ", " . $this->session->userdata('apellido_paterno'); ?>&nbsp;&nbsp;&nbsp - <?php echo $this->session->userdata('almacen_nom');?> 
        </div>
    </div>
    <hr style="border:1px solid #F2F3F4;">
</div>
<div class="container">
    <div class="sms"></div>
</div>

<div class="container-fluid" style="margin: 0 25px;">
    <div class="row">

      

<div class="col-lg-4">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12" style="padding:0px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-6" style="padding: 15px;font-size: 20px;">
                     <select id="almacen" class="form-control input-sm" name="almacen" onchange="init_dashboard()">
                        <?php foreach($almacenes as $almacen):?>
                       
                               <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata("almacen_id")==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                            
                        <?php endforeach?>
                      </select>
              </div>
               <div class="col-lg-6" style="padding: 15px;font-size: 25px;">
                      <select class="form-control" name="moneda" id="moneda" onchange="init_dashboard()">
                           <?PHP foreach ($monedas as $value) { ?>   
                                    <option value = "<?PHP echo $value->id;?>"><?PHP echo $value->moneda?></option>          
                           <?PHP }?>    
                           </select>
              </div>
            </div>
         </div>
      </div> 
      <div class="col-lg-12" style="display: flex;padding:10px;">
          <div class="container">
            <div class="row" style="background: #17A589;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                VENTAS DE HOY
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                <label id="a1"></label> <label id="a2"></label>
              </div>
            </div>
         </div>
      </div>  
        <div class="col-lg-12" style="display: flex;padding:10px;">
          <div class="container">
            <div class="row" style="background: #E74C3C;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                VENTAS DEL MES
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                <label id="b1"></label> <label id="b2"></label>
              </div>
            </div>
         </div>
      </div> 
       <div class="col-lg-12" style="display: flex;padding:10px;">
          <div class="container">
            <div class="row" style="background: #E67E22;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                COMPRAS DEL MES
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                 <label id="c1"></label> <label id="c2"></label>
              </div>
            </div>
         </div>
      </div> 
      <div class="col-lg-12" style="display: flex;padding:10px;">
          <div class="container">
            <div class="row" style="background: #26C6DA;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                TOTAL CLIENTES
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                 <label id="d1"></label> <label id="d2"></label>
              </div>
            </div>
         </div>
      </div>
      </div>
     </div>
    </div>     



      <div class="col-lg-8" style="display: flex;padding:10px;">
          <canvas id="bar-chart-grouped" width="800" height="450"></canvas>
      </div>

      <!--<div class="col-lg-6" style="display: flex;padding:10px;">
         <canvas id="line-chart" width="800" height="450"></canvas>
      </div>-->




     
    </div>
</div>

                                     <!--<div style="width: 100%;text-align: center;">
                                     <?php                                                                                 
                                        $empresa = $this->db->select('id,empresa,foto')->from('empresas')->where('id',1)->get()->row();
                                        echo "<img width='50%' src='".base_url()."images/".$empresa->foto."'>";
                                     ?> 
                                     </div>-->

  
 


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    /*new Chart(document.getElementById("bar-chart-grouped"), {
        type: 'bar',
        data: {
          labels: ["1900", "1950", "1999", "2050"],
          datasets: [
            {
              label: "Africa",
              backgroundColor: "#3e95cd",
              data: [133,221,783,2478]
            }, {
              label: "Europe",
              backgroundColor: "#8e5ea2",
              data: [408,547,675,734]
            }
          ]
        },
        options: {
          title: {
            display: true,
            text: 'Population growth (millions)'
          }
        }
    });*/
</script>

<script type="text/javascript" >

  function init_dashboard(){
    var almacen = $("#almacen").val();
    var moneda = $("#moneda").val();

    $.getJSON('<?= base_url();?>index.php/comprobantes/init_dashboard',{almacen,moneda})
     .done(function(json){



        $("#a1").text(json['moneda']);
        $("#b1").text(json['moneda']);
        $("#c1").text(json['moneda']);

        $("#a2").text(json['a']);
        $("#b2").text(json['b']);
        $("#c2").text(json['c']);
        $("#d2").text(json['d']);

       console.log(json['grafico_compras'][0]);

          // Bar chart
          new Chart(document.getElementById("bar-chart-grouped"), {
              type: 'bar',
              data: {
                labels: [json['grafico_meses'][3],json['grafico_meses'][2],json['grafico_meses'][1],json['grafico_meses'][0]],
                datasets: [
                  {
                    label: "Ventas",
                    backgroundColor: "#3e95cd",
                    data: [json['grafico_ventas'][3],json['grafico_ventas'][2],json['grafico_ventas'][1],json['grafico_ventas'][0]]
                  }, {
                    label: "Compras",
                    backgroundColor: "#8e5ea2",
                    data: [json['grafico_compras'][3],json['grafico_compras'][2],json['grafico_compras'][1],json['grafico_compras'][0]]
                  }
                ]
              },
              options: {
                title: {
                  display: true,
                  text: 'RESUMEN DE COMPRAS/VENTAS X MES'
                }
              }
          });
          
     });
  }


/// LINEA BAR
/*new Chart(document.getElementById("line-chart"), {
  type: 'line',
  data: {
    labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
    datasets: [{ 
        data: [86,114,106,106,107,111,133,221,783,2478],
        label: "Producto 1",
        borderColor: "#3e95cd",
        fill: false
      }, { 
        data: [282,350,411,502,635,809,947,1402,3700,5267],
        label: "Producto 2",
        borderColor: "#8e5ea2",
        fill: false
      }, { 
        data: [168,170,178,190,203,276,408,547,675,734],
        label: "Producto 3",
        borderColor: "#3cba9f",
        fill: false
      }, { 
        data: [40,20,10,16,24,38,74,167,508,784],
        label: "Producto 4",
        borderColor: "#e8c3b9",
        fill: false
      }, { 
        data: [6,3,2,2,7,26,82,172,312,433],
        label: "Producto 5",
        borderColor: "#c45850",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'Productos más vendidos'
    }
  }
});*/

    jQuery(document).ready(function($) {
      init_dashboard();
        //getDatos();
        /*$('#update').click(function(e) {
            e.preventDefault();
            var ini = $('#inicio').val();
            var fin = $('#final').val();
            if (ini=='') {
                $('.sms').html('<div class="alert alert-warning">Selecciona fecha de inicio </div>');
                setTimeout(function() {$('.sms').html('');}, 2000);
            } else if (fin=='') {
                $('.sms').html('<div class="alert alert-warning">Selecciona fecha final </div>');
                setTimeout(function() {$('.sms').html('');}, 2000);
            }else {
                getDatos(ini,fin);
            }
        });*/

    });


    /*function getDatos(ini='',fin='') {

        $.ajax({
            url: '<?= base_url();?>index.php/comprobantes/dashboard',
            type: 'POST',            
            data: {inicio: ini, final:fin},
        })
        .done(function(data) {
            var datos = JSON.parse(data);
            var html = '';

            html+='<div class="table-responsive"><table class="table" style="overflow:hidden;">';
            html+='<thead><tr><th></th><th>Boleta Soles</th><th>Boleta Dólares</th><th>Factura Soles</th><th>Factura Dólares</th></tr></thead><tbody>';

            html+='<tr>';
            html+='<td>Efectivo</td>';
            html+='<td><p>S/. '+ datos.e_boleta_soles.toFixed(2) +'</p><span>'+ datos.cantebsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.e_boleta_dolar.toFixed(2) +'</p> <span>'+ datos.cantebdolar +' documentos</span></td>';
            html+='<td><p>S/. '+ datos.e_factura_soles.toFixed(2) +'</p><span>'+ datos.cantefsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.e_factura_dolar.toFixed(2) +'</p><span>'+ datos.cantefdolar +' documentos</span></td>';
            html+='</tr>';

            html+='<tr>';
            html+='<td> tarjeta </td>';
            html+='<td><p>S/. '+ datos.t_boleta_soles.toFixed(2) +'</p><span>'+ datos.canttbsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.t_boleta_dolar.toFixed(2) +'</p><span>'+ datos.canttbdolar +' documentos</span></td>';
            html+='<td><p>S/. '+ datos.t_factura_soles.toFixed(2) +'</p><span>'+ datos.canttfsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.t_factura_dolar.toFixed(2) +'</p><span>'+ datos.canttfdolar +' documentos</span></td>';
            html+='</tr>';

            html+='<tr>';
            html+='<td> Total </td>';
            html+='<td><p>S/. '+ (datos.e_boleta_soles + datos.t_boleta_soles).toFixed(2) +'</p><span>'+ (datos.cantebsoles + datos.canttbsoles ) +' documentos</span></td>';
            html+='<td><p>$ '+ (datos.e_boleta_dolar + datos.t_boleta_dolar).toFixed(2) +'</p><span>'+ (datos.cantebdolar + datos.canttbdolar) +' documentos</span></td>';
            html+='<td><p>S/. '+ (datos.e_factura_soles + datos.t_factura_soles).toFixed(2) +'</p><span>'+ (datos.cantefsoles + datos.canttfsoles) +' documentos</span></td>';
            html+='<td><p>$ '+ (datos.e_factura_dolar + datos.t_factura_dolar).toFixed(2) +'</p><span>'+ (datos.cantefdolar + datos.canttfdolar) +' documentos</span></td>';
            html+='</tr>';

            html+='</tbody></table></div>';

            $('#table-dash').html(html);
            $("#fecha_inicio").text($('#inicio').val());
            $("#fecha_final").text($('#final').val());

            new Chart(document.getElementById("bar-chart-grouped"), {
                type: 'bar',
                data: {
                  labels: ["Boleta Efectivo", "Boleta Tarjeta", "Factura Efectivo", "Factura Tarjeta"],
                  datasets: [
                    {
                      label: "Soles",
                      backgroundColor: "#3e95cd",
                      data: [datos.e_boleta_soles.toFixed(2),datos.t_boleta_soles.toFixed(2),datos.e_factura_soles.toFixed(2),datos.t_factura_soles.toFixed(2)]
                    }, {
                      label: "Dolar",
                      backgroundColor: "#8e5ea2",
                      data: [datos.e_boleta_dolar.toFixed(2),datos.t_boleta_dolar.toFixed(2),datos.e_factura_dolar.toFixed(2),datos.t_factura_dolar.toFixed(2)]
                    }
                  ]
                },
                options: {
                  title: {
                    display: true,
                    text: 'Gráfico resumen ventas'
                  }
                }
            });

        })
        
    }*/


</script>


