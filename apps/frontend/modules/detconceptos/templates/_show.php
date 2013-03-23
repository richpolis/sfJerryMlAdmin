<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php if (!isset($sin_botones)) $sin_botones = false; ?>
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
        <?php if(!$sin_botones):?>    
            <input type="button" value="Editar" class="buttonEditarRegistro" onclick="$.editarConcepto('<?php echo $dcc->getId() ?>','<?php echo $dcc->getDetallesCotizacionId() ?>');" />
            <?php if(!$dcc->hasNivelCotizacion()):?>
            <input type="button" value="Eliminar" class="buttonEditarRegistro" onclick="$.eliminarConcepto('<?php echo $dcc->getId() ?>','<?php echo $dcc->getDetallesCotizacionId() ?>');" />
            <?php endif;?>
        <?php endif;?>    
        </td>
    </tr>
</table>
<?php if(!$sin_div):?>
</div>
<?php endif;?>