<?php 


//use_javascript('/ksWdCalendarPlugin/js/jquery.js');
//use_javascript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/Common.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.form.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.validate.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/datepicker_lang_".$arrayCulture['suffix_file_path'].".js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.datepicker.js");

use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.dropdown.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.colorselect.js");

//use_javascript("/ksWdCalendarPlugin/js/wdCalendar/edit.js");


use_stylesheet('/ksWdCalendarPlugin/css/main.css');
use_stylesheet('/ksWdCalendarPlugin/css/dp.css');
use_stylesheet('/ksWdCalendarPlugin/css/dropdown.css');
use_stylesheet('/ksWdCalendarPlugin/css/colorselect.css');

include_partial('jsVars');



?>
<?php use_stylesheet('bootstrap.min.css') ?>
<?php //use_javascript('bootstrap.js') ?>
<div class="container-fluid">
    <div class="row-fluid">
        <h3>Editar Evento</h3>
    </div>
    <div class="row-fluid">
        <?php include_partial('form_ajax', array('form' => $form)) ?>
    </div>
    <div class="row-fluid">
        <button class="btn btn-primary" id="btn-save">Guardar</button>
        <?php if(!$form->isNew()):?>
        <button class="btn btn-danger" id="btn-save">Eliminar</button>
        <?php endif;?>
        <button class="btn" id="btn-close">Cerrar</button>
        
    </div>
</div>
<script>
$(document).ready(function() {
    $("#btn-save").click(function() {$("#fmEdit").submit(); });
    $("#btn-close").click(function() { CloseModelWindow(); });
    <?php if(!$form->isNew()):?>
    $("#btn-delete").click(function() {
         if (confirm(sf_calendar_delete)) {  
            $.post(sf_calendar_url_delete,
                {id: '<?php echo $form->getObject()->getId()?>'},
                function(data){
                      if (data.IsSuccess) {
                            alert(data.Msg); 
                            CloseModelWindow(null,true);                            
                        }
                        else {
                            alert("Error occurs.\r\n" + data.Msg);
                        }
                }
            ,"json");
        }
    });
    <?php endif;?>
    
   var options = {
        beforeSubmit: function() {
            return true;
        },
        dataType: "json",
        success: function(data) {
            alert(data.Msg);
            if (data.IsSuccess) {
                CloseModelWindow(null,true);  
            }
        }
    };
    $.validator.addMethod("safe", function(value, element) {
        return this.optional(element) || /^[^$\<\>]+$/.test(value);
    }, "$<> not allowed");
    $("#fmEdit").validate({
        submitHandler: function(form) { $("#fmEdit").ajaxSubmit(options); },
        errorElement: "div",
        errorClass: "cusErrorPanel",
        errorPlacement: function(error, element) {
            showerror(error, element);
        }
    });
    function showerror(error, target) {
        var pos = target.position();
        var height = target.height();
        var newpos = { left: pos.left, top: pos.top + height + 2 }
        var form = $("#fmEdit");             
        error.appendTo(form).css(newpos);
    }
});
</script>
