<?php use_helper('I18N', 'Date') ?>
<?php include_partial('talentos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Editar talento %%name%%', array('%%name%%' => $talentos->getName()), 'messages') ?></h1>

  <?php include_partial('talentos/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('talentos/form_header', array('talentos' => $talentos, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('talentos/form', array('talentos' => $talentos, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('talentos/form_footer', array('talentos' => $talentos, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
