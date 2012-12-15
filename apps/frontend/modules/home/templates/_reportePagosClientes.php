<?php use_helper('Number') ?>
<table border="0" class="reportes" width="100%">
    <thead>
    <tr>
        <th>Empresa</th>
        <th>Fecha de pago</th>
        <th>Importe</th>
        <th>IVA generado</th>
    </tr>
    </thead>
<?php 
  $importeTotal=0;
  $ivaTotal=0;
  $contTotal=0;
?>
<tbody>    
<?php foreach($clientes as $cliente):?>
    <?php 
        $importe=0;
        $iva=0;
        $cont=0;
    ?>
        <?php foreach($cliente->getPagos() as $pago):?>
            <?php foreach($pago->getDetallesPagos() as $dp):?>
                <?php 
                    $importe+=$dp->getImporte();
                    $iva+=$dp->getIva();
                    $cont++;
                ?>
                <tr align="center">
                <td>
                    <?php if($cont==1):?>
                    <?php echo $cliente?>
                    <?php else:?>
                    &nbsp;
                    <?php endif;?>
                </td>        
                <td>
                    <?php echo date("d/M/Y",  strtotime($dp->getFechaPago()))?>
                </td>
                <td><?php echo format_currency($dp->getImporte(), 'USD') ?></td>
                <td><?php echo format_currency($dp->getIva(), 'USD') ?></td>
                </tr>
            <?php endforeach;?>
        <?php endforeach;?>
                <tr style="color: black; border-bottom: 1px solid black;" align="center">
                <td>Total linea</td>        
                <td>&nbsp;</td>
                <td><?php echo format_currency($importe, 'USD') ?></td>
                <td><?php echo format_currency($iva, 'USD') ?></td>
                </tr>        
                
    <?php 
        $importeTotal+=$importe;
        $ivaTotal+=$iva;
        $contTotal+=$cont;
    ?>          
<?php endforeach; ?>
              
    <tr style="color: black; font-weight: bold; border-bottom: 2px solid black;" align="center">
        <td>Total general</td>        
        <td>Registros <?php echo $contTotal;?></td>
        <td><?php echo format_currency($importeTotal, 'USD') ?></td>
        <td><?php echo format_currency($ivaTotal, 'USD') ?></td>
    </tr>        
</tbody>  
</table>

