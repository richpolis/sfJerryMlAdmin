<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('detcotizaciones/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'detcotizaciones/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['actividad']->renderLabel() ?></th>
        <th><?php echo $form['precio']->renderLabel() ?></th>
      </tr>
      <tr>
        <td>
          <?php echo $form['actividad']->renderError() ?>
          <?php echo $form['actividad'] ?>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td>
                        <?php echo $form['precio']->renderError() ?>
                        <?php echo $form['precio'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $form['margen_jerry_ml']->renderError() ?>
                        <?php echo $form['margen_jerry_ml'] ?>
                    </td>
                </tr>
            </table>  
          
        </td>
      </tr>
    </tbody>
  </table>
</form>
