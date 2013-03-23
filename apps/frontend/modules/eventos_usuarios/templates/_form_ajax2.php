<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>    
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">    
    <title>Calendar Details</title>    
    <link href="/ksWdCalendarPlugin/css/main.css" rel="stylesheet" type="text/css" />       
    <link href="/ksWdCalendarPlugin/css/dp.css" rel="stylesheet" />    
    <link href="/ksWdCalendarPlugin/css/dropdown.css" rel="stylesheet" />    
    <link href="/ksWdCalendarPlugin/css/colorselect.css" rel="stylesheet" />   
    <?php include_partial('jsVars'); ?> 
    <script src="/ksWdCalendarPlugin/js/jquery.min.js" type="text/javascript"></script>    
    <script src="/ksWdCalendarPlugin/js/wdCalendar/Common.js" type="text/javascript"></script>        
    <script src="/ksWdCalendarPlugin/js/wdCalendar/jquery.form.js" type="text/javascript"></script>     
    <script src="/ksWdCalendarPlugin/js/wdCalendar/jquery.validate.js" type="text/javascript"></script>     
    <script src="/ksWdCalendarPlugin/js/wdCalendar/datepicker_lang_US.js" type="text/javascript"></script>        
    <script src="/ksWdCalendarPlugin/js/wdCalendar/jquery.datepicker.js" type="text/javascript"></script>     
    <script src="/ksWdCalendarPlugin/js/wdCalendar/jquery.dropdown.js" type="text/javascript"></script>     
    <script src="/ksWdCalendarPlugin/js/wdCalendar/jquery.colorselect.js" type="text/javascript"></script>    
    
    <link rel="stylesheet" type="text/css" media="screen" href="/css/jquery-ui-1.8.9.custom.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/jquery-ui-timepicker-addon.css" />
    <script type="text/javascript" src="/js/jquery-ui-1.8.13.custom.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
    
    
    <script type="text/javascript">
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
            var DATA_FEED_URL = "php/datafeed.php";
            var arrT = [];
            var tt = "{0}:{1}";
            for (var i = 0; i < 24; i++) {
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
            }
            $("#timezone").val(new Date().getTimezoneOffset()/60 * -1);
            $("#stparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            $("#etparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            var check = $("#IsAllDayEvent").click(function(e) {
                if (this.checked) {
                    $("#stparttime").val("00:00").hide();
                    $("#etparttime").val("00:00").hide();
                }
                else {
                    var d = new Date();
                    var p = 60 - d.getMinutes();
                    if (p > 30) p = p - 30;
                    d = DateAdd("n", p, d);
                    $("#stparttime").val(getHM(d)).show();
                    $("#etparttime").val(getHM(DateAdd("h", 1, d))).show();
                }
            });
            if (check[0].checked) {
                $("#stparttime").val("00:00").hide();
                $("#etparttime").val("00:00").hide();
            }
            $("#Savebtn").click(function() { $("#fmEdit").submit(); });
            $("#Closebtn").click(function() { CloseModelWindow(); });
            $("#Deletebtn").click(function() {
                 if (confirm("Are you sure to remove this event")) {  
                    var param = [{ "name": "calendarId", value: 8}];                
                    $.post(DATA_FEED_URL + "?method=remove",
                        param,
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
            
           $("#stpartdate,#etpartdate").datepicker({ picker: "<button class='calpick'></button>"});    
            var cv =$("#colorvalue").val() ;
            if(cv=="")
            {
                cv="-1";
            }
            $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
            //to define parameters of ajaxform
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
            $.validator.addMethod("date", function(value, element) {                             
                var arrs = value.split(i18n.datepicker.dateformat.separator);
                var year = arrs[i18n.datepicker.dateformat.year_index];
                var month = arrs[i18n.datepicker.dateformat.month_index];
                var day = arrs[i18n.datepicker.dateformat.day_index];
                var standvalue = [year,month,day].join("-");
                return this.optional(element) || /^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3-9]|1[0-2])[\/\-\.](?:29|30))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3,5,7,8]|1[02])[\/\-\.]31)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:16|[2468][048]|[3579][26])00[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1-9]|1[0-2])[\/\-\.](?:0?[1-9]|1\d|2[0-8]))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?:\d{1,3})?)?$/.test(standvalue);
            }, "Invalid date format");
            $.validator.addMethod("time", function(value, element) {
                return this.optional(element) || /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value);
            }, "Invalid time format");
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
    <style type="text/css">     
    .calpick     {        
        width:16px;   
        height:16px;     
        border:none;        
        cursor:pointer;        
        background:url("sample-css/cal.gif") no-repeat center 2px;        
        margin-left:-22px;    
    }      

    </style>
  </head>
  <body>  
    <div>      
      <div class="toolBotton">           
        <a id="Savebtn" class="imgbtn" href="javascript:void(0);">                
          <span class="Save"  title="Save the calendar">Save(<u>S</u>)
          </span>          
        </a>                           
                    
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);">                
          <span class="Close" title="Close the window" >Close
          </span></a>            
        </a>        
      </div>                  
      <div style="clear: both">         
      </div>        
      <div class="infocontainer">            
        <form action="<?php echo url_for('eventos_usuarios/'.($form->getObject()->isNew() ? 'createAjax' : 'updateAjax').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" class="fform" id="fmEdit" method="post">                 
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
      </div>         
    </div>
  </body>
</html>

