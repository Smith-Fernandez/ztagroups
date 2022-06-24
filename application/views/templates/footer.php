

    </div>
    <style type="text/css">        
       #container2{        
          display: flex;
          justify-content: center;
          flex-wrap: wrap;
          align-items: center;    
       }
    </style>
        <div id="container2">
          <img src="<?= base_url()?>images/<?php echo $this->session->userdata('empresa_foto');?>" height="160" width="380" style="text-align:center;" ><br> 
          <h2><?= $this->session->userdata('empresa_razon_social')?></h2>
        </div>       
        <script src="<?PHP echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?PHP echo base_url(); ?>assets/js/jquery-ui-1.11.0.js"></script> 
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        </div>	
        <div class="modal fade" id="modalCajaMov" tabindex="-1" role="dialog">        
        </div>          
        <div class="modal fade" id="modalCajaMovCierre" tabindex="-1" role="dialog">        
        </div>          
        <div class="modal fade" id="myModalProducto" tabindex="-1" role="dialog">
        </div>
        <div class="modal fade" id="myModalPrecio" tabindex="-1" role="dialog">
        </div>
        <div class="modal fade" id="myModalNuevoCliente" tabindex="-1" role="dialog">
        </div>
        <div class="modal fade" id="myModalPagoMonto" tabindex="-1" role="dialog">
        </div>
    </body>
</html>

<!-- ALEXANDER FERNANDEZ DE LA CRUZ 13-10-2020 -->
<script type="text/JavaScript">
  function toggleSlideBox(x) {
      if ($('#'+x).is(":hidden")) {
        $('#'+x).slideDown(50)
      } else {
        $('#'+x).slideUp(100);                
      }
  }  
</script>    
