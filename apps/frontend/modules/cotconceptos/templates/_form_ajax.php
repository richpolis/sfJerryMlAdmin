<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form onsubmit="return false;" id="form-ajax" action="<?php echo url_for('cotconceptos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          <!--input type="submit" value="Save" /-->
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['concepto_id']->renderLabel() ?></th>
        <td>
          <?php if(!$form['concepto_id']->isHidden()):?>  
            <?php echo $form['concepto_id']->renderError() ?>
            <?php echo $form['concepto_id'] ?>
          <?php else:?>
            <?php echo $form->getObject()->getConceptos(); ?>
          <?php endif;?>  
        </td>
      </tr>
      <tr>
        <th><?php echo $form['precio']->renderLabel() ?></th>
        <td>
          <?php echo $form['precio']->renderError() ?>
          <?php echo $form['precio'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
