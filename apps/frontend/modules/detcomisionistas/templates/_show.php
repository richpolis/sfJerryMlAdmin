<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php if (!isset($sin_botones)) $sin_botones = false; ?>
<?php if(!$sin_div):?>
<div class="li-comisionista" id="dcco-<?php echo $dcco->getId()?>" style="widith:100%;">
<?php endif;?>    
<table width="100%" >
    <tr>
        <td width="25%">
            <?php echo $dcco->getComisionistas(); ?>
        </td>
        <td width="20%">
            <?php echo $dcco->getMargen(); ?>%
        </td>
        <td width="25%">
            <?php echo format_currency($dcco->getGanancia(), 'USD'); ?>
        </td>
        <td width="30%">
        <?php if(!$sin_botones):?>
            <input type="button" value="Editar" class="buttonEditarRegistro" onclick="$.editarComisionista('<?php echo $dcco->getId() ?>','<?php echo $dcco->getDetallesCotizacionId() ?>');" />
            <?php if(!$dcco->hasNivelCotizacion()):?>
            <input type="button" value="Eliminar" class="buttonEditarRegistro" onclick="$.eliminarComisionista('<?php echo $dcco->getId() ?>','<?php echo $dcco->getDetallesCotizacionId() ?>');" />
            <?php endif;?>
        <?php endif;?>
        </td>
    </tr>
</table>
<?php if(!$sin_div):?>
</div>
<?php endif;?>