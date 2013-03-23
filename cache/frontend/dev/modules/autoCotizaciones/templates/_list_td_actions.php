<td>
  <ul class="sf_admin_td_actions">
    <li class="sf_admin_action_show">
      <?php echo link_to(__('Mostrar', array(), 'messages'), 'cotizaciones/show?id='.$cotizaciones->getId(), array()) ?>
    </li>
    <?php echo $helper->linkToDelete($cotizaciones, array(  'label' => 'Desactivar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
    <li class="sf_admin_action_select">
      <?php echo link_to(__('Seleccionar', array(), 'messages'), 'cotizaciones/ListSelect?id='.$cotizaciones->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_remove">
      <?php echo link_to(__('Quitar Seleccion', array(), 'messages'), 'cotizaciones/ListRemove?id='.$cotizaciones->getId(), array()) ?>
    </li>
  </ul>
</td>
