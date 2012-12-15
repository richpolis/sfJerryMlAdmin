<td>
    <ul class="sf_admin_td_actions">
        <?php if (!$sf_user->getSeleccionarTalentos()): ?>  
            <li class="sf_admin_action_show">
                <?php echo link_to(__('Mostrar', array(), 'messages'), 'talentos/show?slug=' . $talentos->getSlug(), array()) ?>
            </li>
            <?php echo $helper->linkToEdit($talentos, array('label' => 'Editar', 'params' => array(), 'class_suffix' => 'edit',)) ?>
            <?php if($talentos->getIsActive()):?>
            <li class="sf_admin_action_inactive">
               <?php echo link_to(__('Inactivar', array(), 'messages'), 'talentos/inactive?slug='.$talentos->getSlug(), array()) ?>
            </li>
            <?php else:?>
            <li class="sf_admin_action_active">
                <?php echo link_to(__('Activar', array(), 'messages'), 'talentos/active?slug='.$talentos->getSlug(), array()) ?>
            </li>
            <?php endif;?>
        <?php else: ?>
            <li class="sf_admin_action_select">
                <?php echo link_to(__('Seleccionar', array(), 'messages'), 'talentos/ListSelect?slug=' . $talentos->getSlug(), array()) ?>
            </li>
        <?php endif; ?>
            <?php if(!$sf_user->getModoPrecotizacion()):?>
            <li class="sf_admin_action_calendario">
                <?php echo link_to(__('Calendario', array(), 'messages'), 'talentos/ListCalendar?slug=' . $talentos->getSlug(), array()) ?>
            </li>    
            <?php endif;?>
    </ul>
</td>
