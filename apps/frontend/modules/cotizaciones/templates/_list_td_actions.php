<td>
  <ul class="sf_admin_td_actions">
    <?php if (!$sf_user->getSeleccionarCotizaciones()): ?>    
        <li class="sf_admin_action_show">
          <?php echo link_to(__('Mostrar', array(), 'messages'), 'cotizaciones/show?id='.$cotizaciones->getId(), array()) ?>
        </li>
        <?php if(!$cotizaciones->statusAprobada()):?>
        <?php echo $helper->linkToDelete($cotizaciones, array(  'label' => 'Eliminar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
        <?php endif;?>
    <?php else: ?>
    <li class="sf_admin_action_select">
        <?php echo link_to(__('Seleccionar', array(), 'messages'), 'cotizaciones/ListSelect?id=' . $cotizaciones->getId(), array()) ?>
    </li>
    <?php endif; ?>
  </ul>
</td>