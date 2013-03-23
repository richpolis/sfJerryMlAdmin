<div id="li-conceptos">
    <?php foreach ($form->getObject()->getCotizacionesConceptos() as $cotc): ?>
        <?php include_partial('cotconceptos/show', array("cotc" => $cotc)) ?>
    <?php endforeach; ?>
</div>
<div id="cotizacion-concepto-<?php echo $form->getObject()->getId() ?>" style="widith:100%;">
    <table width="100%" >
        <tr><td width="75%">&nbsp;</td>
            <td width="25%">
                <input type="button" class="buttonCrearConcepto" onclick="$.crearConcepto('<?php echo $form->getObject()->getId() ?>')" value="Crear Concepto"/>
            </td>
        </tr>
    </table>
</div>
