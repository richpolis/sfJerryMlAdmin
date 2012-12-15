<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_helper('I18N')?>
<?php if(!isset($ajax)) $ajax=false;?>


<div class="sf_admin_form">
  <p class="validateTips" style="border: 1px solid transparent; padding: 0.3em;display: none;">El nombre, apellidos y email son campos requeridos.</p>
  <?php echo form_tag_for($form, '@contactos') ?>
    <?php echo $form->renderHiddenFields(false) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      <?php include_partial('contactos/form_fieldset', array('contactos' => $contactos, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    <?php endforeach; ?>
	<?php if(!$sf_user->getSeleccionarcontacto()):?>
    <?php include_partial('contactos/form_actions', array('contactos' => $contactos, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
    <?php endif;?>
  <?php if($ajax):?>
  <input type="hidden" value="true" name="ajax" id="ajax"/>
  <?php endif; ?>
  </form>
</div>
<?php if($ajax): ?>
<script>
//$(document).ready(function(){
    var nombre = $( "#contactos_name" ),
    apellidos = $( "#contactos_apellidos" ),
    email = $( "#contactos_email" ),
    allFields = $( [] ).add( nombre ).add(apellidos).add(email);
    
    var tips = $( ".validateTips" ).show();

    function updateTips( t ) {
	tips.text( t ).addClass( "ui-state-highlight" );
        setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
	}, 500 );
    }

    function checkLength( o, n, min, max ) {
            if ( o.val().length > max || o.val().length < min ) {
                    o.addClass( "ui-state-error" );
                    updateTips( "Length of " + n + " must be between " +
                            min + " and " + max + "." );
                    return false;
            } else {
                    return true;
            }
    }

    function checkRegexp( o, regexp, n ) {
            if ( !( regexp.test( o.val() ) ) ) {
                    o.addClass( "ui-state-error" );
                    updateTips( n );
                    return false;
            } else {
                    return true;
            }
    }
//});
</script>
<?php endif; ?>
