<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
             <tr>
                <td width="15%">
                    <?php echo date("d/m/Y",strtotime($detalles_pago->getFechaPago()))?>
                </td>  
                <td width="15%">
                    <?php echo $detalles_pago->getTipoPagoString() ?>
                </td>
                <td width="15%">
                    <?php echo $detalles_pago->getStatusString() ?>
                </td>
                <td width="30%">
                    <?php $importe = $detalles_pago->getImporte(); ?>
                    <table width="100%" class="no-padding">
                        <tr>
                            <th width="50%">Importe: </th>
                            <td><?php echo format_currency($importe,'USD') ?></td>
                        </tr>
                    </table>
                </td>
                <td width="25%">
                    <?php if($detalles_pago->getStatus()<PagosTable::$APROBADO):?>
                    <a class="editarPago" href="#" onclick="$.editarDetallesPago('<?php echo $detalles_pago->getId() ?>','<?php echo $detalles_pago->getCotizacionId() ?>');">Editar</a>
                    <a class="aprobarPago" href="#" onclick="$.confirmarAprobar('<?php echo url_for('aprobar_pago')?>?generar=<?php echo $detalles_pago->getId() ?>');">Aprobar Pago</a>
                    <?php endif; ?>
                    <?php if($detalles_pago->getStatus()==PagosTable::$APROBADO):?>
                    <a class="liberarPago" href="<?php echo url_for('liberar_pago')?>?generar=<?php echo $detalles_pago->getId() ?>">Liberar Pago</a>
                    <?php if($sf_user->getGuardUser()->getIsSuperAdmin()):?>
                    <a class="liberarPago" href="<?php echo url_for('cancelar_aprobar_pago')?>?generar=<?php echo $detalles_pago->getId() ?>">Cancelar Aprobar</a>
                    <?php endif;?>
                    <?php endif; ?>
                </td>
            </tr>
            
<script>
$(document).ready(function(){
    $("a.editarPago,a.aprobarPago,a.liberarPago").button().css({"fontSize":"12px","color":"white"});
});
</script>
            