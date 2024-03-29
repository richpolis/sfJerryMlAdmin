<?php
use_helper("I18N");
use_stylesheets_for_form($form); 
use_javascripts_for_form($form); 
?>

<form id="form-eventos-usuarios-ajax" class="fform" action="<?php echo url_for('eventos_usuarios/'.($form->getObject()->isNew() ? 'createAjax' : 'updateAjax').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php echo $form->renderHiddenFields(false) ?>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <?php if(!$form["user_id"]->isHidden()):?>  
      <tr>
        <th><?php echo $form['user_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['user_id']->renderError() ?>
          <?php echo $form['user_id'] ?>
        </td>
      </tr>
      <?php else: ?>
      <tr>
        <th>Usuario</th>
        <td>
          <?php if($form->getObject()->getUserId()):?>
            <?php $usuario=Doctrine_Core::getTable("sfGuardUser")->find($form->getObject()->getUserId());?>
          <?php else: ?>
            <?php $usuario=$form->getObject()->getUser();?>
          <?php endif;?>
         <?php echo $usuario; ?>
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
          <?php echo $form['is_all_day_event']->render(Array("id" => "IsAllDayEvent")) ?>
        </td>
      </tr>
      
    </tbody>
  </table>
</form>
