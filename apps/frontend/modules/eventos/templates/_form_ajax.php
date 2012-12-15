<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<h2>Status: <?php echo $form->getObject()->getStatusString()?></h2>
<form id="form-eventos-ajax" action="<?php echo url_for('eventos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
         
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <?php if($form->getObject()->getTalentoId()==0):?>  
      <tr>
        <th><?php echo $form['talento_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['talento_id']->renderError() ?>
          <?php echo $form['talento_id'] ?>
        </td>
      </tr>
      <?php else: ?>
      <tr>
        <th>Talento</th>
        <td>
          <?php echo $form->getObject()->getTalentos(); ?>
        </td>
      </tr>
      
      <?php endif;?>  
        
      <tr>
        <th><?php echo $form['subject']->renderLabel() ?></th>
        <td>
          <?php echo $form['subject']->renderError() ?>
          <?php echo $form['subject'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['description']->renderLabel() ?></th>
        <td>
          <?php echo $form['description']->renderError() ?>
          <?php echo $form['description'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['start_time']->renderLabel() ?></th>
        <td>
          <?php echo $form['start_time']->renderError() ?>
          <?php echo $form['start_time'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['end_time']->renderLabel() ?></th>
        <td>
          <?php echo $form['end_time']->renderError() ?>
          <?php echo $form['end_time'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['is_all_day_event']->renderLabel() ?></th>
        <td>
          <?php echo $form['is_all_day_event']->renderError() ?>
          <?php echo $form['is_all_day_event'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lugar_evento']->renderLabel() ?></th>
        <td>
          <?php echo $form['lugar_evento']->renderError() ?>
          <?php echo $form['lugar_evento'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['calle']->renderLabel() ?></th>
        <td>
          <?php echo $form['calle']->renderError() ?>
          <?php echo $form['calle'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['numero_exterior']->renderLabel() ?></th>
        <td>
          <?php echo $form['numero_exterior']->renderError() ?>
          <?php echo $form['numero_exterior'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['numero_interior']->renderLabel() ?></th>
        <td>
          <?php echo $form['numero_interior']->renderError() ?>
          <?php echo $form['numero_interior'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['colonia']->renderLabel() ?></th>
        <td>
          <?php echo $form['colonia']->renderError() ?>
          <?php echo $form['colonia'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['codigo_postal']->renderLabel() ?></th>
        <td>
          <?php echo $form['codigo_postal']->renderError() ?>
          <?php echo $form['codigo_postal'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['cuidad']->renderLabel() ?></th>
        <td>
          <?php echo $form['cuidad']->renderError() ?>
          <?php echo $form['cuidad'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['municipio']->renderLabel() ?></th>
        <td>
          <?php echo $form['municipio']->renderError() ?>
          <?php echo $form['municipio'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['estado']->renderLabel() ?></th>
        <td>
          <?php echo $form['estado']->renderError() ?>
          <?php echo $form['estado'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['pais']->renderLabel() ?></th>
        <td>
          <?php echo $form['pais']->renderError() ?>
          <?php echo $form['pais'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
