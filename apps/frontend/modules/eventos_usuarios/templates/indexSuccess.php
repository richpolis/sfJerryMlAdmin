<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use_helper("I18N");

//use_stylesheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css');
use_stylesheet('/ksWdCalendarPlugin/css/main-wdcalendar.css');
use_stylesheet('/ksWdCalendarPlugin/css/dp.css');
use_stylesheet('/ksWdCalendarPlugin/css/dailog.css');
use_stylesheet('/ksWdCalendarPlugin/css/calendar.css');
//use_stylesheet('dropdown.css');
use_stylesheet('/ksWdCalendarPlugin/css/alert.css');
//use_stylesheet('colorselect.css');

/*
  use_javascript('http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js');
  use_javascript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js");
 */

//use_javascript('/ksWdCalendarPlugin/js/jquery.min.js');
//use_javascript('jquery-1.4.1.min.js');
//use_javascript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/Common.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/datepicker_lang_" . $arrayCulture['suffix_file_path'] . ".js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.datepicker.js");

use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.alert.js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.ifrmdailog.js");

use_javascript("/ksWdCalendarPlugin/js/wdCalendar/wdCalendar_lang_" . $arrayCulture['suffix_file_path'] . ".js");
use_javascript("/ksWdCalendarPlugin/js/wdCalendar/jquery.calendar.js");
//use_javascript("/ksWdCalendarPlugin/js/wdCalendar/index.js");
include_partial('jsVars');

?>
<div>

    <div id="calhead" style="padding-left:1px;padding-right:1px;">
        <div class="cHead">
            <div class="ftitle">
                <?php if($sf_user->getCalendarUsuarioId()>0): ?>
                <?php echo $sf_user->getCalendarUsuarioName()?>
                <?php else:?>
                Todos los usuarios
                <?php endif; ?>
            </div>
            <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Loading data...</div>
            <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Sorry, could not load your data, please try again later</div>
        </div>

        <div id="caltoolbar" class="ctoolbar">
            <div id="BtnBackTalentos" class="fbutton">
                <div>
                    <span title='Regresar a Talentos' class="addcal">
                        Regresar
                    </span>
                </div>
            </div>
            <div id="faddbtn" class="fbutton">
                <div>
                    <span title='Click para crear nuevo evento' class="addcal">
                        Nuevo Evento
                    </span>
                </div>
            </div>
            <div class="btnseparator"></div>
            <div id="showtodaybtn" class="fbutton">
                <div>
                    <span title='Click to back to today ' class="showtoday">
                        Today
                    </span>
                </div>
            </div>
            <div class="btnseparator"></div>

            <div id="showdaybtn" class="fbutton">
                <div><span title='Day' class="showdayview">Day</span></div>
            </div>
            <div  id="showweekbtn" class="fbutton fcurrent">
                <div><span title='Week' class="showweekview">Week</span></div>
            </div>
            <div  id="showmonthbtn" class="fbutton">
                <div><span title='Month' class="showmonthview">Month</span></div>

            </div>
            <div class="btnseparator"></div>
            <div  id="showreflashbtn" class="fbutton">
                <div><span title='Refresh view' class="showdayflash">Refresh</span></div>
            </div>
            <div class="btnseparator"></div>
            <div id="sfprevbtn" title="Prev"  class="fbutton">
                <span class="fprev"></span>

            </div>
            <div id="sfnextbtn" title="Next" class="fbutton">
                <span class="fnext"></span>
            </div>
            <div class="fshowdatep fbutton">
                <div>
                    <input type="hidden" name="txtshow" id="hdtxtshow" />
                    <span id="txtdatetimeshow">Loading</span>

                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
    <div style="padding:1px;">

        <div class="t1 chromeColor">
            &nbsp;</div>
        <div class="t2 chromeColor">
            &nbsp;</div>
        <div id="dvCalMain" class="calmain printborder">
            <div id="gridcontainer" style="overflow-y: visible;">
            </div>
        </div>
        <div class="t2 chromeColor">

            &nbsp;</div>
        <div class="t1 chromeColor">
            &nbsp;
        </div>
    </div>

