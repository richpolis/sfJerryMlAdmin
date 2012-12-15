<td>
    <ul class="sf_admin_td_actions">
        <?php if (!$sf_user->getSeleccionarTalentos()): ?>  
            <li class="sf_admin_action_show">
                <?php echo link_to(__('Mostrar', array(), 'messages'), 'talentos/ListShow?slug=' . $talentos->getSlug(), array()) ?>
            </li>
            <?php echo $helper->linkToEdit($talentos, array('label' => 'Editar', 'params' => array(), 'class_suffix' => 'edit',)) ?>
            <?php echo $helper->linkToDelete($talentos, array('label' => 'Eliminar', 'params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete',)) ?>
        <?php else: ?>
            <li class="sf_admin_action_select">
                <?php echo link_to(__('Seleccionar', array(), 'messages'), 'talentos/ListSelect?slug=' . $talentos->getSlug(), array()) ?>
            </li>
        <?php endif; ?>
            <li class="sf_admin_action_calendario">
                <?php echo link_to(__('Calendario', array(), 'messages'), 'talentos/ListCalendar?slug=' . $talentos->getSlug(), array()) ?>
            </li>    
    </ul>
</td>
