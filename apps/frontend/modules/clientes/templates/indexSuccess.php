<?php use_helper('I18N', 'Date') ?>
<?php include_partial('clientes/assets') ?>

<div id="sf_admin_container">
  <h1>
      <?php if(!$sf_user->getSeleccionarCliente()):?>
      <?php echo __('Lista de Empresas', array(), 'messages') ?>
      <?php else:?>
      <?php echo __('Seleccionar Empresa', array(), 'messages') ?>
      <?php endif;?>
  </h1>

  <?php include_partial('clientes/flashes') ?>

  <div id="sf_admin_header">
    <?php //include_partial('clientes/list_header', array('pager' => $pager)) ?>
    <?php include_partial('clientes/filters', array('form' => $filters, 'configuration' => $configuration)) ?>  
  </div>

  <!--div id="sf_admin_bar">
    <?php //include_partial('clientes/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div-->

  <div id="sf_admin_content">
    <form action="<?php echo url_for('clientes_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('clientes/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    
    
    <ul class="sf_admin_actions">
      <?php if(!$sf_user->getSeleccionarCliente()):?>  
      <?php //include_partial('clientes/list_batch_actions', array('helper' => $helper)) ?>
      <?php endif; ?>    
      <?php include_partial('clientes/list_actions', array('helper' => $helper)) ?>
    </ul>
    
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('clientes/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
