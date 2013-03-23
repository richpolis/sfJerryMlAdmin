<?php use_helper('Number') ?>
<link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.css" />
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<table border="0" class="reportes table table-striped" width="100%">
    <thead>
        <tr>
            <th>Cotizacion</th>
            <th>Cliente</th>
            <th style="text-align: right;">Total Evento</th>
            <th style="text-align: right;">$ Jerry Ml</th>
            <th style="text-align: right;">% Comisionista</th>
            <th style="text-align: right;">$ Comisionista</th>
        </tr>
    </thead>
    <tbody>    
        <?php
        $gananciaJerryMlTotal = 0;
        $gananciaComisionistaTotal = 0;
        $gananciaTalentoTotal = 0;
        $margenComisionistaTotal = 0;
        $contTotal = 0;
        $cots = array();
        ?>

        <?php foreach ($cotizaciones as $cotizacion): ?>
            <?php
            $gananciaJerryMl = 0;
            $gananciaComisionista = 0;
            $ganaciaTalento = 0;
            $margenComisionista = 0;
            ?>
            <?php if (!in_array($cotizacion->getId(), $cots)): ?>
                <?php foreach ($cotizacion->getDetallesCotizacion() as $dc): ?>
                    <?php
                    $gananciaJerryMl+=$dc->getGananciaJerryMl();
                    $gananciaComisionista+=$dc->getGananciaComisionistas();
                    $ganaciaTalento+=$dc->getGananciaTalento();
                    $margenComisionista = ($gananciaComisionista / $ganaciaTalento) * 100;
                    ?>
                <?php endforeach; ?>
                <?php $cots[] = $cotizacion->getId(); ?>
                <tr align="center">
                    <td style="text-align: left;">
                        <?php echo $cotizacion; ?>
                    </td>    
                    <td style="text-align: left;"><?php echo $cotizacion->getClientes() ?></td>
                    <td style="text-align: right;"><?php echo format_currency($cotizacion->getSubtotal(), 'USD') ?></td>
                    <td style="text-align: right;"><?php echo format_currency($gananciaJerryMl, 'USD') ?></td>
                    <td style="text-align: right;"><?php echo number_format($margenComisionista, 2, ".", "") ?>%</td>
                    <td style="text-align: right;"><?php echo format_currency($gananciaComisionista, 'USD') ?></td>
                    <?php
                    $gananciaJerryMlTotal+=$gananciaJerryMl;
                    $gananciaComisionistaTotal+=$gananciaComisionista;
                    $gananciaTalentoTotal+=$ganaciaTalento;
                    $margenComisionistaTotal = ($gananciaComisionistaTotal / $gananciaTalentoTotal) * 100;
                    $contTotal++;
                    ?>
                </tr>
            <?php endif; ?>    
        <?php endforeach; ?>    
        <tr align="center"  style="color: black; font-weight: bold; border: 2px solid black; ">
            <td>Total general</td>
            <td>Registro<?php echo ($contTotal==1?' (1)':'s ('.$contTotal.')')?></td>
            <td style="text-align: right;"><?php echo format_currency($gananciaTalentoTotal + $gananciaJerryMlTotal, 'USD') ?></td>
            <td style="text-align: right;"><?php echo format_currency($gananciaJerryMlTotal, 'USD') ?></td>
            <td style="text-align: right;"><?php echo number_format($margenComisionistaTotal, 2, ".", "") ?>%</td>
            <td style="text-align: right;"><?php echo format_currency($gananciaComisionistaTotal, 'USD') ?></td>
        </tr>
    </tbody>
</table>


