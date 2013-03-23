<?php use_helper('I18N', 'Date') ?>
<?php include_partial('cotizaciones/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Crear Cotizacion', array(), 'messages') ?></h1>

  <?php include_partial('cotizaciones/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('cotizaciones/form_header', array('cotizaciones' => $cotizaciones, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('cotizaciones/form', array('cotizaciones' => $cotizaciones, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('cotizaciones/form_footer', array('cotizaciones' => $cotizaciones, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
