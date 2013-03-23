<td>
  <ul class="sf_admin_td_actions">
    <?php echo $helper->linkToEdit($sf_guard_user, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
    <?php echo $helper->linkToDelete($sf_guard_user, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
    <li class="sf_admin_action_calendario">
      <?php echo link_to(__('Calendario', array(), 'messages'), 'sfGuardUser/ListCalendario?id='.$sf_guard_user->getId(), array()) ?>
    </li>
  </ul>
</td>
