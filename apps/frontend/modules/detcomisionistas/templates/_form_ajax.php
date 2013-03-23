<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_helper('Number') ?>
<form onsubmit="return false;" id="form-ajax" action="<?php echo url_for('detcomisionistas/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
        <th><?php echo $form['comisionista_id']->renderLabel() ?></th>
        <td>
          <?php if(!$form['comisionista_id']->isHidden()):?>  
            <?php echo $form['comisionista_id']->renderError() ?>
            <?php echo $form['comisionista_id'] ?>
          <?php else:?>
            <?php echo $form->getObject()->getComisionistas(); ?>
          <?php endif;?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['margen']->renderLabel() ?></th>
        <td>
          <?php echo $form['margen']->renderError() ?>
          <?php echo $form['margen'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ganancia']->renderLabel() ?></th>
        <td>
          <?php echo format_currency($form->getObject()->getGanancia(), 'USD') ?>  
        </td>
      </tr>
      <tr>
        <th><?php echo $form['nivel']->renderLabel() ?></th>
        <td>
          <?php echo $form->getObject()->getNivelString()?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
