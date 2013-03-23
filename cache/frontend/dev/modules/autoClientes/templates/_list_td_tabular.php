<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($clientes->getId(), 'clientes_edit', $clientes) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_razon_social">
  <?php echo $clientes->getRazonSocial() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_rfc">
  <?php echo $clientes->getRfc() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_active">
  <?php echo get_partial('clientes/list_field_boolean', array('value' => $clientes->getIsActive())) ?>
</td>
