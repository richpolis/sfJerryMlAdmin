<div class="li-conceptos">
    <?php foreach ($cotizaciones->getConceptosCotizacion() as $dcc): ?>
        <?php include_partial('detconceptos/show', array("dcc" => $dcc)) ?>
    <?php endforeach; ?>
</div>
<div id="cotizacion-concepto-<?php echo $cotizaciones->getId() ?>" style="widith:100%;">
    <table width="100%" >
        <tr><td width="75%">&nbsp;</td>
            <td width="25%">
                <input type="button" class="buttonCrearConcepto" onclick="$.crearConcepto('<?php echo $cotizaciones->getId() ?>')" value="Crear Concepto"/>
            </td>
        </tr>
    </table>
</div>
