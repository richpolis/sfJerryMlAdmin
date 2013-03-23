<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($cotizaciones->getId(), 'cotizaciones_edit', $cotizaciones) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_clientes">
  <?php echo $cotizaciones->getClientes() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contactos">
  <?php echo $cotizaciones->getContactos() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_manager">
  <?php echo $cotizaciones->getManager() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_descripcion">
  <?php echo $cotizaciones->getDescripcion() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_importe_sin_iva">
  <?php echo get_partial('cotizaciones/importe_sin_iva', array('type' => 'list', 'cotizaciones' => $cotizaciones)) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_pay">
  <?php echo get_partial('cotizaciones/list_field_boolean', array('value' => $cotizaciones->getIsPay())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_string_status">
  <?php echo $cotizaciones->getStringStatus() ?>
</td>
