<?php use_helper('Number') ?>
<link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.css" />
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<table border="0" class="reportes table table-striped" width="100%">
    <thead>
        <tr>
            <th>Empresa</th>
            <th>Evento</th>
            <th>Fecha de pago</th>
            <th style="text-align: right;">Importe</th>
            <th style="text-align: right;">IVA generado</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <?php
    $importeTotal = 0;
    $ivaTotal = 0;
    $totalTotal = 0;
    $contTotal = 0;
    ?>
    <tbody>    
        <?php foreach ($clientes as $cliente): ?>
            <?php
            $importe = 0;
            $iva = 0;
            $total = 0;
            $cont = 0;
            ?>
            <?php foreach ($cliente->getPagos() as $pago): ?>
                <?php foreach ($pago->getDetallesPagos() as $dp): ?>
                    <?php
                    $importe+=$dp->getImporte();
                    $iva+=$dp->getIva();
                    $total+=$dp->getTotal();
                    $cont++;
                    ?>
                    <tr align="center">
                        <td>
                            <?php if ($cont == 1): ?>
                                <?php echo $cliente ?>
                            <?php else: ?>
                                &nbsp;
                            <?php endif; ?>
                        </td>
                        <td><?php echo $dp->getCotizaciones()->getDescripcion() ?></td>
                        <td>
                            <?php echo date("d/m/Y", strtotime($dp->getFechaPago())) ?>
                        </td>
                        <td style="text-align: right;"><?php echo format_currency($dp->getImporte(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dp->getIva(), 'USD') ?></td>
                        <td style="text-align: right;"><?php echo format_currency($dp->getTotal(), 'USD') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr style="color: black; border-bottom: 1px solid black;" align="center">
                <td>Total cliente</td>        
                <td>&nbsp;</td>
                <td>Registro<?php echo ($cont == 1 ? ' (1)' : 's (' . $cont . ')') ?></td>
                <td style="text-align: right;"><?php echo format_currency($importe, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($iva, 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($total, 'USD') ?></td>
            </tr>        

            <?php
            $importeTotal+=$importe;
            $ivaTotal+=$iva;
            $totalTotal+=$total;
            $contTotal+=$cont;
            ?>          
        <?php endforeach; ?>

        <tr style="color: black; font-weight: bold; border: 2px solid black;" align="center">
            <td>Total general</td>        
            <td>&nbsp;</td>
            <td>Registro<?php echo ($contTotal == 1 ? ' (1)' : 's (' . $contTotal . ')') ?></td>
            <td style="text-align: right;"><?php echo format_currency($importeTotal, 'USD') ?></td>
            <td style="text-align: right;"><?php echo format_currency($ivaTotal, 'USD') ?></td>
            <td style="text-align: right;"><?php echo format_currency($totalTotal, 'USD') ?></td>
        </tr>        
    </tbody>  
</table>

