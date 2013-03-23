<?php use_helper('Number') ?>
<link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.css" />
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<table border="0" class="reportes table table-striped" width="100%">
    <thead>
        <tr>
            <th>Talento</th>
            <th>Cliente</th>
            <th>Evento</th>
            <th>Concepto</th>
            <th>Metodo Recibo</th>
            <th>Fecha de pago</th>
            <th style="text-align: right;">Importe</th>
            <th style="text-align: right;">IVA</th>
            <th style="text-align: right;">Subtotal</th>
            <th style="text-align: right;">Retencion IVA</th>
            <th style="text-align: right;">Retencion ISR</th>
            <th style="text-align: right;">Total a Pagar</th>
        </tr>
    </thead>
    <?php
    $importeTotal = 0;
    $ivaTotal = 0;
    $subtotalTotal = 0;
    $retencionIvaTotal = 0;
    $retencionIsrTotal = 0;
    $totalAPagarTotal = 0;
    $contTotal = 0;
    ?>
    <tbody>   
        <?php foreach ($talentos as $talento): ?>
            <?php
            $importe = 0;
            $iva = 0;
            $subtotal = 0;
            $retencionIsr = 0;
            $retencionIva = 0;
            $totalAPagar = 0;
            $cont = 0;
            ?>
            <?php foreach ($talento->getPagosTalentos() as $pago): ?>
                <?php foreach ($pago->getDetallesPagosTalentos() as $dpt): ?>
                    <?php
                    $importe+=$dpt->getImporte();
                    $iva+=$dpt->getIva();
                    $retencionIsr+=$dpt->getRetencionIsr();
                    $retencionIva+=$dpt->getRetencionIva();
                    $subtotal+=$dpt->getSubtotal();
                    $totalAPagar+=$dpt->getImporteAPagar();
                    $cont++;
                    $cotizacion = $dpt->getDetallesCotizacion()->getCotizaciones();
                    ?>
                    <tr align="center">
                        <td>
                            <?php if ($cont == 1): ?>
                                <?php echo $talento ?>
                            <?php else: ?>
                                &nbsp;
                            <?php endif; ?>
                        </td>
                        <td><?php echo $cotizacion->getClientes(); ?></td>
                        <td><?php echo $cotizacion->getDescripcion(); ?></td>
                        <td><?php echo $dpt->getDetallesCotizacion()->getConceptosString(); ?></td>
                        <td><?php echo $dpt->getMetodoReciboString(); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($dpt->getFechaPago())) ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dpt->getImporte(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dpt->getIva(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dpt->getSubtotal(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dpt->getRetencionIva(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dpt->getRetencionIsr(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dpt->getImporteAPagar(), 'USD') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr style="color: black; border-bottom: 1px solid black;" align="center">
                <td>Total talento</td>        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Registro<?php echo ($cont==1?' (1)':'s ('.$cont.')')?></td>
                <td style="text-align: right;"><?php echo format_currency($importe, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($iva, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($subtotal, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($retencionIva, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($retencionIsr, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($totalAPagar, 'USD') ?></td>
            </tr>        

            <?php
            $importeTotal+=$importe;
            $ivaTotal+=$iva;
            $subtotalTotal+=$subtotal;
            $retencionIvaTotal+=$retencionIva;
            $retencionIsrTotal+=$retencionIsr;
            $totalAPagarTotal+=$totalAPagar;
            $contTotal+=$cont;
            ?>          
        <?php endforeach; ?>
        <tr align="center"  style="color: black; font-weight: bold; border: 2px solid black; ">
            <td>Total general</td>        
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Registro<?php echo ($contTotal==1?' (1)':'s ('.$contTotal.')')?></td>
                <td style="text-align: right;"><?php echo format_currency($importeTotal, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($ivaTotal, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($subtotalTotal, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($retencionIvaTotal, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($retencionIsrTotal, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($totalAPagarTotal, 'USD') ?></td>
        </tr>        
    </tbody>                
</table>