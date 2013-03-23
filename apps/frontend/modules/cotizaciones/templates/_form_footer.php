<script>
    var overlay = {
        show    : function(){
            $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
        },
        hide    : function(){
            $('#overlay,#preloader').remove();
        }
    }
    jQuery.crearEvento=function(CotizacionId){
        overlay.show();
        $('#dialog-form-evento').attr('evento-id','0').attr('cotizacion-id',CotizacionId);
        $.get('<?php echo url_for('@ks_wc_event_new') ?>',{'cotizacion_id':CotizacionId,'nivel':<?php echo CotizacionesTable::$NIVEL_TEMPLATE ?>},function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
    }
    jQuery.editarEvento=function(EventoId,CotizacionId){
        overlay.show();
        $('#dialog-form-evento').attr('evento-id',EventoId).attr('cotizacion-id',CotizacionId);
        $.get('/eventos/'+EventoId+'/edit',function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
    }
   
    jQuery.crearConcepto=function(CotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Concepto').attr('registro-id',0).attr('cotizacion-id',CotizacionId).attr('find-id','li-conceptos');
        $.get('<?php echo url_for('cotconceptos/new') ?>',{cotizacion: CotizacionId,'nivel':<?php echo CotizacionesTable::$NIVEL_TEMPLATE ?>},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
    }
    jQuery.editarConcepto=function(ConceptoId,CotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Concepto').attr('registro-id',ConceptoId).attr('cotizacion-id',CotizacionId).attr('find-id','li-conceptos');
        var Url="<?php echo url_for("cotconceptos/edit") ?>";
        $.get(Url,{id: ConceptoId},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
    }
    jQuery.eliminarConcepto=function(ConceptoId,CotizacionId){
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Concepto').attr('registro-id',ConceptoId).attr('cotizacion-id',CotizacionId).attr('find-id','li-conceptos');
        formu = document.createElement("form");// creamos el formulario
        formu.action = "<?php echo url_for("cotconceptos/delete") ?>";
        formu.method = "post";
        overlay.show();        
        $.post(formu.action,{id:ConceptoId},function(data){
            if(data=="ok"){
                $.get("<?php echo url_for("cotconceptos/index") ?>",{'cotizacion':CotizacionId}, function(data){
                    $("#li-conceptos").html(data).find("input").button();
                });
                overlay.hide();
            }else{
                $( "#dialog-message p.mensaje" ).html(data);
                $( "#dialog-message" ).dialog("open");
                overlay.hide();
            }
        });
    }
   
    jQuery.crearComisionista=function(CotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Comisionista').attr('registro-id',0).attr('cotizacion-id',CotizacionId).attr('find-id','li-comisionistas');
        $.get('<?php echo url_for('cotcomisionistas/new') ?>',{cotizacion: CotizacionId,'nivel':<?php echo CotizacionesTable::$NIVEL_TEMPLATE ?>},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
    }
   
    jQuery.editarComisionista=function(ComisionistaId,CotizacionId){
        overlay.show();
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Comisionista').attr('registro-id',ComisionistaId).attr('cotizacion-id',CotizacionId).attr('find-id','li-comisionistas');
        var Url="<?php echo url_for("cotcomisionistas/edit") ?>";
        $.get(Url,{id: ComisionistaId},function(data){
            $dialogo.html(data).dialog('open');
            overlay.hide();
        });
    }
    jQuery.eliminarComisionista=function(ComisionistaId,CotizacionId){
        $dialogo=$('#dialog-form-concepto');
        $dialogo.attr('title','Comisionista').attr('registro-id',ComisionistaId).attr('cotizacion-id',CotizacionId).attr('find-id','li-comisionistas');
        formu = document.createElement("form");// creamos el formulario
        formu.action = "<?php echo url_for("cotcomisionistas/delete") ?>";
        formu.method = "post";
        overlay.show();        
        $.post(formu.action,{id:ComisionistaId},function(data){
            //alert(CotizacionId);
            if(data=="ok"){
                $.get("<?php echo url_for("cotcomisionistas/index") ?>",{'cotizacion':CotizacionId}, function(data){
                    $("#li-comisionistas").html(data).find("input").button();
                });
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
                            $.get("<?php echo url_for('eventos/show') ?>",{id: EventoId},function(data){
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
                            var $dialogo=$( "#dialog-form-concepto" );
                            var dato=$dialogo.attr('find-id');
                            var cotizacion=$dialogo.attr('cotizacion-id');
                            $dialogo.dialog( "close" );
                            if(dato=="li-conceptos"){
                                $.get("<?php echo url_for("cotconceptos/index") ?>",{'cotizacion': cotizacion}, function(data){
                                    $("#li-conceptos").html(data).find("input").button();
                                });
                            }else{
                                $.get("<?php echo url_for("cotcomisionistas/index") ?>",{'cotizacion':cotizacion}, function(data){
                                    $("#li-comisionistas").html(data).find("input").button();
                                });
                            }
                            
                            overlay.hide();
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

    });
</script>
<div id="dialog-form-evento" title="Evento" evento-id="" cotizacion-id="">

</div>
<div id="dialog-form-concepto" title="Registro" cotizacion-id="">

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