<?php use_helper('I18N', 'Date','Number') ?>

<div id="sf_admin_container" style="background-image: url(/images/dashboard/backgroundajustable.png);">
  <h1 style="color: white;"><?php echo __('Dashboard Usuario: ', array(), 'messages') ?><?php echo $sf_user->getGuardUser(); ?></h1>
  <?php include_partial('home/flashes') ?>
  <div id="sf_admin_content" class="dashboard">
      <div id="tabs-dashboard">
        <div class="tab-dashboard" id="tabs-precotizaciones">
            <h3 class="tab-dashboard-titulo">Precotizaciones</h3>
            <div class="tab-dashboard-contenedor">
                <?php include_partial('precotizaciones/dash_precotizaciones', array('precotizaciones'=>Doctrine_Core::getTable('Precotizaciones')->getUltimasPrecotizaciones(),"dashboard"=>true))?>
            </div>
        </div>
        <div class="tab-dashboard" id="tabs-cotizaciones">
            <h3 class="tab-dashboard-titulo">Cotizaciones</h3>
            <div class="tab-dashboard-contenedor">
                <?php include_partial('cotizaciones/dash_cotizaciones', array('cotizaciones'=>Doctrine_Core::getTable('Cotizaciones')->getUltimasCotizaciones(),"dashboard"=>true))?>
            </div>
            
        </div>
        <div class="tab-dashboard" id="tabs-contratos">
            <h3 class="tab-dashboard-titulo">Contratos</h3>
            <div class="tab-dashboard-contenedor">
                <?php include_partial('contratos/dash_contratos', array('contratos'=>Doctrine_Core::getTable('Contratos')->getUltimosContratos(),"dashboard"=>true))?>
            </div>
        </div>
        <div class="tab-dashboard" id="tabs-pagos">
            <h3 class="tab-dashboard-titulo">Pagos Clientes</h3>
            <div class="tab-dashboard-contenedor">
                <?php include_partial('pagos/dash_pagos', array('cotizaciones'=>Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPendientesDePago(),"dashboard"=>true))?>
            </div>
            
        </div>
        <div class="tab-dashboard" id="tabs-pagos-talentos">
            <h3 class="tab-dashboard-titulo">Pagos Talentos</h3>
            <div class="tab-dashboard-contenedor">
                <?php include_partial('pagos_talentos/dash_pagos_talentos', array('detalles_cotizacion'=>Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPendientesDePago(),"dashboard"=>true))?>
            </div>
            
        </div>  
    </div>
    <div id="eventos-dashboard">
        <img src="/images/bot_add_eventos_usuarios.png" class="eventos-dashboard-boton" onclick="$.crearEvento('<?php echo $sf_user->getGuardUser()->getId()?>')" />
        <div id="eventos-dashboard-contenedor">
          <?php include_partial('eventos_usuarios/dash_eventos', array('eventos'=>Doctrine_Core::getTable('EventosUsuarios')->getProximosEventosUsuarios(),"dashboard"=>true))?>
        </div>
        
    </div>  
      
      
  </div>
  <div style="clear: both;"></div>
  <div id="sf_admin_footer" >
    <?php //include_partial('clientes/list_footer', array('pager' => $pager)) ?>
  </div>
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
  
jQuery.quitarPrecotizacion=function(Id){
    overlay.show();
    $.get('<?php echo url_for1('precotizaciones/inactivar')?>',{id:Id},function(data){
        //alert(data);
        if(data=="ok"){
           $('#precotizacion-id-'+Id).hide();
           overlay.hide();
        }else{
           overlay.hide();
        }
    });
}
jQuery.quitarCotizacion=function(Id){
    overlay.show();
    $.get('<?php echo url_for1('cotizaciones/inactivar')?>',{id:Id},function(data){
        //alert(data);
        if(data=="ok"){
           $('#cotizacion-id-'+Id).hide();
           overlay.hide();
        }else{
           overlay.hide();
        }
    });
}   
jQuery.quitarContrato=function(Id){
    overlay.show();
    $.get('<?php echo url_for1('contratos/inactivar')?>',{id:Id},function(data){
        //alert(data);
        if(data=="ok"){
           $('#contrato-id-'+Id).hide();
           overlay.hide();
        }else{
           overlay.hide();
        }
    });
}
jQuery.crearEvento=function(UsuarioId){
    overlay.show();
    $('#dialog-form-evento').attr('usuario-id',UsuarioId).attr('evento-id',0);
    $.get('<?php echo url_for('eventos_usuarios/newAjax')?>',{user_id: UsuarioId},function(data){
        $('#dialog-form-evento').html(data).dialog('open');
        overlay.hide();
    });
}

jQuery.editarEvento=function(EventoId){
    overlay.show();
    $('#dialog-form-evento').attr('evento-id',EventoId);
    $.get('<?php echo url_for('eventos_usuarios/editAjax')?>',{id:EventoId},function(data){
        $('#dialog-form-evento').html(data).dialog('open');
        overlay.hide();
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
                        var form = $('form#form-eventos-usuarios-ajax');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                               var EventoId=$('#dialog-form-evento').attr('evento-id');
                                $.get("<?php echo url_for('eventos_usuarios/dashboard')?>",{id: EventoId},function(data){
                                    $('#eventos-dashboard-contenedor').html(data);
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
                location.href="<?php echo url_for("sfGuardUser/listCalendario?id=".$sf_user->getGuardUser()->getId()."&goto=@homepage")?>";
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
<div id="dialog-form-evento" title="Evento" evento-id="">
    
</div>