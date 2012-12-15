<td>
  <ul class="sf_admin_td_actions">
    <li class="sf_admin_action_show">
      <?php echo link_to(__('Mostrar', array(), 'messages'), 'precotizaciones/show?id='.$precotizaciones->getId(), array()) ?>
    </li>
    <?php if(!$precotizaciones->statusAprobada()):?>
    <?php echo $helper->linkToDelete($precotizaciones, array(  'label' => 'Eliminar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
    <?php endif; ?>
  </ul>
</td>
