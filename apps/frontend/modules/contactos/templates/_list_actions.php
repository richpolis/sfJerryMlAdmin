<?php if (!$sf_user->getSeleccionarContacto()): ?>
    <?php echo $helper->linkToNew(array('label' => 'Nuevo Contacto', 'params' => array(), 'class_suffix' => 'new',)) ?>
<?php else: ?>  
    <li class="sf_admin_action_cancelar">
        <a href="<?php echo url_for('@cancelar_seleccionar_contacto')?>">Cancelar Seleccion</a>
    </li>
    <li class="sf_admin_action_crear_contacto_ajax">
        <a href="#" onclick="$.crearContacto();">Crear Contacto</a>
    </li>
<?php endif; ?>  
