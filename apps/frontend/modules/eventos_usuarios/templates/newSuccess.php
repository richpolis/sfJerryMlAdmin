<?/*
use_javascript('/ksWdCalendarPlugin/js/jquery.min.js');
//use_javascript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/Common.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.form.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.validate.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/datepicker_lang_".$arrayCulture['suffix_file_path'].".js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.datepicker.js");

use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.dropdown.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.colorselect.js");

use_javascript("/ksWdCalendarPlugin/js/wdCalendar/edit.js");
use_stylesheet('jquery-ui-1.8.9.custom.min.js');
use_javascript('jquery-ui-timepicker-addon.js');




use_stylesheet('/ksWdCalendarPlugin/css/main.css');
use_stylesheet('/ksWdCalendarPlugin/css/dp.css');
use_stylesheet('/ksWdCalendarPlugin/css/dropdown.css');
use_stylesheet('/ksWdCalendarPlugin/css/colorselect.css');
use_stylesheet('jquery-ui-1.8.9.custom.css');
use_stylesheet('jquery-ui-timepicker-addon.css');


include_partial('jsVars');
include_javascripts();
include_stylesheets() ;
*/
?>
<!--h1>Nuevo Evento</h1-->

<?php include_partial('form_ajax2', array('form' => $form)) ?>
