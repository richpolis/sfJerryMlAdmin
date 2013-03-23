<?php use_helper('I18N', 'Date') ?>
<?php include_partial('cotizaciones/assets') ?>
<?php
    $app_name = $sf_context->getConfiguration()->getApplication();
    if(strcmp($sf_context->getConfiguration()->getEnvironment(),'prod') != 0)
    {$app_name .= '_'.$sf_context->getConfiguration()->getEnvironment();}
    if($app_name=="frontend"){
        $app_name="index";
    }
?>
<div id="sf_admin_container">
  <h1><?php echo __('Cotizacion', array(), 'messages') ?> ID: <?php echo $cotizaciones->getId();?> (<?php echo $cotizaciones->getStringStatus();?>)</h1>

  <?php include_partial('cotizaciones/flashes') ?>

  <div id="sf_admin_content">
    <form action="<?php echo url_for('cotizaciones_collection', array('action' => 'talentos')) ?>" method="post">
    <?php include_partial('cotizaciones/evento', array('cotizaciones' => $cotizaciones)) ?>
    <div id="talentos-cotizaciones">
        <?php include_partial('cotizaciones/talentos', array('cotizaciones' => $cotizaciones,"detalles_cotizaciones"=>$detalles_cotizaciones)) ?>
    </div>
    <div id="totales-cotizacion" style="display: none;">
        
    </div>    
    <?php include_partial('cotizaciones/pie_evento', array('cotizaciones' => $cotizaciones)) ?>
    <ul class="sf_admin_actions">
         <li>
             <a href="<?php echo url_for('@cotizaciones')?>">Listado</a>
         </li>
         <?php if(!$cotizaciones->statusIncompleto()):?>
         <li>
             <a href="<?php echo url_for('@reactivar_cotizacion?generar='.$cotizaciones->getId())?>" id="aprobar_cotizacion">Reactivar Cotizacion</a>
         </li>
         <?php if(!$cotizaciones->statusAprobada()):?>
            <?php echo $helper->linkToDelete($cotizaciones, array(  'label' => 'Eliminar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
        <?php endif;?>
         <?php elseif(!$cotizaciones->statusAprobada()):?>
         <li>
             <a href="<?php echo url_for('@seleccionar_talentos')?><?php echo '?modo=cotizaciones&goto=/'.$app_name.'.php/cotizaciones/'.$cotizaciones->getId().'&cotizacion='.$cotizaciones->getId();?>" id="vista_previa">Agregar Talentos</a>
         </li>
         <li>
             <a href="<?php echo url_for('@vista_previa_cotizacion?generar='.$cotizaciones->getId())?>" id="vista_previa" target="_blank">Vista Previa</a>
         </li>
         <li>
             <a href="<?php echo url_for('@preparar_envio_cotizacion?generar='.$cotizaciones->getId())?>" id="enviar_cliente_cotizacion">Enviar a Cliente</a>
         </li>
         <li>
             <a href="<?php echo url_for('@aprobar_cotizacion?generar='.$cotizaciones->getId())?>" id="aprobar_cotizacion">Aprobar Cotizacion</a>
         </li>
         <li>
             <a href="<?php echo url_for('@se_cayo_cotizacion?generar='.$cotizaciones->getId())?>" id="se_cayo_cotizacion">Se Cayo Cotizacion</a>
         </li>
         <?php if(!$cotizaciones->statusAprobada()):?>
            <?php echo $helper->linkToDelete($cotizaciones, array(  'label' => 'Eliminar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
         <?php endif;?>
         <?php elseif(!$cotizaciones->statusPagosLiberados()): ?>
         <li>
             <a href="<?php echo url_for('@cancelar_cotizacion?generar='.$cotizaciones->getId())?>" id="cancelar_cotizacion">Cancelar Aprobacion</a>
         </li>
         <li>
             <a href="<?php echo url_for('@liberar_pagos_cotizacion?generar='.$cotizaciones->getId())?>" id="liberar_pagos_cotizacion">Liberar Pagos a Talentos</a>
         </li>
         <?php endif; ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php //include_partial('cotizaciones/list_footer', array('pager' => $pager)) ?>
  </div>
</div>