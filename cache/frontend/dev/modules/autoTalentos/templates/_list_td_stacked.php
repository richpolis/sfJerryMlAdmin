<td colspan="5">
  <?php echo __('%%id%% - %%imagen%% - %%name%% - %%descripcion%% - %%is_active%%', array('%%id%%' => link_to($talentos->getId(), 'talentos_edit', $talentos), '%%imagen%%' => get_partial('talentos/imagen', array('type' => 'list', 'talentos' => $talentos)), '%%name%%' => $talentos->getName(), '%%descripcion%%' => get_partial('talentos/descripcion', array('type' => 'list', 'talentos' => $talentos)), '%%is_active%%' => get_partial('talentos/list_field_boolean', array('value' => $talentos->getIsActive()))), 'messages') ?>
</td>
