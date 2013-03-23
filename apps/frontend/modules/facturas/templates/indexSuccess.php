<?php use_helper('I18N', 'Date') ?>
<?php include_partial('contratos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Contratos', array(), 'messages') ?></h1>

  <?php include_partial('contratos/flashes') ?>

  <div id="sf_admin_header">
    <?php //include_partial('contratos/list_header', array('pager' => $pager)) ?>
    <?php include_partial('contratos/filters', array('form' => $filters, 'configuration' => $configuration)) ?>  
  </div>

  <!--div id="sf_admin_bar">
    <?php //include_partial('contratos/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div-->

  <div id="sf_admin_content">
    <form action="<?php echo url_for('contratos_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('contratos/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('contratos/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('contratos/list_actions', array('helper' => $helper)) ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('contratos/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
