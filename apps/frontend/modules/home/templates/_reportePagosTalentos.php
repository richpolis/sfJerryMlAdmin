<?php use_helper('Number') ?>
<table border="0" class="reportes" width="100%">
    <thead>
    <tr>
        <th>Talento</th>
        <th>Metodo Recibo</th>
        <th>Fecha de pago</th>
        <th>Importe</th>
        <th>IVA</th>
        <th>ISR</th>
    </tr>
    </thead>
<?php 
  $importeTotal=0;
  $ivaTotal=0;
  $isrTotal=0;
  $contTotal=0;
?>
    <tbody>   
<?php foreach($talentos as $talento):?>
    <?php 
        $importe=0;
        $iva=0;
        $isr=0;
        $cont=0;
    ?>
        <?php foreach($talento->getPagosTalentos() as $pago):?>
            <?php foreach($pago->getDetallesPagosTalentos() as $dpt):?>
                <?php 
                    $importe+=$dpt->getImporte();
                    $iva+=$dpt->getIva();
                    $isr+=$dpt->getIsr();
                    $cont++;
                ?>
                <tr align="center">
                <td>
                    <?php if($cont==1):?>
                    <?php echo $talento?>
                    <?php else:?>
                    &nbsp;
                    <?php endif;?>
                </td>
                <td><?php echo $dpt->getMetodoReciboString();?></td>
                <td><?php echo date("d/M/Y",  strtotime($dpt->getFechaPago()))?></td>
                <td><?php echo format_currency($dpt->getImporte(), 'USD') ?></td>
                <td><?php echo format_currency($dpt->getIva(), 'USD') ?></td>
                <td><?php echo format_currency($dpt->getIsr(), 'USD') ?></td>
                </tr>
            <?php endforeach;?>
        <?php endforeach;?>
                <tr style="color: black; border-bottom: 1px solid black;" align="center">
                <td>Total linea</td>        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?php echo format_currency($importe, 'USD') ?></td>
                <td><?php echo format_currency($iva, 'USD') ?></td>
                <td><?php echo format_currency($isr, 'USD') ?></td>
                </tr>        
                
    <?php 
        $importeTotal+=$importe;
        $ivaTotal+=$iva;
        $isrTotal+=$isr;
        $contTotal+=$cont;
    ?>          
<?php endforeach; ?>
    <tr align="center"  style="color: black; font-weight: bold; border-bottom: 2px solid black; ">
        <td>Total general</td>
        <td>&nbsp;</td>
        <td>Registros <?php echo $contTotal;?></td>
        <td><?php echo format_currency($importeTotal, 'USD') ?></td>
        <td><?php echo format_currency($ivaTotal, 'USD') ?></td>
        <td><?php echo format_currency($isrTotal, 'USD') ?></td>
    </tr>        
</tbody>                
</table>