<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form onsubmit="return false;" id="form-conceptos-ajax" action="<?php echo url_for('detconceptos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
        <th><?php echo $form['detalles_cotizacion_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['detalles_cotizacion_id']->renderError() ?>
          <?php echo $form['detalles_cotizacion_id'] ?>
          <?php echo $form->getObject()->getDetallesCotizacion()?>  
        </td>
      </tr>
      <tr>
        <th><?php echo $form['concepto_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['concepto_id']->renderError() ?>
          <?php echo $form['concepto_id'] ?>
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
