<td colspan="4">
  <?php echo __('%%id%% - %%razon_social%% - %%rfc%% - %%is_active%%', array('%%id%%' => link_to($clientes->getId(), 'clientes_edit', $clientes), '%%razon_social%%' => $clientes->getRazonSocial(), '%%rfc%%' => $clientes->getRfc(), '%%is_active%%' => get_partial('clientes/list_field_boolean', array('value' => $clientes->getIsActive()))), 'messages') ?>
</td>
