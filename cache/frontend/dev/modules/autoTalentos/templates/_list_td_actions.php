<td>
  <ul class="sf_admin_td_actions">
    <li class="sf_admin_action_show">
      <?php echo link_to(__('Mostrar', array(), 'messages'), 'talentos/show?id='.$talentos->getId(), array()) ?>
    </li>
    <?php echo $helper->linkToEdit($talentos, array(  'label' => 'Editar',  'params' =>   array(  ),  'class_suffix' => 'edit',)) ?>
    <li class="sf_admin_action_inactive">
      <?php echo link_to(__('Inactivar', array(), 'messages'), 'talentos/inactive?id='.$talentos->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_active">
      <?php echo link_to(__('Activar', array(), 'messages'), 'talentos/active?id='.$talentos->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_select">
      <?php echo link_to(__('Seleccionar', array(), 'messages'), 'talentos/ListSelect?id='.$talentos->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_remove">
      <?php echo link_to(__('Quitar Seleccion', array(), 'messages'), 'talentos/ListRemove?id='.$talentos->getId(), array()) ?>
    </li>
    <li class="sf_admin_action_calendar">
      <?php echo link_to(__('Calendario', array(), 'messages'), 'talentos/ListCalendar?id='.$talentos->getId(), array()) ?>
    </li>
  </ul>
</td>
