<?php use_helper('I18N') ?>
<?php use_stylesheet('contacto_white.css'); ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<div id="formContacto" style="margin-left: 0px;">
    <p class="validateTips" style="border: 1px solid transparent; padding: 0.3em;display: none;"><?php echo $mensaje_ok ?></p>
    <?php echo $form->renderGlobalErrors() ?>
    <form action="<?php echo url_for('@enviar_cotizacion_cliente_2') ?>" method="POST" class="formGen">
        <div class="formRow">
            <table border="0">
                <tr>
                    <td>
                        <label for="enviar_cotizacion_cliente"><?php echo $form['cliente']->renderLabel() ?><span class="star">*</span>:</label>
                        <br/>
                        <?php echo $form['cliente']->render(array('class' => 'textField required', "value" => $cotizacion->getClientes())) ?>
                    </td>
                    <td>
                        <?php if ($form['cliente']->hasError()): ?>
                            <span class="errorIcon">
                                <span class="errorTip" style="left: 30px; display: none; opacity: 1; ">
                                    <?php echo $form['cliente']->getError(); ?>
                                </span>
                            </span>  
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="formRow">
            <table border="0">
                <tr>
                    <td>
                        <label for="enviar_cotizacion_contacto"><?php echo $form['contacto']->renderLabel() ?><span class="star">*</span>:</label>
                        <br/>
                        <?php echo $form['contacto']->render(array('class' => 'textField required', "value" => $cotizacion->getContactos())) ?>
                    </td>
                    <td>
                        <?php if ($form['contacto']->hasError()): ?>
                            <span class="errorIcon">
                                <span class="errorTip" style="left: 30px; display: none; opacity: 1; ">
                                    <?php echo $form['contacto']->getError(); ?>
                                </span>
                            </span>  
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="formRow">
            <table border="0">
                <tr>
                    <td>
                        <label for="enviar_cotizacion_email"><?php echo $form['email']->renderLabel() ?><span class="star">*</span>:</label>
                        <br/>
                        <?php echo $form['email']->render(array('class' => 'textField required email', "value" => $cotizacion->getContactos()->getEmail())) ?>
                    </td>
                    <td>
                        <?php if ($form['email']->hasError()): ?>
                            <span class="errorIcon">
                                <span class="errorTip" style="left: 30px; display: none; opacity: 1; ">
                                    <?php echo $form['email']->getError(); ?>
                                </span>
                            </span>  
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="formRow">
            <table border="0">
                <tr>
                    <td>
                        <label for="enviar_cotizacion_subject"><?php echo $form['email']->renderLabel() ?>:</label>
                        <br/>
                        <?php echo $form['subject']->render(array('class' => 'textField required', "value" => "Envio de cotizacion")) ?>
                    </td>
                    <td>
                        <?php if ($form['subject']->hasError()): ?>
                            <span class="errorIcon">
                                <span class="errorTip" style="left: 30px; display: none; opacity: 1; ">
                                    <?php echo $form['subject']->getError(); ?>
                                </span>
                            </span>  
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="formRow">
            <table border="0">
                <tr>
                    <td>
                        <label for="enviar_cotizacion_message"><?php echo $form['message']->renderLabel() ?><span class="star">*</span>:</label>
                        <br/>
                        <?php echo $form['message']->render(array('class' => 'textArea required', 'value' => $cotizacion->getRenderMensaje())) ?>
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
            </table>   
        </div>
        <?php echo $form['cotizacion_id']->render(array('value' => $cotizacion->getId())) ?>
        <?php echo $form->renderHiddenFields(false) ?>
        <?php if (true): ?>
            <div class="formRow">
                <table boder="0">
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="submit" value="<?php echo __('Enviar'); ?>" src="/images/jerryml/contacto/btn_enviar.png" id="submit" style="padding: 0; width: 400px;position:relative; left: -10px;"/>
                            <!--span class="button_form_contacto">
                                Enviar
                                <span></span>
                            </span-->
                        </td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>    
    </form>
</div>
<script>
    $(function($){
        var form = $('#dialog-form form');
        form.zinePretifyForm();
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

    (function($){

        $.fn.zinePretifyForm = function(){
            return this.each(function(){
                var form = $(this);
                form.find('input[type=button],input[type=submit]').each(function(){
                    var originalButton = $(this),
                    button = $('<span>',{
                        className   : 'button',
                        html    : originalButton.val()+'<span></span>'
                    });
                    button.insertAfter(originalButton.hide());
                    button.click(function(){
                        originalButton.click();
                    });

                });

                form.find('input[type=checkbox]').each(function(){
                    var originalCheckBox = $(this),
                    checkBox = $('<span>',{
                        className   : 'checkBox '+(this.checked?'checked':'')
                    });
                    checkBox.insertAfter(originalCheckBox.hide());

                    checkBox.click(function(){
                        checkBox.toggleClass('checked');
                        originalCheckBox.attr('checked',checkBox.hasClass('checked'));
                    });

                });

                form.find('input[type=radio]').each(function(){

                    var originalRadio = $(this),
                    radio = $('<span>',{
                        className  : 'radio '+(this.checked?'checked':'')
                    });

                    radio.insertAfter(originalRadio.hide());

                    radio.click(function(){
                        $('input[type=radio][name='+originalRadio.attr('name')+']').each(function(){
                            $(this).next().removeClass('checked');
                        });

                        radio.addClass('checked');
                        originalRadio.attr('checked',true);
                    });

                });

                form.find('select').each(function(i){

                    var select = $(this);

                    var selectBoxContainer = $('<span>',{
                        width       : select.outerWidth(),
                        className       : 'selectContainer',
                        html        : '<div class="selectBox"></div><span></span>',
                        css         : {zIndex : 1000-i}
                    });

                    var dropDown = $('<ul>',{className:'dropDown'});
                    var selectBox = selectBoxContainer.find('.selectBox');

                    select.find('option').each(function(i){
                        var option = $(this);

                        if(i==select.attr('selectedIndex')){
                            selectBox.html(option.text());
                        }

                        var li = $('<li>',{
                            html:   option.html()
                        });

                        li.click(function(){

                            selectBox.html(option.text());
                            dropDown.trigger('hide');

                            select.val(option.val());
                            return false;
                        });

                        dropDown.append(li);
                    });

                    selectBoxContainer.append(dropDown.hide());
                    select.hide().after(selectBoxContainer);

                    dropDown.bind('show',function(){

                        if(dropDown.is(':animated')){
                            return false;
                        }

                        selectBox.addClass('expanded');
                        dropDown.slideDown('fast');

                    }).bind('hide',function(){

                        if(dropDown.is(':animated')){
                            return false;
                        }

                        selectBox.removeClass('expanded');
                        dropDown.slideUp('fast');

                    }).bind('toggle',function(){
                        if(selectBox.hasClass('expanded')){
                            dropDown.trigger('hide');
                        }
                        else dropDown.trigger('show');
                    });

                    selectBoxContainer.click(function(){
                        dropDown.trigger('toggle');
                        return false;
                    });

                    $(document).click(function(){
                        dropDown.trigger('hide');
                    });
                });

            });
        }

    })(jQuery);
    $(document).ready(function(){
<?php if (strlen($mensaje_ok) > 0): ?>
            $("p.validateTips").show('fast',function(){
                setTimeout(function(){
                    $("p.validateTips").hide('fast');
                },3000);
            });
<?php endif; ?>
    });
</script>
