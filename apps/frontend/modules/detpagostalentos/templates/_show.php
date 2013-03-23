<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>

<?php $cotizacion = $detalle_cotizacion->getCotizaciones(); ?>
<?php $detalles_pagos_talentos = $detalle_cotizacion->getDetallesPagosTalentos(); ?>
<?php $pagos_talentos = $detalles_pagos_talentos[0]->getPagosTalentos(); ?>
<?php 
$importeTotal = 0.0;
$ivaTotal = 0.0;
$isrTotal = 0.0;
$retencionIvaTotal = 0.0;
$retencionIsrTotal = 0.0; 
?>
<?php if (!$sin_div): ?>
    <div id="detalles_cotizacion_<?php echo $detalle_cotizacion->getId() ?>">
                    <?php endif; ?>    
    <table style="width: 100%;">
        <thead>
            <tr>
                <th colspan="10">
                    <?php echo 'Cotizacion: ' . $cotizacion; ?>    
                </th>
            </tr>
        </thead> 
        <tbody>
            <tr>
                <th>Fecha Pago:</th>
                <th>Metodo Recibo:</th>
                <th>Status:</th>
                <th>Subtotal:</th>
                <th>IVA:</th>
                <th>Total:</th>
                <th>Ret. IVA:</th>
                <th>Ret. ISR:</th>
                <th>Total a pagar:</th>
                <th>Acciones:</th>
            </tr>
            <?php foreach ($detalles_pagos_talentos as $dpt): ?>
                <tr>
                    <td width="10%"><?php echo date("d/m/Y", strtotime($dpt->getFechaPago())) ?></td>  
                    <td width="10%"><?php echo $dpt->getMetodoReciboString() ?></td>
                    <td width="10%"><?php echo $dpt->getStatusString() ?></td>
                    <?php $importe = $dpt->getImporte(); ?>
                    <?php $iva= $dpt->getIva(); ?>
                    <?php
                        $isr= $dpt->getIsr();
                        if ($isr>0):
                          $retencionIva=$dpt->getIva() * (2 / 3);
                          $retencionIsr=$dpt->getImporte() * 0.1;
                        else:
                          $retencionIsr=0;
                          $retencionIva=0;
                        endif;
                    ?>
                    <td><?php echo format_currency($importe, 'USD') ?></td>
                    <td><?php echo format_currency($iva, 'USD') ?></td>
                    <td><?php echo format_currency($iva+$importe, 'USD') ?></td>
                    <td><?php echo format_currency($retencionIva, 'USD') ?></td>
                    <td><?php echo format_currency($retencionIsr, 'USD') ?></td>
                    <td><?php echo format_currency(($iva+$importe)-$isr, 'USD') ?></td>
                    <td width="25%">
                        <?php if ($dpt->getStatus() < PagosTalentosTable::$APROBADO): ?>
                            <input type="button" class="editarPago" onclick="$.editarDetallesPagoTalento('<?php echo $dpt->getId() ?>','<?php echo $detalle_cotizacion->getId() ?>');" value="Editar"/>
                            <input type="button" class="aprobarPago" onclick="$.confirmarAprobar('<?php echo url_for('aprobar_pago_talento') ?>?generar=<?php echo $dpt->getId() ?>');" value="Aprobar Pago"/>
                        <?php endif; ?>
                        <?php if ($dpt->getStatus() == PagosTalentosTable::$APROBADO): ?>
                            <input type="button" class="liberarPago" onclick="location.href='<?php echo url_for('liberar_pago_talento') ?>?generar=<?php echo $dpt->getId() ?>'" value="Liberar Pago"/>
                            <?php if ($sf_user->getGuardUser()->getIsSuperAdmin()): ?>
                                <input type="button" class="liberarPago" onclick="location.href='<?php echo url_for('cancelar_aprobar_pago_talento') ?>?generar=<?php echo $dpt->getId() ?>'" value="Cancelar Aprobar"/>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $importeTotal+= $importe; ?>
                <?php $ivaTotal+= $iva; ?>
                <?php
                $isrTotal+= $isr;
                $retencionIvaTotal+=$retencionIva;
                $retencionIsrTotal+=$retencionIsr;
                ?>
                <?php endforeach; ?>
                <?php if ($importeTotal > 0): ?>
                <tr style=" font-weight: bold;">
                    <td width="10%">&nbsp;</td>  
                    <td width="10%">&nbsp;</td>
                    <td width="10%">Gran total</td>
                    <td><?php echo format_currency($importeTotal, 'USD') ?></td>
                    <td><?php echo format_currency($ivaTotal, 'USD') ?></td>
                    <td><?php echo format_currency($importeTotal + $ivaTotal, 'USD') ?></td>
                    <td><?php echo format_currency($retencionIvaTotal, 'USD') ?></td>
                    <td><?php echo format_currency($retencionIsrTotal, 'USD') ?></td>
                    <td><?php echo format_currency(($importeTotal + $ivaTotal) - ($isrTotal), 'USD') ?></td>
                    <td width="25%">&nbsp;</td>
                </tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10">
                    <div id="importes-detalles-pago">
                        <?php
                        $total_pagado = 0;
                        foreach ($detalles_pagos_talentos as $dpt) {
                            if ($dpt->getStatus() == PagosTalentosTable::$PAGOS_CALCULADOS)
                                $total_pagado+=$dpt->getImporte();
                        }
                        $total_a_pagar = $detalle_cotizacion->getGananciaTalentoReal();

                        $saldo = $total_a_pagar - $total_pagado;
                        echo "Saldo por pagar: " . format_currency($saldo, 'USD');
                        ?>
                    </div>

                </td>  
            </tr>
        </tfoot>
    </table>

<?php if (!$sin_div): ?>    
    </div>
<?php endif; ?>
<script>
    $(document).ready(function(){
        $("input.editarPago,input.aprobarPago,input.liberarPago").button().css({"fontSize":"12px","color":"white"});
    });
</script>
