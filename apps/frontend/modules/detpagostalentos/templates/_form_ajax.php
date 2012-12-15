<!--div id="detalles_pago_<?php echo $form->getObject()->getId()?>"-->
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
                
<form onsubmit="return false;" id="form-detalles-pago-ajax" action="<?php echo url_for('detpagostalentos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php echo $form->renderHiddenFields(false) ?>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table style="width: 100%">
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
        <th><?php echo $form['metodo_recibo']->renderLabel() ?></th>  
        <td>
          <?php echo $form['metodo_recibo']->renderError() ?>
          <?php echo $form['metodo_recibo']->render() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['importe']->renderLabel() ?></th>  
        <td>
          <?php echo $form['importe']->renderError() ?>
          <?php echo $form['importe']->render() ?>  
        </td>
      </tr>
      <tr>
        <th><?php echo $form['iva']->renderLabel() ?></th>  
        <td>
          <?php echo $form['iva']->renderError() ?>
          <?php echo $form['iva']->render() ?>  
        </td>
      </tr>
      <tr>
        <th><?php echo $form['isr']->renderLabel() ?></th>  
        <td>
          <?php echo $form['isr']->renderError() ?>
          <?php echo $form['isr']->render() ?>  
        </td>
      </tr>
    </tbody>
  </table>
</form>
        
<!--/div-->

