<div class="li-comisionistas">
    <?php foreach ($cotizaciones->getComisionistasCotizaciones() as $dcco): ?>
        <?php include_partial('detcomisionistas/show', array("dcco" => $dcco)) ?>
    <?php endforeach; ?>
</div>
<div id="cotizacion-comisionista-<?php echo $cotizaciones->getId() ?>" style="widith:100%;">
    <table width="100%" >
        <tr><td width="75%">&nbsp;</td>
            <td width="25%">
                <input type="button" class="buttonCrearConcepto" onclick="$.crearComisionista('<?php echo $cotizaciones->getId() ?>')" value="Crear Comisionista"/>
            </td>
        </tr>
    </table>
</div>
