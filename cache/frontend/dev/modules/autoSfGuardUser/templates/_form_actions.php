<ul class="sf_admin_actions">
<?php if ($form->isNew()): ?>
  <?php echo $helper->linkToList(array(  'label' => 'Regresar a Lista',  'params' =>   array(  ),  'class_suffix' => 'list',)) ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'label' => 'Guardar',  'params' =>   array(  ),  'class_suffix' => 'save',)) ?>
  <?php echo $helper->linkToDelete($form->getObject(), array(  'label' => 'Eliminar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
<?php else: ?>
  <?php echo $helper->linkToList(array(  'label' => 'Regresar a Lista',  'params' =>   array(  ),  'class_suffix' => 'list',)) ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'label' => 'Guardar',  'params' =>   array(  ),  'class_suffix' => 'save',)) ?>
  <?php echo $helper->linkToDelete($form->getObject(), array(  'label' => 'Eliminar',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',)) ?>
<?php endif; ?>
</ul>
