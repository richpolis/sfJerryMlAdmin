<div id="li-comisionistas">
    <?php foreach ($form->getObject()->getCotizacionesComisionistas() as $cotco): ?>
        <?php include_partial('cotcomisionistas/show', array("cotco" => $cotco)) ?>
    <?php endforeach; ?>
</div>
<div id="cotizacion-comisionista-<?php echo $form->getObject()->getId() ?>" style="widith:100%;">
    <table width="100%" >
        <tr><td width="75%">&nbsp;</td>
            <td width="25%">
                <input type="button" class="buttonCrearConcepto" onclick="$.crearComisionista('<?php echo $form->getObject()->getId() ?>')" value="Crear Comisionista"/>
            </td>
        </tr>
    </table>
</div>

