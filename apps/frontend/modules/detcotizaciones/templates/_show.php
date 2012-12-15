<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
<?php
    $app_name = $sf_context->getConfiguration()->getApplication();
    if(strcmp($sf_context->getConfiguration()->getEnvironment(),'prod') != 0)
    {$app_name .= '_'.$sf_context->getConfiguration()->getEnvironment();}
    
    if($app_name=="frontend"){
        $app_name="index";
    }
?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php $talento = $detalles_cotizacion->getTalentos(); ?>
<?php $cotizacion = $detalles_cotizacion->getCotizaciones(); ?>
<?php $eventos = Doctrine_Core::getTable('CotizacionesEventos')->findBy('cotizacion_id', $cotizacion->getId()) ?>
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
                    <?php //echo $detalles_cotizacion->getActividad(ESC_RAW) ?>
                    <table border="0" style="width:100%;">
                        <tr>
                            <td width="100%">
                                <?php foreach ($eventos as $evento): ?>
                                   <?php if($evento->getEventos()->getTalentoId()==$talento->getId()):?>
                                    <div id="eventos-<?php echo $evento->getEventos()->getId() ?>" style="widith:100%;">
                                        <table width="100%" >
                                            <tr>
                                                <td width="75%">
                                                     <?php echo $evento->getEventos(); ?>
                                                </td>
                                                <td width="25%">
                                                    <?php if(!$evento->getEventos()->statusApartado()):?>
                                                    <input type="button" value="Editar Evento" class="buttonEditarEvento" onclick="$.editarEvento('<?php echo $evento->getEventos()->getId() ?>');" />
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                   <?php endif;?>
                                <?php endforeach; ?>
                                <div id="detalles-cotizacion-evento-<?php echo $detalles_cotizacion->getId() ?>" style="widith:100%;">
                                    <table width="100%" >
                                        <tr>
                                            <td width="75%">
                                                &nbsp;
                                            </td>
                                            <td width="25%">
                                                <?php if(!$detalles_cotizacion->getIsPayTalento()):?>
                                                <a class="buttonCrearEvento" href="<?php echo url_for('talentos/ListCalendar?slug=' . $talento->getSlug().'&modo=cotizaciones&goto=/'.$app_name.'.php/cotizaciones/'.$detalles_cotizacion->getCotizacionId().'&return_talentos=false')?>">Crear Evento</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="20%">
                    <table width="100%">
                       <tr>
                            <td>Importe <?php echo format_currency($detalles_cotizacion->getSubtotal(), 'USD') ?></td>
                        </tr> 
                        
                        <tr>
                            <td>Talento <?php echo format_currency($detalles_cotizacion->getGananciaTalento(), 'USD') ?></td>
                        </tr>
                        <tr>
                            <td>JerryMl <?php echo format_currency($detalles_cotizacion->getGananciaJerryml(), 'USD') ?></td>
                        </tr>
                        <?php if ($detalles_cotizacion->getGananciaComisionista() > 0): ?>
                            <tr>
                                <td>Comisionista <?php echo format_currency($detalles_cotizacion->getGananciaComisionista(), 'USD') ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>    

                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <?php if(!$cotizacion->statusAprobada()):?>
                    &nbsp; <input type="button" value="Editar" class="buttonEditar" onclick="$.editarTalento('<?php echo $detalles_cotizacion->getId() ?>');" />
                    <?php else:?>
                    &nbsp;
                    <?php endif;?>
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