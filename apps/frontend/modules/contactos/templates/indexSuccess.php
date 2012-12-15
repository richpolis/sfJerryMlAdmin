<?php use_helper('I18N', 'Date') ?>
<?php include_partial('contactos/assets') ?>

<div id="sf_admin_container">
  <h1>
      <?php if(!$sf_user->getSeleccionarContacto()):?>
      <?php echo __('Lista de Contactos', array(), 'messages') ?>
      <?php else:?>
      <?php echo __('Seleccionar Contacto', array(), 'messages') ?>
      <?php endif;?>
  </h1>

  <?php include_partial('contactos/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('contactos/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

  <!--div id="sf_admin_bar">
    <?php //include_partial('contactos/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div-->

  <div id="sf_admin_content">
    <form action="<?php echo url_for('contactos_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('contactos/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    
    <ul class="sf_admin_actions">
      <?php if(!$sf_user->getSeleccionarContacto()):?>  
      <?php //include_partial('contactos/list_batch_actions', array('helper' => $helper)) ?>
      <?php endif;?>  
      <?php include_partial('contactos/list_actions', array('helper' => $helper)) ?>
    </ul>
    
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('contactos/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
