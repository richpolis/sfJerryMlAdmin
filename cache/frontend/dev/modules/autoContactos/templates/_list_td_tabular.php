<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($contactos->getId(), 'contactos_edit', $contactos) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_nombre_completo">
  <?php echo $contactos->getNombreCompleto() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_telefono">
  <?php echo $contactos->getTelefono() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_celular">
  <?php echo $contactos->getCelular() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email">
  <?php echo $contactos->getEmail() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_active">
  <?php echo get_partial('contactos/list_field_boolean', array('value' => $contactos->getIsActive())) ?>
</td>
