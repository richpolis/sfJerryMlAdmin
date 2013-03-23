<td colspan="6">
  <?php echo __('%%id%% - %%nombre_completo%% - %%telefono%% - %%celular%% - %%email%% - %%is_active%%', array('%%id%%' => link_to($contactos->getId(), 'contactos_edit', $contactos), '%%nombre_completo%%' => $contactos->getNombreCompleto(), '%%telefono%%' => $contactos->getTelefono(), '%%celular%%' => $contactos->getCelular(), '%%email%%' => $contactos->getEmail(), '%%is_active%%' => get_partial('contactos/list_field_boolean', array('value' => $contactos->getIsActive()))), 'messages') ?>
</td>
