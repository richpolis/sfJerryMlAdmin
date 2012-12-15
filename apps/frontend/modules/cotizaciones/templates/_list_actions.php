<?php if (!$sf_user->getSeleccionarCotizaciones()): ?>
    <li class="sf_admin_action_crear">
    <?php echo link_to(__('Nueva Cotizacion', array(), 'messages'), 'cotizaciones/crearCotizacion', array()) ?>
  </li>
<?php else: ?>  
    <li class="sf_admin_action_cancelar">
        <a href="<?php echo url_for('@cancelar_seleccionar_cotizacion')?>">Cancelar Seleccion</a>
    </li>
    <?php if(count($sf_user->getCotizaciones())>0):?>
    <li class="sf_admin_action_finalizar">
        <a href="<?php echo url_for('@finalizar_seleccionar_cotizacion')?>">Finalizar Seleccion</a>
    </li>
    <?php endif; ?>
<?php endif; ?>  
