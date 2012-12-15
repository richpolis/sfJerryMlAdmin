<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php if(!$sin_div):?>
<div class="li-concepto" id="dcc-<?php echo $dcc->getId() ?>" style="widith:100%;">
<?php endif;?>    
<table width="100%" >
    <tr>
        <td width="35%">
            <?php echo $dcc->getConceptos(); ?>
        </td>
        <td width="35%">
            <?php echo format_currency($dcc->getPrecio(), 'USD') ?>
        </td>
        <td width="30%">
            <input type="button" value="Editar" class="buttonEditarConcepto" onclick="$.editarConcepto('<?php echo $dcc->getId() ?>');" />
            <input type="button" value="Eliminar" class="buttonEditarConcepto" onclick="$.eliminarConcepto('<?php echo $dcc->getId() ?>','<?php echo $dcc->getDetallesCotizacionId() ?>');" />
        </td>
    </tr>
</table>
<?php if(!$sin_div):?>
</div>
<?php endif;?>