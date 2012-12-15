<?php use_helper('I18N', 'Date') ?>
<?php include_partial('pagos_talentos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Pagos Talentos', array(), 'messages') ?></h1>

  <?php include_partial('pagos_talentos/flashes') ?>

  <div id="sf_admin_content">
    <?php include_partial('pagos_talentos/pago', array('pagos_talentos' => $pagos_talentos)) ?>
    <?php include_partial('pagos_talentos/cotizaciones', array('pagos_talentos' => $pagos_talentos,"detalles_cotizacion"=>$detalles_cotizacion)) ?>
    <ul class="sf_admin_actions">
         <li>
             <a href="<?php echo url_for('@pagos_talentos')?>">Listado</a>
         </li>
         <!--li>
             <a href="<?php echo url_for('@seleccionar_cotizaciones')?><?php echo '?modo=pagos_talentos&goto=/index.php/pagos_talentos/'.$pagos_talentos->getId().'&talento='.$pagos_talentos->getTalentoId()."&pago_talento=".$pagos_talentos->getId();?>" id="seleccionar_cotizacion">Agregar cotizaciones</a>
         </li-->
         <li>
             <a href="<?php echo url_for('@aprobar_pagos_talentos?generar='.$pagos_talentos->getId())?>" id="aprobar_pago">Aprobar Pagos</a>
         </li>
         <li>
             <a href="<?php echo url_for('@liberar_pagos_talentos?generar='.$pagos_talentos->getId())?>" id="aprobar_pago">Liberar Pagos</a>
         </li>
    </ul>
  </div>

  <div id="sf_admin_footer">
    <?php //include_partial('pagos_talentos/list_footer', array('pager' => $pager)) ?>
  </div>
</div>