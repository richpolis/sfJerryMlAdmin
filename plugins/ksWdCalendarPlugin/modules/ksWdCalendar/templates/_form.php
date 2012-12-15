<?php
use_helper("I18N");
use_stylesheets_for_form($form); 
use_javascripts_for_form($form); 
?>

<form id="fmEdit" class="fform" action="<?php echo url_for('ksWdCalendar/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php echo $form->renderHiddenFields(false) ?>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <!--<tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('ksWdCalendar/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'ksWdCalendar/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>-->
    <tfoot>
        <tr>
            <td>
                <ul>
                    <li>
                        <a id="Savebtn" class="imgbtn" href="javascript:void(0);">
            <span class="Save" title="<?php echo __("Save") ?>"><?php echo __("Save") ?>(<u>S</u>)</span>
        </a>
                    </li>
                    <?php if($form->getObject()->isNew() === false): ?>
                    <li>
                    <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">
            <span class="Delete" title="<?php echo __("Delete") ?>"><?php echo __("Delete") ?>(<u>D</u>)</span>
        </a>
       </li>                 
        <?php endif; ?>
       <li>
                    <a id="Closebtn" class="imgbtn" href="javascript:void(0);">
            <span class="Close" title="<?php echo __("Close") ?>" ><?php echo __("Close") ?></span>
        </a>
       </li>
       
                </ul>
            </td>
        </tr>
        
        
        
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
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
        <th><?php echo $form['recurring_rule']->renderLabel() ?></th>
        <td>
          <?php echo $form['recurring_rule']->renderError() ?>
          <?php echo $form['recurring_rule'] ?>
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
