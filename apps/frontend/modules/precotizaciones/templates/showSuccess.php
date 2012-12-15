<?php use_helper('I18N', 'Date') ?>
<?php include_partial('precotizaciones/assets') ?>
<?php
    $app_name = $sf_context->getConfiguration()->getApplication();
    if(strcmp($sf_context->getConfiguration()->getEnvironment(),'prod') != 0)
    {$app_name .= '_'.$sf_context->getConfiguration()->getEnvironment();}
    if($app_name=="frontend"){
        $app_name="index";
    }
?>

<div id="sf_admin_container">
  <h1><?php echo __('Precotizacion', array(), 'messages') ?> ID: <?php echo $precotizaciones->getId();?> (<?php echo $precotizaciones->getStringStatus();?>)</h1>

  <?php include_partial('precotizaciones/flashes') ?>



  <div id="sf_admin_content">
    <form action="<?php echo url_for('precotizaciones_collection', array('action' => 'talentos')) ?>" method="post">
    <?php include_partial('precotizaciones/evento', array('precotizaciones' => $precotizaciones)) ?>
    <div id="talentos-precotizaciones">
        <?php include_partial('precotizaciones/talentos', array('precotizaciones' => $precotizaciones,"detalles_precotizaciones"=>$detalles_precotizaciones)) ?>
    </div>    
    <ul class="sf_admin_actions">
         <li>
             <a href="<?php echo url_for('@precotizaciones')?>">Listado</a>
         </li>
         <?php if(!$precotizaciones->statusAprobada()):?>
         <li>
             <a href="<?php echo url_for('@seleccionar_talentos')?><?php echo '?modo=precotizaciones&goto=/'.$app_name.'.php/precotizaciones/'.$precotizaciones->getId().'&precotizacion='.$precotizaciones->getId();?>">Agregar Talentos</a>
         </li>
         <li>
             <a href="<?php echo url_for('@vista_previa_precotizacion?generar='.$precotizaciones->getId())?>" id="vista_previa" target="_blank">Vista Previa</a>
         </li>
         <li>
             <a href="<?php echo url_for('@preparar_envio_precotizacion?generar='.$precotizaciones->getId())?>" id="enviar_cliente_precotizacion">Enviar a Cliente</a>
         </li>
         <li>
             <a href="<?php echo url_for('@aprobar_precotizacion?generar='.$precotizaciones->getId())?>" id="aprobar_precotizacion">Aprobar Pre-Cotizacion</a>
         </li>
         <?php else: ?>
         <li>
             <a href="<?php echo url_for('@cancelar_precotizacion?generar='.$precotizaciones->getId())?>" id="cancelar_precotizacion">Cancelar Aprobacion</a>
         </li>
         <?php endif; ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php //include_partial('precotizaciones/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
