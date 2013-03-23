<table width="100%" >
    <tr>
        <td width="70%"><?php echo $evento; ?></td>
        <td width="30%">
            <?php if (!$evento->statusApartado()): ?>
                <input type="button" value="Editar" class="buttonEditarEvento" onclick="$.editarEvento('<?php echo $evento->getId() ?>');" />
                <?php if ($evento->getNivel() == CotizacionesTable::$NIVEL_DETALLE): ?>
                    <input type="button" value="Eliminar" class="buttonEditarEvento" onclick="$.eliminarEvento('<?php echo $evento->getId() ?>');" />
                <?php endif; ?>
            <?php endif; ?>
        </td>
    </tr>
</table>
<script>
    $(document).ready(function(){
       $(".buttonEditarEvento").button(); 
    });
</script>    