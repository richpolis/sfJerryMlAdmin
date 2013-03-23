<?php use_helper('I18N', 'Date') ?>
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<?php $confCotizacion=Doctrine_Core::getTable('Configuracion')->getSeccion("cotizaciones");?>

<div id="sf_admin_container">
  <div id="sf_admin_header">
      <div style="text-align: right; width: 100%">
          <?php echo date("d/m/Y")?>
      </div>
      <img src="http://<?php echo $sf_request->getHost()?>/uploads/assets/<?php echo $confCotizacion->getImagen()?>" style="height: 100px;" />
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>  
  <div id="sf_admin_content">
      
      <?php echo $cotizaciones->getContactos()?>,<br/>
      <?php echo $cotizaciones->getClientes()?><br/>
      Presente. 
      
      <div  style="width:100%; height: 30px;">
          
      </div>
      Te envío la información que me solicitaste referente a las siguiente(s) celebridad(es):<br/> 
      <?php if($cotizaciones->getTipoCotizacion()==CotizacionesTable::$TIPO_COTIZACION_CAMPANA):?>
      Descripción: <?php echo $cotizaciones->getDescripcion();?><br/>
      Vigencia: <?php echo $cotizaciones->getVigencia();?><br/>
      Territorio: <?php echo $cotizaciones->getPlaza();?><br/>
      Dias de trabajo: <?php echo $cotizaciones->getActividad(ESC_RAW);?><br/>
      <?php else:?>
      Descripción: <?php echo $cotizaciones->getDescripcion();?><br/>
      Actividad: <?php echo $cotizaciones->getActividadLimpia(ESC_RAW);?><br/>
      Fecha: <?php echo sfRichSys::getStringFechasInicialFinal($cotizaciones->getFechaDesde(), $cotizaciones->getFechaHasta());?><br/>
      Plaza: <?php echo $cotizaciones->getPlaza();?><br/>
      <?php endif;?>
      <?php foreach($detalles_cotizaciones as $detalle):?>
        <table style="width: 100%; border: 1px solid grey;">
            <tbody>
                <tr aling="center" valing="middle"  style="background-color: #1188FF;  text-transform: uppercase; font-weight: bold;">
                    <th style="color: white;">ARTISTA</th>
                    <th style="color: white;">INFORMACIÓN</th>
                    <th style="color: white;">COSTO</th>
                </tr>
                <tr>
                    <td width="20%" style="text-align: center; vertical-align: central;">
                        <?php echo $detalle->getTalentos() ?><br/>
                        <?php if(file_exists(sfConfig::get('sf_upload_dir').'/talentos/'.$detalle->getTalentos()->getImagen())):?>
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/talentos/<?php echo $detalle->getTalentos()->getImagen() ?>" style="max-height: 100px; max-width: 100px;" width="100" height="100"/>
                        <?php else:?>
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/assets/<?php echo $confCotizacion->getImagen()?>" style="max-height: 100px; max-width: 100px;" />
                        <?php endif;?>
                            
                    </td>  
                    <td width="60%" style=" vertical-align: central; font-style: normal; text-align: justify; " >
                      <?php echo $detalle->getActividad(ESC_RAW) ?>
                    </td>
                    <td width="20%" style="text-align: center; vertical-align: central;">
                        <?php echo format_currency($detalle->getSubtotal(), 'USD') ?>
                    </td>
                </tr>
                <?php $registros=KsWCEventTable::getInstance()->getCountEventosPorDetalleCotizacion($detalle->getId());?>
                <?php if($registros>0):?>
                <tr aling="center" valing="top">
                    <td style=" padding-top: 20px;">Actividades:</td>
                    <td aling="center" valing="top" style=" padding-top: 20px;">
                        <?php foreach($detalle->getEventos() as $evento):?>
                            <?php if($evento->getNivel()==CotizacionesTable::$NIVEL_DETALLE):?>
                            <div id="evento<?php echo $evento->getId()?>">
                                <?php echo sfRichSys::getStringFechasInicialFinal($evento->getStartTime(), $evento->getEndTime())?> - <?php echo $evento->getSubject()?> - <?php echo $evento->getLugarEvento()?>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td></td>
                </tr>
               <?php endif; ?> 
            </tbody>
        </table>
      <?php endforeach;?>
      
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
  <div id="sf_admin_footer">
    <?php include_partial('configuracion/contenido_vista_previa', array("configuracion"=>$confCotizacion,"cotizaciones"=>$cotizaciones))?>
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
  <div  style="width:100%; height: 30px;">
      Atte. <?php echo $sf_user->getGuardUser()->getNombreCompleto()?><br/>
      <?php echo $sf_user->getGuardUser()->getEmail()?>
  </div>  
</div>