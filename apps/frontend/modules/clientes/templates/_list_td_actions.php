<td>
    <ul class="sf_admin_td_actions">
        <?php if (!$sf_user->getSeleccionarCliente()): ?>  
            <li class="sf_admin_action_show">
                <?php echo link_to(__('Mostrar', array(), 'messages'), 'clientes/show?slug=' . $clientes->getSlug(), array()) ?>
            </li>
            <?php echo $helper->linkToEdit($clientes, array('label' => 'Editar', 'params' => array(), 'class_suffix' => 'edit',)) ?>
            <?php if($clientes->getIsActive()):?>
            <li class="sf_admin_action_inactive">
               <?php echo link_to(__('Inactivar', array(), 'messages'), 'clientes/inactive?slug='.$clientes->getSlug(), array()) ?>
            </li>
            <?php else:?>
            <li class="sf_admin_action_active">
                <?php echo link_to(__('Activar', array(), 'messages'), 'clientes/active?slug='.$clientes->getSlug(), array()) ?>
            </li>
            <?php endif;?>
        <?php else: ?>
            <li class="sf_admin_action_select">
                <?php echo link_to(__('Seleccionar', array(), 'messages'), 'clientes/ListSelect?slug=' . $clientes->getSlug(), array()) ?>
            </li>
        <?php endif; ?>
    </ul>
</td>
