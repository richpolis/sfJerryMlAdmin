<?php use_helper('I18N', 'Date', 'Number') ?>
<?php use_stylesheet('bootstrap.css'); ?>
<?php use_javascript('bootstrap.min.js'); ?>

<div id="sf_admin_container" style="background-image: url(/images/dashboard/backgroundajustable.png); padding: 0;">
  <!--h1 style="color: white;"><?php echo __('Dashboard Usuario: ', array(), 'messages') ?><?php echo $sf_user->getGuardUser(); ?></h1-->
    <?php include_partial('home/flashes') ?>
    <div id="sf_admin_content" class="dashboard">
        <div id="tabs-dashboard">
            <div class="row-fluid">
                <div style="width: 16%; float: left; min-height:500px;" id="contenedor-precotizaciones">    
                    <table width="100%" class="table table-striped" style="background-color: white;" >
                        <tr>
                            <th class="tab-dashboard-titulo">Precotizaciones</th>
                        </tr>
                        <tr>
                            <td class="borde-right">
                                <div class="tab-dashboard-contenedor">
                                    <?php include_partial('precotizaciones/dash_precotizaciones', array('precotizaciones' => $precotizaciones, "dashboard" => true)) ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
               
                <div style="width: 84%; float: left; min-height:500px;" id="contenedor-cotizaciones">
                    <table width="100%" class="table table-striped" style="background-color: white;" >
                        <tr>
                            <th width="19%" class="tab-dashboard-titulo">Cotizaciones</th>
                            <th width="19%" class="tab-dashboard-titulo">Contratos</th>
                            <th width="19%" class="tab-dashboard-titulo">Facturas</th>
                            <th width="19%" class="tab-dashboard-titulo">Pagos Clientes</th>
                            <th width="19%" class="tab-dashboard-titulo">Pagos Talentos</th>
                            <th width="5%" class="tab-dashboard-titulo">&nbsp;</th>
                        </tr>
                        <?php foreach ($cotizaciones as $cotizacion): ?>
                            <tr id="cotizacion-id-<?php echo $cotizacion->getId()?>">
                                <td class="borde-right">
                                    <div class="tab-dashboard-contenedor">
                                        <?php include_partial('cotizaciones/dash_cotizaciones', array('cotizacion' => $cotizacion))?>
                                    </div>
                                </td>
                                <td class="borde-right">
                                    <div class="tab-dashboard-contenedor">
                                        <?php include_partial('contratos/dash_contratos', array('contratos' => $cotizacion->getContratos())) ?>
                                    </div>
                                </td>
                                <td class="borde-right">
                                    <div class="tab-dashboard-contenedor">
                                        <?php include_partial('facturas/dash_facturas', array('facturas' => $cotizacion->getFacturas()))?>
                                    </div>
                                </td>
                                <td class="borde-right">
                                    <div class="tab-dashboard-contenedor">
                                        <?php include_partial('pagos/dash_pagos', array('cotizacion' => $cotizacion))?>
                                    </div> 
                                </td>
                                <td class="borde-right">
                                    <div class="tab-dashboard-contenedor">
                                        <?php include_partial('pagos_talentos/dash_pagos_talentos', array('detalles_cotizacion' => $cotizacion->getDetallesCotizacion()))?>
                                    </div>
                                </td>
                                <td class="borde-right">
                                    <div class="tab-dashboard-contenedor">
                                        <div class="dash-registro">
                                          <a href="#" onclick="$.quitarCotizacion('<?php echo $cotizacion->getId()?>')" id="control-cotizacion-id-<?php echo $cotizacion->getId()?>" style="display: none;">
                                             <img src="/images/dashboard/borrar.png" class="image-status-dash-registro" />
                                          </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </table>
                </div>
            </div>
            <div style="float:left; height: 64px; width: 97%;">
                <div id="eventos-dashboard-contenedor">
                    <?php include_partial('eventos_usuarios/dash_eventos', array('eventos' => $eventos, "dashboard" => true)) ?>
                </div>
            </div>
            <div align="center" valign="middle" style="background-color: #00ADEE; float: right; height: 64px; width: 3%;">
                <img src="/images/bot_add_eventos_usuarios.png" class="eventos-dashboard-boton" onclick="$.crearEvento('<?php echo $sf_user->getGuardUser()->getId() ?>')" />
            </div>

        </div>
    </div>
    <div style="clear: both;"></div>
    <div id="sf_admin_footer" >
<?php //include_partial('clientes/list_footer', array('pager' => $pager))  ?>
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
        $.get('<?php echo url_for1('precotizaciones/inactivar') ?>',{id:Id},function(data){
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
        $.get('<?php echo url_for1('cotizaciones/inactivar') ?>',{id:Id},function(data){
            //alert(data);
            if(data=="ok"){
                $('#cotizacion-id-'+Id).hide();
                overlay.hide();
            }else{
                overlay.hide();
            }
        });
    }   
    jQuery.crearEvento=function(UsuarioId){
        overlay.show();
        $('#dialog-form-evento').attr('usuario-id',UsuarioId).attr('evento-id',0);
        $.get('<?php echo url_for('eventos_usuarios/newAjax') ?>',{user_id: UsuarioId},function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
    }

    jQuery.editarEvento=function(EventoId){
        overlay.show();
        $('#dialog-form-evento').attr('evento-id',EventoId);
        $.get('<?php echo url_for('eventos_usuarios/editAjax') ?>',{id:EventoId},function(data){
            $('#dialog-form-evento').html(data).dialog('open');
            overlay.hide();
        });
    }
</script>
<script>
    $(function() {
    
        var cotizaciones=new Array();
         <?php foreach($cotizaciones as $key=>$cotizacion):?>
            cotizaciones[<?php echo $key?>]="cotizacion-id-<?php echo $cotizacion->getId()?>";
         <?php endforeach;?>   
       
        var seguimiento=0;
        for (var i=0;i<cotizaciones.length;i++)
        { 
            seguimiento=$("#"+cotizaciones[i]).find(".seguimiento").length;
            if(seguimiento==0){
                $("#control-"+cotizaciones[i]).show();
            }
        }
        
    
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
                            $.get("<?php echo url_for('eventos_usuarios/dashboard') ?>",{id: EventoId},function(data){
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
                    location.href="<?php echo url_for("sfGuardUser/listCalendario?id=" . $sf_user->getGuardUser()->getId() . "&goto=@homepage") ?>";
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