</div>

<?php if($sf_user->getCalendarUsuarioId()>0): ?>
 <?php $theme=$sf_user->getCalendarUsuarioColor();?>
<?php else:?>
 <?php $theme=3;?>
<?php endif; ?>

<script>
    $(document).ready(function(){
        $("#BtnBackTalentos").click(function(){
           <?php $pagina=(strlen($sf_user->getRegresarA())>0?$sf_user->getRegresarA():'@sf_guard_user')?> 
           location.href='<?php echo url_for($pagina)?>'; 
        });
    });
</script>
<script>
$(document).ready(function() {     
  var view="week";          
  
  var overlay = {
        show    : function(){
                $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
        },
        hide    : function(){
                $('#overlay,#preloader').remove();
        }
  }
  
  var op = {
    view: view,
    theme:<?php echo $theme?>,
    showday: new Date('<?php echo $showdate?>'),
    EditCmdhandler:Edit,
    DeleteCmdhandler:Delete,
    ViewCmdhandler:View,
    onWeekOrMonthToDay:wtd,
    onBeforeRequestData: cal_beforerequest,
    onAfterRequestData: cal_afterrequest,
    onRequestDataError: cal_onerror,
    autoload:true,
    url: sf_calendar_url_list,
    quickAddUrl: sf_calendar_url_add,
    quickUpdateUrl: sf_calendar_url_update,
    quickDeleteUrl: sf_calendar_url_delete
  };
  
  var $dv = $("#calhead");
  var _MH = document.documentElement.clientHeight;
  var dvH = $dv.height() + 2;
  op.height = _MH - dvH;
  op.eventItems =[];
  
  var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
  if (p && p.datestrshow) {
    $("#txtdatetimeshow").text(p.datestrshow);
  }
  
  $("#caltoolbar").noSelect();
  
  $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
    onReturn:function(r){
      var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
      if (p && p.datestrshow) {
        $("#txtdatetimeshow").text(p.datestrshow);
      }
    }
  });
  
  function cal_beforerequest(type)
  {
    var t=sf_calendar_loading;
    
    switch (type) {
      case 1:
        t=sf_calendar_loading;
        break;
      case 2:
      case 3:
      case 4:
        t=sf_calendar_request;
        break;
    }
    
    $("#errorpannel").hide();
    $("#loadingpannel").html(t).show();
  }
  
  function cal_afterrequest(type)
  {
    switch (type) {
      case 1:
        $("#loadingpannel").hide();
        break;
      case 2:
      case 3:
      case 4:
        $("#loadingpannel").html(sf_calendar_success);
        window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
        break;
    }
  }
  
  function cal_onerror(type,data)
  {
    $("#errorpannel").show();
  }
  
  function Edit(data)
  {
    //var eurl=sf_calendar_url_edit + "?id={0}&start={2}&end={3}&isallday={4}&title={1}";
    var eurl= sf_calendar_url_edit + "?id={0}&start={2}&end={3}&isallday={4}&title={1}";
    if (data) {
      var url = StrFormat(eurl,data);
      //alert(url);
      OpenModelWindow(url,{ width: 500, height: 600, caption:sf_calendar_manage,onclose:function(){
        $("#gridcontainer").reload();
      }});
      //editarEvento(url);
    }
  }
  
  function View(data)
  {
    var eurl= sf_calendar_url_show + "?id={0}&start={2}&end={3}&isallday={4}&title={1}";
    if (data) {
      var url = StrFormat(eurl,data);
      OpenModelWindow(url,{ width: 600, height: 400, caption:sf_calendar_manage,onclose:function(){
        $("#gridcontainer").reload();
      }});
    }
  }
  
  function View2(data)
  {
    var str = "";
    $.each(data, function(i, item){
      str += "[" + i + "]: " + item + "\n";
    });
    
    alert(str);
  }
  
  function Delete(data,callback)
  {
    $.alerts.okButton="Ok";
    $.alerts.cancelButton="Cancel";
    hiConfirm(sf_calendar_delete, 'Confirm',function(r){ r && callback(0);});
  }
  
  function wtd(p)
  {
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
    
    $("#caltoolbar div.fcurrent").each(function() {
      $(this).removeClass("fcurrent");
    })
    
    $("#showdaybtn").addClass("fcurrent");
  }
  
  //to show day view
  $("#showdaybtn").click(function(e) {
    $("#caltoolbar div.fcurrent").each(function() {
      $(this).removeClass("fcurrent");
    })
    
    $(this).addClass("fcurrent");
    var p = $("#gridcontainer").swtichView("day").BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  });
  
  //to show week view
  $("#showweekbtn").click(function(e) {
    $("#caltoolbar div.fcurrent").each(function() {
      $(this).removeClass("fcurrent");
    })
    
    $(this).addClass("fcurrent");
    var p = $("#gridcontainer").swtichView("week").BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  });
  
  //to show month view
  $("#showmonthbtn").click(function(e) {
    $("#caltoolbar div.fcurrent").each(function() {
      $(this).removeClass("fcurrent");
    })
    
    $(this).addClass("fcurrent");
    var p = $("#gridcontainer").swtichView("month").BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  });
  
  $("#showreflashbtn").click(function(e){
    $("#gridcontainer").reload();
  });
  
  //Add a new event
  $("#faddbtn").click(function(e) {
    var url=sf_calendar_url_edit;
    OpenModelWindow(url,{ width: 400, height: 600, caption: sf_calendar_create});
  });
  
  //Add a new event
  $("#faddbtn2").click(function(e) {
    //var url=sf_calendar_url_add;
    //OpenModelWindow(url,{ width: 500, height: 400, caption: sf_calendar_create});
    <?php if($sf_user->getCalendarUsuarioId()>0): ?>
       var usuario='<?php echo $sf_user->getCalendarUsuarioId();?>';
    <?php else:?>
       var usuario='0';
    <?php endif; ?>
      crearEvento(usuario);  
        
  });
  //go to today
  $("#showtodaybtn").click(function(e) {
    var p = $("#gridcontainer").gotoDate().BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  });
  
  //previous date range
  $("#sfprevbtn").click(function(e) {
    var p = $("#gridcontainer").previousRange().BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  });
  
  //next date range
  $("#sfnextbtn").click(function(e) {
    var p = $("#gridcontainer").nextRange().BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  });
  
  $( "#dialog:ui-dialog" ).dialog( "destroy" );
  $( "#dialog-form-evento" ).dialog({
	autoOpen: false,
	height: 500,
	width: 500,
	modal: true,
	buttons: {
            "Guardar": function() {
			overlay.show();
                        var form = $('form#form-eventos-usuarios-ajax');
                        var variables=form.serialize();
                        jQuery.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                                $("#gridcontainer").reload();
                                overlay.hide();
                               $('#dialog-form-evento').dialog( "close" );
                                
                            }else{
                                $('#dialog-form-evento').html(data);
                                overlay.hide();
                            }
                            
                            
                        });
            },
             Cancel: function() {
                $("#gridcontainer").reload(); 
                $( this ).dialog( "close" );
            }
	},
	close: function() {
            $("#gridcontainer").reload();
           
        }
     });
  
    


    function crearEvento(UsuarioId){
        overlay.show();
        $('#dialog-form-evento').attr('usuario-id',UsuarioId);
        $.get('<?php echo url_for('eventos_usuarios/newAjax')?>',{user_id: UsuarioId},function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
    }

    function editarEvento(variables){
        overlay.show();
        //$('#dialog-form-evento').attr('evento-id',EventoId);
        $.get('<?php echo url_for('eventos_usuarios/editAjax')?>'+variables,{},function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        }).error(function() { alert("error"); });
    }
  
});
  
</script>
<div id="dialog-form-evento" title="Evento" evento-id="">
    
</div>