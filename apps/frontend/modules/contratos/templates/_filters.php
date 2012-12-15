<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_filter">
  <?php if ($form->hasGlobalErrors()): ?>
    <?php echo $form->renderGlobalErrors() ?>
  <?php endif; ?>
   <h2>Buscar</h2>
  <form  action="<?php echo url_for('contratos_collection', array('action' => 'filter')) ?>" method="post">
    <table cellspacing="0"  style="width: 100%;">
      <tfoot>
        <tr>
          <td colspan="2">
            <?php echo $form->renderHiddenFields() ?>
            <?php echo link_to(__('Limpiar', array(), 'sf_admin'), 'contratos_collection', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post')) ?>
            <input type="submit" value="<?php echo __('Buscar', array(), 'sf_admin') ?>" />
          </td>
        </tr>
      </tfoot>
      <tbody>
          <tr>
        <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>
        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
          <?php include_partial('contratos/filters_field', array(
            'name'       => $name,
            'attributes' => $field->getConfig('attributes', array('style'=>'width:250px;')),
            'label'      => $field->getConfig('label'),
            'help'       => $field->getConfig('help'),
            'form'       => $form,
            'field'      => $field,
            'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_filter_field_'.$name,
          )) ?>
        <?php endforeach; ?>
          </tr>      
      </tbody>
    </table>
  </form>
</div>
