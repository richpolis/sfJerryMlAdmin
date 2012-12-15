<?php use_helper('I18N', 'Date') ?>
<?php include_partial('cotizaciones/assets') ?>

<div id="sf_admin_container">
  <h1>
      <?php if(!$sf_user->getSeleccionarCotizaciones()):?>
      <?php echo __('Cotizaciones', array(), 'messages') ?>
      <?php else:?>
      <?php echo __('Seleccionar Cotizaciones', array(), 'messages') ?>
      <?php endif;?>
  </h1>

  <?php include_partial('cotizaciones/flashes') ?>

  <div id="sf_admin_header">
    <?php //include_partial('cotizaciones/list_header', array('pager' => $pager)) ?>
    <?php include_partial('cotizaciones/filters', array('form' => $filters, 'configuration' => $configuration)) ?>  
  </div>

  <!--div id="sf_admin_bar">
    <?php //include_partial('cotizaciones/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div-->

  <div id="sf_admin_content">
    <form action="<?php echo url_for('cotizaciones_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('cotizaciones/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('cotizaciones/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('cotizaciones/list_actions', array('helper' => $helper)) ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('cotizaciones/list_footer', array('cotizacionesSeleccionadas' => $cotizacionesSeleccionadas)) ?>
  </div>
</div>
