<?php echo $helper->linkToNew(array(  'label' => 'Nueva Empresa',  'params' =>   array(  ),  'class_suffix' => 'new',)) ?>
<li class="sf_admin_action_cancelar">
  <?php echo link_to(__('Cancelar modo seleccion', array(), 'messages'), 'clientes/cancelarSeleccion', array()) ?>
</li>
