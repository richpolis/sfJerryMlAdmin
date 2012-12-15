<td>
    <ul class="sf_admin_td_actions">
        <?php if (!$sf_user->getSeleccionarContacto()): ?>  
            <li class="sf_admin_action_show">
                <?php echo link_to(__('Mostrar', array(), 'messages'), 'contactos/show?slug=' . $contactos->getSlug(), array()) ?>
            </li>
            <?php echo $helper->linkToEdit($contactos, array('label' => 'Editar', 'params' => array(), 'class_suffix' => 'edit',)) ?>
            <?php if($contactos->getIsActive()):?>
            <li class="sf_admin_action_inactive">
               <?php echo link_to(__('Inactivar', array(), 'messages'), 'contactos/inactive?slug='.$contactos->getSlug(), array()) ?>
            </li>
            <?php else:?>
            <li class="sf_admin_action_active">
                <?php echo link_to(__('Activar', array(), 'messages'), 'contactos/active?slug='.$contactos->getSlug(), array()) ?>
            </li>
            <?php endif;?>
        <?php else: ?>
            <li class="sf_admin_action_select">
                <?php echo link_to(__('Seleccionar', array(), 'messages'), 'contactos/ListSelect?slug=' . $contactos->getSlug(), array()) ?>
            </li>
        <?php endif; ?>
    </ul>
</td>
