<?php if($sf_user->hasCredential("admin")):?>
<?php echo $helper->linkToNew(array(  'label' => 'Nuevo usuario',  'params' =>   array(  ),  'class_suffix' => 'new',)) ?>
<?php endif;?>
