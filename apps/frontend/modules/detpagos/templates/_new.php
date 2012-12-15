<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
                
<form onsubmit="return false;" id="form_cotizacion_<?php echo $form->getObject()->getCotizacionId()?>" action="<?php echo url_for('detpagos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table style="width: 100%">
    <tfoot>
      <tr>
        <td colspan="5">
          <?php echo $form->renderHiddenFields(false) ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['fecha_pago']->renderLabel() ?></th>  
        <th><?php echo $form['tipo_pago']->renderLabel() ?></th>
        <th><?php echo $form['importe']->renderLabel() ?></th>
      </tr>
      <tr>
        <td width="30%">
          <?php echo $form['fecha_pago']->renderError() ?>
          <?php echo $form['fecha_pago']->render() ?>
        </td>  
        <td width="30%">
          <?php echo $form['tipo_pago']->renderError() ?>
          <?php echo $form['tipo_pago']->render() ?>
        </td>
        <td width="40%">
          <?php echo $form['importe']->renderError() ?>
          <?php echo $form['importe']->render() ?>  
        </td>
        <td colspan="3">
          <input type="submit"  value="Guardar" id="buttonGuardar"  onclick="$.guardarDetallePag0('<?php echo $form->getObject()->getCotizacionId() ?>')" />
        </td>
      </tr>
    </tbody>
  </table>
</form>
        
<!--/div-->

