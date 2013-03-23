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
<?php //use_javascript('bootstrap.min.js') ?>
<div class="container-fluid">
    <div class="row-fluid">
        <h3>Nuevo Evento</h3>
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
if (!DateAdd || typeof (DateDiff) != "function") {
    var DateAdd = function(interval, number, idate) {
        number = parseInt(number);
        var date;
        if (typeof (idate) == "string") {
            date = idate.split(/\D/);
            eval("var date = new Date(" + date.join(",") + ")");
        }
        if (typeof (idate) == "object") {
            date = new Date(idate.toString());
        }
        switch (interval) {
            case "y": date.setFullYear(date.getFullYear() + number); break;
            case "m": date.setMonth(date.getMonth() + number); break;
            case "d": date.setDate(date.getDate() + number); break;
            case "w": date.setDate(date.getDate() + 7 * number); break;
            case "h": date.setHours(date.getHours() + number); break;
            case "n": date.setMinutes(date.getMinutes() + number); break;
            case "s": date.setSeconds(date.getSeconds() + number); break;
            case "l": date.setMilliseconds(date.getMilliseconds() + number); break;
        }
        return date;
    }
}
function getHM(date)
{
     var hour =date.getHours();
     var minute= date.getMinutes();
     var ret= (hour>9?hour:"0"+hour)+":"+(minute>9?minute:"0"+minute) ;
     return ret;
}
$(document).ready(function() {
    //debugger;
    var arrT = [];
    var tt = "{0}:{1}";
    for (var i = 0; i < 24; i++) {
        arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
    }
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
