<?php echo $helper->linkToNew(array(  'label' => 'Nuevo Contacto',  'params' =>   array(  ),  'class_suffix' => 'new',)) ?>
<li class="sf_admin_action_cancelar">
  <?php echo link_to(__('Cancelar modo seleccion', array(), 'messages'), 'contactos/cancelarSeleccion', array()) ?>
</li>
