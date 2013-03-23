<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php if(!$sin_div):?>
<div class="li-comisionista" id="cotco-<?php echo $cotco->getId()?>" style="widith:100%;">
<?php endif;?>    
<table width="100%" >
    <tr>
        <td width="35%">
            <?php echo $cotco->getComisionistas(); ?>
        </td>
        <td width="30%">
            <?php echo $cotco->getMargen(); ?>%
        </td>
        <td width="30%">
            <input type="button" value="Editar" class="buttonEditarRegistro" onclick="$.editarComisionista('<?php echo $cotco->getId() ?>','<?php echo $cotco->getCotizacionId() ?>');" />
            <input type="button" value="Eliminar" class="buttonEditarRegistro" onclick="$.eliminarComisionista('<?php echo $cotco->getId() ?>','<?php echo $cotco->getCotizacionId() ?>');" />
        </td>
    </tr>
</table>
<?php if(!$sin_div):?>
</div>
<?php endif;?>