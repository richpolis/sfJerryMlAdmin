<?php use_helper('I18N') ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />

<div id="formContacto" style="margin-left: 15px;">
    <p class="validateTips" style="border: 1px solid transparent; padding: 0.3em;display: none;"><?php echo $mensaje_ok ?></p>
    <?php echo $form->renderGlobalErrors() ?>
    <form action="<?php echo url_for('@enviar_precotizacion').'?precotizacion='.$precotizacion->getId() ?>" method="POST" class="formGen">
        <?php echo $form->renderHiddenFields(false) ?>
            <table border="0">
               <tr>
                    <td>
                       <label for="enviar_cotizacion_message"><?php echo $form['message']->renderLabel() ?><span class="star">*</span>:</label>
                       <br/>
                        <?php echo $form['message']->render(array('class' => 'fields_normal')) ?>
                    </td>
                    <td>
                        <?php if ($form['message']->hasError()): ?>
                            <span class="errorIcon">
                                <span class="errorTip" style="left: 30px; display: none; opacity: 1; ">
                                    <?php echo $form['message']->getError(); ?>
                                </span>
                            </span>  
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if (true): ?>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Enviar"  id="submit" style="padding: 0; width: 250px;position:relative; left: -10px;"/>
                            <input type="button" value="Regresar"  id="return_page" style="padding: 0; width: 250px;position:relative; left: -10px;"/>
                            <!--span class="button_form_contacto">
                                Enviar
                                <span></span>
                            </span-->
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
               <?php endif; ?>
            </table>       
        </form>
</div>
<script>
    $(function($){
        var form = $('#dialog-form form');
        form.submit(handleFormSubmit);
        form.find('input[type=submit]').click(handleFormSubmit);
        var submitFlag = false;

        function handleFormSubmit(){
            if(submitFlag){
                return false;
            }
            overlay.show();
            submitFlag = true;

            $.post(form.attr('action'),form.serialize(),function(data){
                submitFlag = false;
                overlay.hide();
                $('#dialog-form').html(data);
                submitFlag=false;
            });
            return false;
        }
        var overlay = {
            show    : function(){
                $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
            },
            hide    : function(){
                $('#overlay,#preloader').remove();
            }
        }
        function displayOverlay(){
        }
    
        var errorTip=$('.errorTip');        
        $('.errorIcon').each(function(index){
            $(this).hover(function(){
                errorTip.eq(index).stop().fadeIn(function(){
                    errorTip.eq(index).css('opacity',1);
                });
            },function(){
                errorTip.eq(index).stop().fadeOut('slow',function(){
                    errorTip.eq(index).hide().css('opacity',1);
                });
            });
        });               
     
    });
$(document).ready(function(){
  $("#submit").button();  
<?php if (strlen($mensaje_ok) > 0): ?>
    $("p.validateTips").show('fast',function(){
     setTimeout(function(){
         $("p.validateTips").hide('fast');
     },3000);
    });
<?php endif; ?>
<?php if (!isset($enviado)) $enviado = false; ?>
<?php if($enviado):?>
    setTimeout(function(){
         location.href="<?php echo url_for("precotizaciones/show?id=".$precotizacion->getId())?>";
     },4000);
<?php endif; ?>
$("#return_page").click(function(){
        $.regresarAPagina();
    });
    
});
jQuery.regresarAPagina=function(){
  location.href="<?php echo url_for("precotizaciones/show?id=".$precotizacion->getId())?>";  
}
</script>