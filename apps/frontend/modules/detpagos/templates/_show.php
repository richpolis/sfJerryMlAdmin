<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
             <tr>
                <td width="15%">
                    <?php echo date("d/M/Y",strtotime($detalles_pago->getFechaPago()))?>
                </td>  
                <td width="15%">
                    <?php echo $detalles_pago->getTipoPagoString() ?>
                </td>
                <td width="15%">
                    <?php echo $detalles_pago->getStatusString() ?>
                </td>
                <td width="30%">
                    <?php
                    $importe = $detalles_pago->getImporte();
                    $iva = $detalles_pago->getIva();
                    ?>
                    <table width="100%">
                        <tr>
                            <td>Importe: <?php echo format_currency($importe, 'USD') ?></td>
                            <td>IVA generado: <?php echo format_currency($iva, 'USD') ?></td>
                        </tr>
                    </table>    
                </td>
                <td width="25%">
                    <?php if($detalles_pago->getStatus()<PagosTable::$APROBADO):?>
                    <a href="#" onclick="$.editarDetallesPago('<?php echo $detalles_pago->getId() ?>','<?php echo $detalles_pago->getCotizacionId() ?>')">Editar</a>
                    <a href="<?php echo url_for('aprobar_pago')?>?generar=<?php echo $detalles_pago->getId() ?>">Aprobar Pago</a>
                    <?php endif; ?>
                    <?php if($detalles_pago->getStatus()==PagosTable::$APROBADO):?>
                    <a href="<?php echo url_for('liberar_pago')?>?generar=<?php echo $detalles_pago->getId() ?>">Liberar Pago</a>
                    <?php endif; ?>
                </td>
            </tr>