<?php if (!$sf_user->getSeleccionarCliente()): ?>
    <?php echo $helper->linkToNew(array('label' => 'Nueva Empresa', 'params' => array(), 'class_suffix' => 'new',)) ?>
<?php else: ?>  
    <li class="sf_admin_action_cancelar">
        <a href="<?php echo url_for('@cancelar_seleccionar_cliente')?>">Cancelar Seleccion</a>
    </li>
    <li class="sf_admin_action_crear_cliente_ajax">
        <a href="#" onclick="$.crearCliente();">Crear Empresa</a>
    </li>
<?php endif; ?>  
