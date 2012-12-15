<?php if (!$sf_user->getSeleccionarTalentos()): ?>
    <?php echo $helper->linkToNew(array('label' => 'Nuevo Talento', 'params' => array(), 'class_suffix' => 'new',)) ?>
<?php else: ?>  
    <li class="sf_admin_action_cancelar">
        <a href="<?php echo url_for('@cancelar_seleccionar_talento')?>">Cancelar Seleccion</a>
    </li>
    <?php if(count($sf_user->getTalentos())>0):?>
    <li class="sf_admin_action_finalizar">
        <a href="<?php echo url_for('@finalizar_seleccionar_talento')?>">Finalizar Seleccion</a>
    </li>
    <?php endif; ?>
<?php endif; ?>  
