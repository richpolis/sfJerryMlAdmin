<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($talentos->getId(), 'talentos_edit', $talentos) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_imagen">
  <?php echo get_partial('talentos/imagen', array('type' => 'list', 'talentos' => $talentos)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo $talentos->getName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_descripcion">
  <?php echo get_partial('talentos/descripcion', array('type' => 'list', 'talentos' => $talentos)) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_active">
  <?php echo get_partial('talentos/list_field_boolean', array('value' => $talentos->getIsActive())) ?>
</td>
