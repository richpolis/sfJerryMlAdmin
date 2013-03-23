<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php $cotizacion = $form->getObject()->getCotizaciones() ?>
<?php $detalles_cotizacion_conceptos = $form->getObject()->getDetallesCotizacionConceptos() ?>
<?php $detalles_cotizacion_comisionistas = $form->getObject()->getDetallesCotizacionComisionistas() ?>

<form onsubmit="return false;" id="form_detalles_cotizacion_<?php echo $form->getObject()->getId() ?>" action="<?php echo url_for('detcotizaciones/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <table style="width: 100%">
        <thead>
            <tr>
                <th colspan="3">
                    <?php echo $form->getObject()->getTalentos() ?>
                </th>
            </tr>
        </thead>  
        <tfoot>
            <tr>
                <td colspan="3">
                    <?php echo $form->renderHiddenFields(false) ?>
                    <?php if (!$form->getObject()->getCotizaciones()->statusAprobada()): ?>
                        &nbsp;<input type="button"  value="Eliminar talento" class="buttonEliminar" onclick="$.borrarTalento('<?php echo $form->getObject()->getId() ?>')"/>
                    <?php endif; ?>
                    <input type="submit"  value="Guardar" class="buttonGuardar"  onclick="tinyMCE.triggerSave(); $.guardarTalento('<?php echo $form->getObject()->getId() ?>')" />
                    <input type="button"  value="Calcular" class="buttonCalcular"/>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form->renderGlobalErrors() ?>
            <tr>
                <th>Imagen</th>  
                <th><?php echo $form['actividad']->renderLabel() ?></th>
                <th><?php echo $form['precio']->renderLabel() ?></th>
            </tr>
            <tr>
                <td width="20%" align="center" valign="middle">
                    <img src="/uploads/talentos/<?php echo $form->getObject()->getTalentos()->getImagen() ?>" style="max-height: 100px; max-width: 100px;"/>
                </td>  
                <td width="60%">
                    <?php echo $form['actividad']->renderError() ?>
                    <?php echo $form['actividad']->render(array('class' => 'detalles_cotizacion_actividad_' . $form->getObject()->getId())) ?>
                    <h3>Conceptos:</h3>
                    <div class="li-conceptos">
                        <?php foreach ($detalles_cotizacion_conceptos as $dcc): ?>
                            <?php include_partial('detconceptos/show', array("dcc" => $dcc)) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($cotizacion->getAddConceptos() && ConceptosTable::getInstance()->getCountFiltrarPorDetalleCotizacion($form->getObject()->getId())): ?>
                        <div id="detalles-cotizacion-concepto-<?php echo $form->getObject()->getId() ?>" style="widith:100%;">
                            <table width="100%" >
                                <tr><td width="75%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="button" class="buttonCrearConcepto" onclick="$.crearConcepto('<?php echo $form->getObject()->getId() ?>')" value="Crear Concepto"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endif; ?> 
                    <h3>Comisionistas:</h3>
                    <div class="li-comisionistas">
                        <?php foreach ($detalles_cotizacion_comisionistas as $dcco): ?>
                            <?php include_partial('detcomisionistas/show', array("dcco" => $dcco)) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($cotizacion->getAddComisionistas() && ComisionistasTable::getInstance()->getCountFiltrarPorDetalleCotizacion($form->getObject()->getId())): ?>
                        <div id="detalles-cotizacion-comisionista-<?php echo $form->getObject()->getId() ?>" style="widith:100%;">
                            <table width="100%" >
                                <tr><td width="75%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="button" class="buttonCrearConcepto" onclick="$.crearComisionista('<?php echo $form->getObject()->getId() ?>')" value="Crear Comisionista"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endif; ?> 

                </td>
                <td width="20%">
                    <table width="100%">
                        <tr>
                            <td>Importe: </td>
                            <td>
                                <?php echo $form['precio']->renderError() ?>
                                <?php echo $form['precio']->render(array('readonly' => 'true', 'class' => 'readonly', 'style' => 'width: 90px; font-size: 14px;')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>% Jerry Ml:</td>
                            <td>
                                <?php echo $form['margen_jerry_ml']->renderError() ?>
                                <?php echo $form['margen_jerry_ml']->render(array('style' => 'width: 90px; font-size: 14px;')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>% Extras:</td>
                            <td>
                                <?php echo $form['margen_comisionistas']->renderError() ?>
                                <?php echo $form['margen_comisionistas']->render(array('readonly' => 'true', 'class' => 'readonly', 'style' => 'width: 70px; font-size: 14px;')); ?>
                            </td>
                        </tr>
                    </table>
                    <div id="calculo-detalle-cotizacion-<?php echo $cotizacion->getId() ?>">

                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<!--/div-->

<script>
    $(document).ready(function() {
        $("input:button,input:submit").button();

        var formulario = "#form_detalles_cotizacion_<?php echo $form->getObject()->getId() ?>";
        var $divCalculoDetalleCotizacion = $("#calculo-detalle-cotizacion-<?php echo $cotizacion->getId() ?>");

        $("input.buttonCalcular").click(function() {
            calcular();
        });

        function calcular() {
            $divCalculoDetalleCotizacion.html("<div style=\"height:100px;width:100px;\">Calculando...</div>");
            $.post('<?php echo url_for('detcotizaciones/calculo') ?>',
                    $("form" + formulario).serialize(),
                    function(data) {
                        $divCalculoDetalleCotizacion.html(data);
                    }
            );
        }
        calcular();
    });

</script>    