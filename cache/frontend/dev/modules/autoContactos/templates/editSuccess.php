<?php use_helper('I18N', 'Date') ?>
<?php include_partial('contactos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Editar contacto %%name%% %%apellidos%%', array('%%name%%' => $contactos->getName(), '%%apellidos%%' => $contactos->getApellidos()), 'messages') ?></h1>

  <?php include_partial('contactos/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('contactos/form_header', array('contactos' => $contactos, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('contactos/form', array('contactos' => $contactos, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('contactos/form_footer', array('contactos' => $contactos, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
