<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('detcomisionistas/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('detcomisionistas/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'detcomisionistas/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['comisionista_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['comisionista_id']->renderError() ?>
          <?php echo $form['comisionista_id'] ?>
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
          <?php echo $form['ganancia']->renderError() ?>
          <?php echo $form['ganancia'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['nivel']->renderLabel() ?></th>
        <td>
          <?php echo $form['nivel']->renderError() ?>
          <?php echo $form['nivel'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
