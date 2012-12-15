<?php if (!$sf_user->getSeleccionarRepresentante()): ?>
    <?php echo $helper->linkToNew(array('label' => 'Nuevo Representante', 'params' => array(), 'class_suffix' => 'new',)) ?>
<?php else: ?>  
    <li class="sf_admin_action_cancelar">
        <?php echo link_to(__('Cancelar modo seleccion', array(), 'messages'), 'representantes/cancelarSeleccion', array()) ?>
    </li>
<?php endif; ?>  
