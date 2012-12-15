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
    <?php foreach($cotizaciones as $cotizacion): ?>
        <?php include_partial('detpagos/list',array('cotizacion'=>$cotizacion))?>
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
  
   jQuery.editarDetallesPago=function(DetallesPagoId,CotizacionId){
        overlay.show();
        $('#dialog-form-detalles-pago').attr('detalles-pago-id',DetallesPagoId).attr('cotizacion-id',CotizacionId);
        $.get('/detpagos/edit/',{id:DetallesPagoId},function(data){
            $('#dialog-form-detalles-pago').html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.crearDetallesPago=function(PagoId,CotizacionId){
        overlay.show();
        $('#dialog-form-detalles-pago').attr('pago-id',PagoId).attr('cotizacion-id',CotizacionId);
        $.get('/detpagos/new',{'pago_id':PagoId,'cotizacion_id': CotizacionId},function(data){
            $('#dialog-form-detalles-pago').html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.guardarDetallesPago=function(Id){
        overlay.show();
        var form = $('form#form-detalles-pago-ajax');
        var variables=form.serialize();
        
        $.post(form.attr('action'),variables,function(data){
            $('#cotizacion_'+Id).html(data);
            overlay.hide();
        });
   }
   
</script>
<script>
$(function() {
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    $( "#dialog-form-detalles-pago" ).dialog({
	autoOpen: false,
	height: 350,
	width: 330,
	modal: true,
	buttons: {
            "Guardar": function() {
			overlay.show();
                        var form = $('form#form-detalles-pago-ajax');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                                var CotizacionId=$('#dialog-form-detalles-pago').attr('cotizacion-id');
                                $.get("<?php echo url_for('detpagos/show')?>",{'cotizacion_id': CotizacionId,'sin_div':true},function(data){
                                    $('#cotizacion_'+CotizacionId).html(data);
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
});
</script>
<div id="dialog-form-detalles-pago" title="Detalles Pago">
    
</div>
<script>
$(function() {
    // reportes de pago
    $( "#dialog-form-reportes-pagos" ).dialog({
	autoOpen: false,
	height: 500,
	width: 500,
	modal: true,
	buttons: {
            "Actualizar": function() {
			overlay.show();
                        var form = $('form#form-evento-ajax');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            var data = Ext.util.JSON.decode(data);
                            if(!data.success){
                                $('#dialog-form-evento').html(data);
                                overlay.hide();
                            }else{
                                var DetallesCotizacionId=$('#dialog-form-evento').attr('detalles-cotizacion-id');
                                $.get("<?php echo url_for('detcotizaciones/show')?>",{id: DetallesCotizacionId},function(data){
                                    $('#detalles_cotizacion_'+DetallesCotizacionId).html(data);
                                });
                                overlay.hide();
                                $( this ).dialog( "close" );
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
});
</script>
<div id="dialog-form-reportes-pagos" title="Pagos">
    
</div>
