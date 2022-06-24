<h2 align="center">Empresas</h2>
<br>
<div class="container">
    <table class="table tab-content">
        <tr>
            <td>Razón social</td>
            <td>RUC</td>
            <td>Dirección</td>
            <td>Logo</td>
            <td>Modificar</td>
        </tr>
        <tr>
            <td><?php echo $empresa['empresa']?></td>
            <td><?php echo $empresa['ruc']?></td>
            <td><?php echo $empresa['domicilio_fiscal']?></td>
            <td><a class="btn btn-default" href="<?php echo base_url()?>index.php/empresas/logo">Logo</a></td>
            <td><a class="btn btn-default" href="<?php echo base_url()?>index.php/empresas/modificar">Modificar</a></td>
        </tr>
    </table>
</div>