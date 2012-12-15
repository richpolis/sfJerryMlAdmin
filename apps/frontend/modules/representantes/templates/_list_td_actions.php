<td>
    <ul class="sf_admin_td_actions">
        <?php if (!$sf_user->getSeleccionarRepresentante()): ?>  
            <li class="sf_admin_action_show">
                <?php echo link_to(__('Mostrar', array(), 'messages'), 'representantes/ListShow?id=' . $representantes->getId(), array()) ?>
            </li>
            <?php echo $helper->linkToEdit($representantes, array('label' => 'Editar', 'params' => array(), 'class_suffix' => 'edit',)) ?>
            <?php echo $helper->linkToDelete($representantes, array('label' => 'Eliminar', 'params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete',)) ?>
        <?php else: ?>
            <li class="sf_admin_action_select">
                <?php echo link_to(__('Seleccionar', array(), 'messages'), 'representantes/ListSelect?id=' . $representantes->getId(), array()) ?>
            </li>
        <?php endif; ?>
    </ul>
</td>
