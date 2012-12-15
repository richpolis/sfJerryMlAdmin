<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>

<?php $cotizacion = $detalle_cotizacion->getCotizaciones(); ?>
<?php $detalles_pagos_talentos = $detalle_cotizacion->getDetallesPagosTalentos(); ?>
<?php $pagos_talentos = $detalles_pagos_talentos[0]->getPagosTalentos(); ?>
<?php if (!$sin_div): ?>
    <div id="detalles_cotizacion_<?php echo $detalle_cotizacion->getId() ?>">
<?php endif; ?>    
    <table style="width: 100%;">
        <thead>
            <tr>
                <th colspan="5">
                    <?php echo 'Cotizacion: '.$cotizacion; ?>    
                </th>
            </tr>
        </thead> 
        <tbody>
            <tr>
                <th>Fecha Pago:</th>
                <th>Metodo Recibo:</th>
                <th>Status:</th>
                <th>Importes:</th>
                <th>Acciones:</th>
            </tr>
            <?php foreach($detalles_pagos_talentos as $dpt):?>
            <tr>
                <td width="15%">
                    <?php echo date("d/M/Y",strtotime($dpt->getFechaPago()))?>
                </td>  
                <td width="15%">
                    <?php echo $dpt->getMetodoReciboString() ?>
                </td>
                <td width="15%">
                    <?php echo $dpt->getStatusString() ?>
                </td>
                <td width="30%">
                    <?php
                    $importe = $dpt->getImporte();
                    $iva = $dpt->getIva();
                    $isr=$dpt->getIsr();
                    ?>
                    <table width="100%">
                        <tr>
                            <td>Importe <?php echo format_currency($importe, 'USD') ?></td>
                            <td>IVA <?php echo format_currency($iva, 'USD') ?></td>
                            <td>ISR <?php echo format_currency($isr, 'USD') ?></td>
                            
                        </tr>
                    </table>    
                </td>
                <td width="10%">
                    <?php if($dpt->getStatus()<PagosTalentosTable::$APROBADO):?>
                    <a href="#" onclick="$.editarDetallesPagoTalento('<?php echo $dpt->getId() ?>','<?php echo $detalle_cotizacion->getId() ?>')">Editar</a>
                    <a href="<?php echo url_for('aprobar_pago_talento')?>?generar=<?php echo $dpt->getId() ?>">Aprobar Pago</a>
                    <?php endif; ?>
                    <?php if($dpt->getStatus()==PagosTalentosTable::$APROBADO):?>
                    <a href="<?php echo url_for('liberar_pago_talento')?>?generar=<?php echo $dpt->getId() ?>">Liberar Pago</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                   <div id="importes-detalles-pago">
                        <?php
                        $total_pagado=0;
                        foreach($detalles_pagos_talentos as $dpt){
                            $total_pagado+=$dpt->getImporte();
                        }
                        $total_a_pagar=$detalle_cotizacion->getGananciaTalento();
                        
                        $saldo=$total_a_pagar-$total_pagado;
                        echo "Saldo por pagar: ". format_currency($saldo, 'USD'); 
                        ?>
                    </div>
                    
                </td>  
            </tr>
        </tfoot>
    </table>
  
<?php if (!$sin_div): ?>    
    </div>
<?php endif; ?>