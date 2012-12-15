<?php use_helper('Escaping')?>
<?php slot('adicional-css')?>
<?php
    $app_name = $sf_context->getConfiguration()->getApplication();
    if(strcmp($sf_context->getConfiguration()->getEnvironment(),'prod') != 0)
    {$app_name .= '_'.$sf_context->getConfiguration()->getEnvironment();}
    if($app_name=="frontend"){
        $app_name="index";
    }
?>
<style>
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<?php end_slot()?>
<?php $contRegistros=0;?>
<div id="talentos" class="sf_admin_form_row">
    <h2>Detalles de Precotizacion</h2>
    <?php foreach($detalles_precotizaciones as $detalle): ?>
        <?php $contRegistros++;?>
        <?php include_partial('detprecotizaciones/show',array('detalles_precotizacion'=>$detalle))?>
    <?php endforeach;?>  
</div>
<script>
  var overlay = {
     show    : function(){
            $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
     },
     hide    : function(){
            $('#overlay,#preloader').remove();
     }
  }
  
   jQuery.editarTalento=function(Id){
        overlay.show();
        $.get('<?php echo url_for('detprecotizaciones/edit') ?>',{'id':Id},function(data){
            $("div#detalles_precotizacion_"+Id).html(data);
            $(".buttonEditar").fadeOut();
            overlay.hide();
        });
   }
   jQuery.guardarTalento=function(Id){
        overlay.show();
        var form = $('form#form_detalles_precotizacion_'+Id);
        var variables=form.serialize();
        
        $.post(form.attr('action'),variables,function(data){
           $('#detalles_precotizacion_'+Id).html(data);
           $(".buttonEditar").fadeIn();
           overlay.hide();
        });
   }
   jQuery.borrarTalento=function(Id){
        overlay.show();
        var form = $('form#form_detalles_precotizacion_'+Id);
        var variables=form.serialize();
        
        $.post('<?php echo url_for1('detprecotizaciones/delete')?>',variables,function(data){
            if(data=="ok"){
                $('#detalles_precotizacion_'+Id).hide();
                $(".buttonEditar").fadeIn();
                overlay.hide();
            }else{
                 $( "#dialog-message p.mensaje" ).html(data);
                 $( "#dialog-message" ).dialog("open");
                 $(".buttonEditar").fadeIn();
                overlay.hide();
            }
        });
   }
</script>
<script>
    $(function() {
        $( "#dialog-message" ).dialog({
            autoOpen: false,
            modal: true,
            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        <?php if($contRegistros>1){?>
            $("#talentos" ).sortable({
                    handle: '.manejador',
                    update: function(){
                    overlay.show();
                     var order = $('#talentos').sortable('serialize');
                     //alert(order);
                     $("#talentos-precotizaciones").load('/<?php echo $app_name ?>.php/precotizaciones/ajaxRegistroOrder?id=<?php echo $detalles_precotizaciones[0]->getPrecotizacionId();?>&'+order,
                     {},
                     function(data){
                         //alert(data)
                          overlay.hide();
                     });
                     }
            });
            $( "#talentos" ).disableSelection();
        <?php } ?>
    });
</script>
<div id="dialog-message" title="Borrar registro">
    <p>
        <span class="ui-icon ui-icon-cancel" style="float: left; margin: 0 7px 50px 0;"></span>
        Permiso denegado
    </p>
    <p class="mensaje">
        No es posible eliminar el registro
    </p>
</div>