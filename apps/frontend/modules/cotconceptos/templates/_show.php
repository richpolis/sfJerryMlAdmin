<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php if(!$sin_div):?>
<div class="li-concepto" id="cotc-<?php echo $cotc->getId() ?>" style="widith:100%;">
<?php endif;?>    
<table width="100%" >
    <tr>
        <td width="35%">
            <?php echo $cotc->getConceptos(); ?>
        </td>
        <td width="35%">
            <?php echo format_currency($cotc->getPrecio(), 'USD') ?>
        </td>
        <td width="30%">
            <input type="button" value="Editar" class="buttonEditarRegistro" onclick="$.editarConcepto('<?php echo $cotc->getId() ?>','<?php echo $cotc->getCotizacionId() ?>');" />
            <input type="button" value="Eliminar" class="buttonEditarRegistro" onclick="$.eliminarConcepto('<?php echo $cotc->getId() ?>','<?php echo $cotc->getCotizacionId() ?>');" />
        </td>
    </tr>
</table>
<?php if(!$sin_div):?>
</div>
<?php endif;?>