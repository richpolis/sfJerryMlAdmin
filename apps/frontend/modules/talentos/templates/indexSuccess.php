<?php use_helper('I18N', 'Date') ?>
<?php include_partial('talentos/assets') ?>

<div id="sf_admin_container">
  <h1>
      <?php if(!$sf_user->getSeleccionarTalentos()):?>
      <?php echo __('Lista de Talentos', array(), 'messages') ?>
      <?php else:?>
      <?php echo __('Seleccionar Talentos', array(), 'messages') ?>
      <?php endif;?>
  </h1>

  <?php include_partial('talentos/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('talentos/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

  <!--div id="sf_admin_bar">
    <?php //include_partial('talentos/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div-->

  <div id="sf_admin_content">
    <form action="<?php echo url_for('talentos_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('talentos/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    
    <ul class="sf_admin_actions">
      <?php //if(!$sf_user->getSeleccionarTalentos()):?>  
      <?php include_partial('talentos/list_batch_actions', array('helper' => $helper)) ?>
      <?php //endif;?>  
      <?php include_partial('talentos/list_actions', array('helper' => $helper)) ?>
    </ul>
    
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('talentos/list_footer', array('talentosSeleccionados' => $talentosSeleccionados)) ?>
  </div>
</div>
