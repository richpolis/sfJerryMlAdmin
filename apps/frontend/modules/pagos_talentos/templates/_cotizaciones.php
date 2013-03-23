<?php use_helper('Escaping')?>
<?php use_stylesheet('contacto.css')?>
<?php slot('adicional-css')?>
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
<div id="talentos" class="sf_admin_form_row">
    <h2>Listado</h2>
    <?php foreach($detalles_cotizacion as $detalle): ?>
        <?php include_partial('detpagostalentos/show',array('detalle_cotizacion'=>$detalle))?>
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
  
   jQuery.editarDetallesPagoTalento=function(DetallesPagoTalentoId,DetallesCotizacionId){
       

        overlay.show();
        $('#dialog-form-detalles-pago').attr('detalles-pago-talento-id',DetallesPagoTalentoId).attr('detalles-cotizacion-id',DetallesCotizacionId);
        $.get('/detpagostalentos/edit?id='+DetallesPagoTalentoId,function(data){
          
            $('#dialog-form-detalles-pago').html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.crearDetallesPagoTalento=function(PagosTalentosId,CotizacionId){
        overlay.show();
        $('#dialog-form-detalles-pago').attr('pago-talento-id',PagosTalentosId).attr('cotizacion-id',CotizacionId);
        $.get('/detpagos/new',{'pagos_talentos_id':PagoTalentosId,'cotizacion_id': CotizacionId},function(data){
            $('#dialog-form-detalles-pago').html(data).dialog('open');
            overlay.hide();
        });
   }
   
   jQuery.confirmarAprobar=function(Url){
       $("#dialog-confirmar-pago").attr('url',Url);
       $("#dialog-confirmar-pago").dialog("open");
   }
</script>
<script>
$(function() {
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    $( "#dialog-form-detalles-pago" ).dialog({
	autoOpen: false,
	height: 400,
	width: 400,
	modal: true,
	buttons: {
            "Guardar": function() {
			overlay.show();
                        var form = $('form#form-detalles-pago-ajax');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                                var DetallesCotizacionId=$('#dialog-form-detalles-pago').attr('detalles-cotizacion-id');
                                $.get("<?php echo url_for('detpagostalentos/show')?>",{'id': DetallesCotizacionId,'sin_div':true},function(data){
                                    $('#detalles_cotizacion_'+DetallesCotizacionId).html(data);
                                });
                                overlay.hide();
                                $("#dialog-form-detalles-pago").dialog("close");
                            }else{
                                $('#dialog-form-detalles-pago').html(data);
                                overlay.hide();
                            }
                       });
            },
	    Cancel: function() {
                $( this ).dialog( "close" );
            }
	},
	close: function() {
            //allFields.val( "" ).removeClass( "ui-state-error" );
        }
     });
     
     $( "#dialog-confirmar-pago" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            autoOpen: false,
            buttons: {
                "Confirmar pago": function() {
                    $( "#dialog-confirmar-pago" ).dialog( "close" );
                    location.href=$( "#dialog-confirmar-pago" ).attr("url");
                    
                },
                Cancelar: function() {
                    $( "#dialog-confirmar-pago" ).dialog( "close" );
                }
            }
        });
});
</script>
<div id="dialog-form-detalles-pago" title="Detalles Pago Talento">
    
</div>
<div id="dialog-confirmar-pago" title="Confirmar aprobar pago">
    <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        ¿Confirma aprobar pago?</p>
</div>