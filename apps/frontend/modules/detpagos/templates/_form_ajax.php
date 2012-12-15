<!--div id="detalles_pago_<?php echo $form->getObject()->getId()?>"-->
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
                
<form onsubmit="return false;" id="form-detalles-pago-ajax" action="<?php echo url_for('detpagos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php echo $form->renderHiddenFields(false) ?>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table style="width: 100%">
      <!--thead>
        <tr>
            <th colspan="3">
                  <?php echo 'Cotizacion: '.$form->getObject()->getCotizaciones() ?>
             </th>
        </tr>
      </thead-->  
    <!--tfoot>
      <tr>
        <td colspan="3">
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<input type="button"  value="Eliminar" id="buttonEliminar" onclick="$.borrarCotizacion('<?php echo $form->getObject()->getId() ?>')"/>
          <?php endif; ?>
            <input type="submit"  value="Guardar" id="buttonGuardar"  onclick="$.guardarCotizacion('<?php echo $form->getObject()->getId() ?>')" />
        </td>
      </tr>
    </tfoot-->
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['fecha_pago']->renderLabel() ?></th>  
        <td>
          <?php echo $form['fecha_pago']->renderError() ?>
          <?php echo $form['fecha_pago']->render() ?>
        </td> 
        
      </tr>
      <tr>
        <th><?php echo $form['tipo_pago']->renderLabel() ?></th>  
        <td>
          <?php echo $form['tipo_pago']->renderError() ?>
          <?php echo $form['tipo_pago']->render() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['importe']->renderLabel() ?></th>  
        <td>
          <?php echo $form['importe']->renderError() ?>
          <?php echo $form['importe']->render() ?>  
        </td>
      </tr>
    </tbody>
  </table>
</form>
        
<!--/div-->

