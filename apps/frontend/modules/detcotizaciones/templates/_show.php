<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
<?php
$app_name = $sf_context->getConfiguration()->getApplication();
if (strcmp($sf_context->getConfiguration()->getEnvironment(), 'prod') != 0) {
    $app_name .= '_' . $sf_context->getConfiguration()->getEnvironment();
}
if ($app_name == "frontend") {
    $app_name = "index";
}
?>

<?php if (!isset($sin_div)) $sin_div = false; ?>

<?php $talento      = $detalles_cotizacion->getTalentos(); ?>
<?php $cotizacion   = $detalles_cotizacion->getCotizaciones(); ?>
<?php $eventos      = $detalles_cotizacion->getEventos(); ?>

<?php if (!$sin_div): ?>
<div id="detalles_cotizacion_<?php echo $detalles_cotizacion->getId() ?>">
<?php endif; ?>    
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Artista:</th>
                <th>Actividades:</th>
                <th>Costo:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="20%">
                    <?php echo $talento; ?><br/>
                    <img class="manejador" src="http://<?php echo $sf_request->getHost() ?>/uploads/talentos/<?php echo $talento->getImagen() ?>" style="max-height: 100px; max-width: 100px;" width="100" height="100"/>
                </td>  
                <td width="60%">
                    <table border="0" style="width:100%;">
                        <tr>
                            <td width="100%">
                                <h3>Eventos:</h3>
                                <?php foreach ($eventos as $evento): ?>
                                    <div id="eventos-<?php echo $evento->getId() ?>" style="widith:100%;">
                                        <table width="100%" >
                                            <tr>
                                                <td width="70%"><?php echo $evento.$evento->getDescriptionLimpia(ESC_RAW); ?></td>
                                                <td width="30%">
                                                <?php if (!$evento->statusApartado()): ?>
                                                  <input type="button" value="Editar" class="buttonEditarEvento" onclick="$.editarEvento('<?php echo $evento->getId() ?>');" />
                                                  <?php if($evento->getNivel()==CotizacionesTable::$NIVEL_DETALLE):?>
                                                  <input type="button" value="Eliminar" class="buttonEditarEvento" onclick="$.eliminarEvento('<?php echo $evento->getId() ?>');" />
                                                  <?php endif;?>
                                                <?php endif; ?>
                                                </td>
                                             </tr>
                                        </table>
                                    </div>
                                <?php endforeach; ?>
                                <?php if($cotizacion->getAddEventos()):?>
                                <div id="detalles-cotizacion-evento-<?php echo $detalles_cotizacion->getId() ?>" style="widith:100%;">
                                    <table width="100%" >
                                        <tr><td width="75%">&nbsp;</td>
                                            <td width="25%">
                                                <?php if (!$detalles_cotizacion->getIsPayTalento()): ?>
                                                    <a class="buttonCrearEvento" style="color: white;" href="<?php echo url_for('talentos/ListCalendar?slug=' . $talento->getSlug() . '&modo=cotizaciones&goto=/' . $app_name . '.php/cotizaciones/' . $detalles_cotizacion->getCotizacionId() . '&return_talentos=false') ?>">Crear Evento</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php endif;?>
                                <h3>Conceptos:</h3>
                                <?php if(!$detalles_cotizacion->getDetallesCotizacionConceptos()==null):?>
                                    <?php foreach ($detalles_cotizacion->getDetallesCotizacionConceptos() as $dcc): ?>
                                      <?php include_partial('detconceptos/show', array("dcc"=>$dcc,"sin_botones"=>true))?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    Sin conceptos cargados
                                <?php endif;?>
                                <h3>Comisionistas:</h3>
                                <?php if(!$detalles_cotizacion->getDetallesCotizacionComisionistas()==null):?>
                                    <?php foreach ($detalles_cotizacion->getDetallesCotizacionComisionistas() as $dcco): ?>
                                      <?php include_partial('detcomisionistas/show', array("dcco"=>$dcco,"sin_botones"=>true))?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    Sin comisionistas cargados
                                <?php endif;?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="20%">
                    <table width="100%">
                        <tr>
                            <td>Importe</td>
                            <td style=" text-align: right;"> <?php echo format_currency($detalles_cotizacion->getSubtotal(), 'USD') ?></td>
                        </tr> 
                        <tr>
                            <td>Talento</td>
                            <td style=" text-align: right;">  <?php echo format_currency($detalles_cotizacion->getGananciaTalento(), 'USD') ?></td>
                        </tr>
                        <tr>
                            <td>Comisionistas</td>
                            <td style=" text-align: right;">  <?php echo format_currency($detalles_cotizacion->getGananciaComisionistas(), 'USD') ?></td>
                        </tr>
                        <tr style="border-top: 1px solid black;">
                            <td>Pagar a talento</td>
                            <td style=" text-align: right;">  <?php echo format_currency($detalles_cotizacion->getGananciaTalentoReal(), 'USD') ?></td>
                        </tr>
                        <tr>
                            <td>JerryMl</td>
                            <td style=" text-align: right;">  <?php echo format_currency($detalles_cotizacion->getGananciaJerryMl(), 'USD') ?></td>
                        </tr>
                        
                    </table>    
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <?php if (!$cotizacion->statusAprobada()): ?>
                        &nbsp; <input type="button" value="Editar" class="buttonEditar" onclick="$.editarTalento('<?php echo $detalles_cotizacion->getId() ?>');" />
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>  
            </tr>
        </tfoot>
    </table>

<?php if (!$sin_div): ?>    
    </div>
<?php endif; ?>
<script>
    $(document).ready(function(){
        $("input:submit, input:button,a.buttonCrearEvento").button();
    });
</script>   