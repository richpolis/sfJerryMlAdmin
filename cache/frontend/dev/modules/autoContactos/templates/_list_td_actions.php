<td>
  <ul class="sf_admin_td_actions">
    <li class="sf_admin_action_show">
      <?php echo link_to(__('Mostrar', array(), 'messages'), 'contactos/ListShow?id='.$contactos->getId(), array()) ?>
    </li>
    <?php echo $helper->linkToEdit($contactos, array(  'label' => 'Editar',  'params' =>   array(  ),  'class_suffix' => 'edit',)) ?>
    <li class="sf_admin_action_inactive">
      <?php echo link_to(__('Inactivar', array(), 'messages'), 'contactos/inactive?id='.$contactos->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_active">
      <?php echo link_to(__('Activar', array(), 'messages'), 'contactos/active?id='.$contactos->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_select">
      <?php echo link_to(__('Seleccionar', array(), 'messages'), 'contactos/ListSelect?id='.$contactos->getId(), array()) ?>
    </li>
  </ul>
</td>
