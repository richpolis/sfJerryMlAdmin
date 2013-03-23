<?php use_helper('Escaping')?>
<?php
    $app_name = $sf_context->getConfiguration()->getApplication();
    if(strcmp($sf_context->getConfiguration()->getEnvironment(),'prod') != 0)
    {$app_name .= '_'.$sf_context->getConfiguration()->getEnvironment();}
    if($app_name=="frontend"){
        $app_name="index";
    }
?>
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
<?php 
$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
$conn->beginTransaction();
$contRegistros=0;$calcularCotizacion=false;$precioTotal=0;
?>
<div id="talentos" class="sf_admin_form_row">
    <h2>Detalles de cotizacion</h2>
    <?php foreach($detalles_cotizaciones as $detalle): ?>
        <?php 
            $contRegistros++;
            $precio=0;
            $margenComisionista=0;
            foreach($detalle->getDetallesCotizacionConceptos() as $dcc){
                $precio+=$dcc->getPrecio();
                $precioTotal+=$dcc->getPrecio();
            }
            foreach($detalle->getDetallesCotizacionComisionistas() as $dcco){
                $margenComisionista+=$dcco->getMargen();
            }
            if($precio!=$detalle->getPrecio()){
                $detalle->calcularConceptos();
                $calcularCotizacion=true;
            }elseif($margenComisionista!=$detalle->getMargenComisionistas()){
                $detalle->calcularComisionistas();
                $calcularCotizacion=true;
            }
        ?>
        
        <?php include_partial('detcotizaciones/show',array('detalles_cotizacion'=>$detalle))?>
    <?php endforeach;?>
    <?php $conn->commit();?>  
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
        $.get('<?php echo url_for('detcotizaciones/edit') ?>',{'id':Id},function(data){
            $("div#detalles_cotizacion_"+Id).html(data);
            $(".buttonEditar").fadeOut();
            overlay.hide();
        });
   }
   jQuery.guardarTalento=function(Id){
        overlay.show();
        var form = $('form#form_detalles_cotizacion_'+Id);
        var variables=form.serialize();
        
        $.post(form.attr('action'),variables,function(data){
            $('#detalles_cotizacion_'+Id).html(data);
            $(".buttonEditar").fadeIn();
            overlay.hide();
            
        });
   }
   
   jQuery.eliminarRegistro=function(Id){
        overlay.show();
        var form = $('form#form_detalles_cotizacion_'+Id);
        var variables=form.serialize();
        
        $.post('<?php echo url_for1('detcotizaciones/delete')?>',variables,function(data){
            //alert(data);
            if(data=="ok"){
                $('#detalles_cotizacion_'+Id).hide();
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
   
   jQuery.borrarTalento=function(Id){
       $( "#dialog-confirm" ).attr("eliminar-registro-id",Id);
       $( "#dialog-confirm" ).dialog("open");
       
   }
   
   jQuery.crearEvento=function(DetallesCotizacionId){
        overlay.show();
        $('#dialog-form-evento').attr('evento-id','0').attr('detalles-cotizacion-id',DetallesCotizacionId);
        
        $.get('<?php echo url_for('@ks_wc_event_new')?>',{'detalles_cotizacion_id':DetallesCotizacionId},function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.editarEvento=function(EventoId,DetallesCotizacionId){
        overlay.show();
        $('#dialog-form-evento').attr('evento-id',EventoId).attr('detalles-cotizacion-id',DetallesCotizacionId);
        $('#dialog-form-evento').attr('url','<?php echo url_for("mostrar_evento_talento")?>?id='+EventoId)
        $.get('/eventos/'+EventoId+'/edit',function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
   }
   
   jQuery.eliminarEvento=function(EventoId){
        formu = document.createElement("form");// creamos el formulario
        formu.action = "<?php echo url_for("eventos/delete")?>";
        formu.method = "post";
        overlay.show();        
        $.post(formu.action,{id:EventoId},function(data){
            if(data=="delete"){
                $("#eventos-"+EventoId).fadeOut("fast");
                overlay.hide();
            }else{
                 $( "#dialog-message p.mensaje" ).html(data);
                 $( "#dialog-message" ).dialog("open");
                overlay.hide();
            }
        });
   }
   
   jQuery.actualizarImportesCotizacion=function(){
       <?php if(!$detalles_cotizaciones==null):?>
       $("#cotizacion-importes").html("Actualizando...");        
       $("#cotizacion-importes").load('<?php echo url_for('@actualizar_importes_cotizacion')?>?id=<?php echo $detalles_cotizaciones[0]->getCotizaciones()->getId()?>',function(data){
         
       });
       <?php endif; ?>
   }
   
   jQuery.crearConcepto=function(DetallesCotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Concepto').attr('registro-id',0).attr('detalles-cotizacion-id',DetallesCotizacionId);
        $.get('<?php echo url_for('detconceptos/new')?>',{detalle_cotizacion: DetallesCotizacionId},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.editarConcepto=function(ConceptoId,DetallesCotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Concepto').attr('registro-id',ConceptoId).attr('detalles-cotizacion-id',DetallesCotizacionId);
        var Url="<?php echo url_for("detconceptos/edit")?>";
        $.get(Url,{id: ConceptoId},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.eliminarConcepto=function(ConceptoId,DetallesCotizacionId){
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Concepto').attr('registro-id',ConceptoId).attr('detalles-cotizacion-id',DetallesCotizacionId);
        formu = document.createElement("form");// creamos el formulario
        formu.action = "<?php echo url_for("detconceptos/delete")?>";
        formu.method = "post";
        overlay.show();        
        $.post(formu.action,{id:ConceptoId},function(data){
            //alert(DetallesCotizacionId);
            if(data=="ok"){
                $.editarTalento(DetallesCotizacionId);
                overlay.hide();
            }else{
                 $( "#dialog-message p.mensaje" ).html(data);
                 $( "#dialog-message" ).dialog("open");
                overlay.hide();
            }
        });
   }
   
   jQuery.crearComisionista=function(DetallesCotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Comisionista').attr('registro-id',0).attr('detalles-cotizacion-id',DetallesCotizacionId);
        $.get('<?php echo url_for('detcomisionistas/new')?>',{detalle_cotizacion: DetallesCotizacionId},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
   }
   
   jQuery.editarComisionista=function(ComisionistaId,DetallesCotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Comisionista').attr('registro-id',ComisionistaId).attr('detalles-cotizacion-id',DetallesCotizacionId);
        var Url="<?php echo url_for("detcomisionistas/edit")?>";
        $.get(Url,{id: ComisionistaId},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
   }
   jQuery.eliminarComisionista=function(ComisionistaId,DetallesCotizacionId){
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Comisionista').attr('registro-id',ComisionistaId).attr('detalles-cotizacion-id',DetallesCotizacionId);
        formu = document.createElement("form");// creamos el formulario
        formu.action = "<?php echo url_for("detcomisionistas/delete")?>";
        formu.method = "post";
        overlay.show();        
        $.post(formu.action,{id:ComisionistaId},function(data){
            //alert(DetallesCotizacionId);
            if(data=="ok"){
                $.editarTalento(DetallesCotizacionId);
                overlay.hide();
            }else{
                 $( "#dialog-message p.mensaje" ).html(data);
                 $( "#dialog-message" ).dialog("open");
                overlay.hide();
            }
        });
   }
</script>
<script>
$(function() {
    
<?php if ($calcularCotizacion || $precioTotal != $cotizaciones->getSubtotal()): ?>
    $.get("<?php echo url_for('cotizaciones/calcularImportes') ?>",{id: <?php echo $cotizaciones->getId();?>},function(data){
        $('#totales-cotizacion').html(data);
    });
<?php endif; ?>
    
    
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    $( "#dialog-form-evento" ).dialog({
	autoOpen: false,
	height: 500,
	width: 500,
	modal: true,
	buttons: {
            "Guardar": function() {
			overlay.show();
                        var form = $('form#form-eventos-ajax');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                               var EventoId=$('#dialog-form-evento').attr('evento-id');
                                $.get("<?php echo url_for('eventos/show')?>",{id: EventoId},function(data){
                                    $('#eventos-'+EventoId).html(data);
                                });
                                overlay.hide();
                               $('#dialog-form-evento').dialog( "close" );
                                
                            }else{
                                $('#dialog-form-evento').html(data);
                                overlay.hide();
                            }
                            
                            
                        });
            },
            Calendario: function() {
                    var urlEvento=$('#dialog-form-evento').attr('url');
                    location.href=urlEvento;
            },
	    Cancel: function() {
                $( this ).dialog( "close" );
            }
	},
	close: function() {
            //allFields.val( "" ).removeClass( "ui-state-error" );
        }
     });
     $( "#dialog-form-concepto" ).dialog({
	autoOpen: false,
	height: 300,
	width: 400,
	modal: false,
	buttons: {
            "Guardar": function() {
			overlay.show();
                        var form = $('form#form-ajax');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                               var DetalleCotizacionId=$('#dialog-form-concepto').attr('detalles-cotizacion-id');
                               
                               $.editarTalento(DetalleCotizacionId); 
                               
                                overlay.hide();
                               $('#dialog-form-concepto').dialog( "close" );
                                
                            }else{
                                $('#dialog-form-concepto').html(data);
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
<script>
    $(function() {
        $( "#dialog-message" ).dialog({
            autoOpen: false,
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            autoOpen: false,
            height:140,
            modal: true,
            buttons: {
                "Eliminar registro": function() {
                    $( this ).dialog( "close" );
                    $.eliminarRegistro($("#dialog-confirm").attr('eliminar-registro-id'));
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                    $("#dialog-confirm").attr('eliminar-registro-id',0);
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
                     $("#talentos-cotizaciones").load('/<?php echo $app_name ?>.php/cotizaciones/ajaxRegistroOrder?id=<?php echo $detalles_cotizaciones[0]->getCotizacionId();?>&'+order,
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
<div id="dialog-form-evento" title="Evento" evento-id="" detalles-cotizacion-id="">
    
</div>
<div id="dialog-form-concepto" title="Registro" detalles-cotizacion-id="">
    
</div>
<div id="dialog-message" title="Borrar registro">
    <p>
        <span class="ui-icon ui-icon-cancel" style="float: left; margin: 0 7px 50px 0;"></span>
        Permiso denegado
    </p>
    <p class="mensaje">
        No es posible eliminar el registro
    </p>
</div>
<div id="dialog-confirm" title="Eliminar registro?">
    <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        El elemento va a ser eliminado. Esta seguro?</p>
</div>