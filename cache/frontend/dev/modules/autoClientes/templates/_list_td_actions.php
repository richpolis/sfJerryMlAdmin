<td>
  <ul class="sf_admin_td_actions">
    <li class="sf_admin_action_show">
      <?php echo link_to(__('Mostrar', array(), 'messages'), 'clientes/show?id='.$clientes->getId(), array()) ?>
    </li>
    <?php echo $helper->linkToEdit($clientes, array(  'label' => 'Editar',  'params' =>   array(  ),  'class_suffix' => 'edit',)) ?>
    <li class="sf_admin_action_inactive">
      <?php echo link_to(__('Inactivar', array(), 'messages'), 'clientes/inactive?id='.$clientes->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_active">
      <?php echo link_to(__('Activar', array(), 'messages'), 'clientes/active?id='.$clientes->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_select">
      <?php echo link_to(__('Seleccionar', array(), 'messages'), 'clientes/ListSelect?id='.$clientes->getId(), array()) ?>
    </li>
  </ul>
</td>
