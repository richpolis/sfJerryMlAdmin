<ul class="sf_admin_actions">
<?php if ($form->isNew()): ?>
  <?php echo $helper->linkToList(array(  'label' => 'Regresar a Lista',  'params' =>   array(  ),  'class_suffix' => 'list',)) ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'label' => 'Guardar',  'params' =>   array(  ),  'class_suffix' => 'save',)) ?>
  <li class="sf_admin_action_canceledit">
<?php if (method_exists($helper, 'linkToCancelEdit')): ?>
  <?php echo $helper->linkToCancelEdit($form->getObject(), array(  'label' => 'Cancelar',  'action' => 'show',  'params' =>   array(  ),  'class_suffix' => 'canceledit',)) ?>
<?php else: ?>
  <?php echo link_to(__('Cancelar', array(), 'messages'), 'cotizaciones/show?id='.$cotizaciones->getId(), array()) ?>
<?php endif; ?>
  </li>
<?php else: ?>
  <?php echo $helper->linkToList(array(  'label' => 'Regresar a Lista',  'params' =>   array(  ),  'class_suffix' => 'list',)) ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'label' => 'Guardar',  'params' =>   array(  ),  'class_suffix' => 'save',)) ?>
  <li class="sf_admin_action_canceledit">
<?php if (method_exists($helper, 'linkToCancelEdit')): ?>
  <?php echo $helper->linkToCancelEdit($form->getObject(), array(  'label' => 'Cancelar',  'action' => 'show',  'params' =>   array(  ),  'class_suffix' => 'canceledit',)) ?>
<?php else: ?>
  <?php echo link_to(__('Cancelar', array(), 'messages'), 'cotizaciones/show?id='.$cotizaciones->getId(), array()) ?>
<?php endif; ?>
  </li>
<?php endif; ?>
</ul>
