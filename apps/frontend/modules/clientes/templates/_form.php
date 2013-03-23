<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_helper('I18N')?>

<?php if(!isset($ajax)) $ajax=false; ?>

<div class="sf_admin_form">
  <p class="validateTips" style="border: 1px solid transparent; padding: 0.3em;display: none;">"Nombre empresa" es campo requerido.</p>
  <?php echo form_tag_for($form, '@clientes') ?>
    <?php echo $form->renderHiddenFields(false) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      <?php include_partial('clientes/form_fieldset', array('clientes' => $clientes, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    <?php endforeach; ?>
  
	<?php if(!$sf_user->getSeleccionarCliente()):?>
    <?php include_partial('clientes/form_actions', array('clientes' => $clientes, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
    <?php endif;?>
  
  <?php if($ajax): ?>
    <input type="hidden" value="true" id="ajax" name="ajax"/>
  <?php endif; ?>
  </form>
</div>


<?php if($ajax): ?>
<script>
//    $(document).ready(function(){
            // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
            
            var razon_social = $( "#clientes_razon_social" ),
            allFields = $( [] ).add( razon_social );

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
            
            
//    });
</script>
<?php endif; ?>

