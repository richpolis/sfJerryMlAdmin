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
  <!--h1><?php echo __('Cotizacion JerryML', array(), 'messages') ?></h1-->
  <div id="sf_admin_content">
      <!--table border="0">
          <tbody>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Cliente:
                  </td>
                  <td>
                      <?php echo $cotizaciones->getClientes()?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Evento:
                  </td>
                  <td>
                      <?php echo $cotizaciones->getEvento()?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Descripcion:
                  </td>
                  <td>
                      <?php echo $cotizaciones->getDescripcion(ESC_RAW)?>
                  </td>
              </tr>
          </tbody>
      </table-->
      <?php echo $cotizaciones->getContactos()?>,<br/>
      <?php echo $cotizaciones->getEvento()?><br/>
      Presente. 
      
      <div  style="width:100%; height: 30px;">
          
      </div>
      <?php foreach($detalles_cotizaciones as $detalle):?>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: small; text-transform: uppercase;">
                            
                    </th>
                </tr>
            </thead> 
            <tbody>
                <tr aling="center" valing="middle"  style="background-color: #1188FF;  text-transform: uppercase; font-weight: bold;">
                    <th style="color: white;">ARTISTA</th>
                    <th style="color: white;">INFORMACIÓN</th>
                    <th style="color: white;">COSTO</th>
                </tr>
                <tr aling="center" valing="top" >
                    <td width="20%">
                        <?php echo $detalle->getTalentos() ?><br/>
                        <?php if(file_exists(sfConfig::get('sf_upload_dir').'/talentos/'.$detalle->getTalentos()->getImagen())):?>
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/talentos/<?php echo $detalle->getTalentos()->getImagen() ?>" style="max-height: 100px; max-width: 100px;" width="100" height="100"/>
                        <?php else:?>
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/assets/<?php echo $confCotizacion->getImagen()?>" style="max-height: 100px; max-width: 100px;" />
                        <?php endif;?>
                            
                    </td>  
                    <td width="60%" style=" font-style: normal; text-align: justify; " ><?php echo $detalle->getActividad(ESC_RAW) ?></td>
                    
                    <td width="20%">
                        <?php echo format_currency($detalle->getSubtotal(), 'USD') ?>
                    </td>
                </tr>
                <tr aling="center" valing="top">
                    <td style=" padding-top: 20px;">Actividades:</td>
                    <td aling="center" valing="top" style=" padding-top: 20px;">
                        <?php foreach($cotizaciones->getCotizacionesEventos() as $evento):?>
                            <?php if($evento->getEventos()->getTalentoId()==$detalle->getTalentoId()):?>
                            <div id="evento<?php echo $evento->getEventos()->getId()?>">
                                <?php echo sfRichSys::getStringFechasInicialFinal($evento->getEventos()->getStartTime(), $evento->getEventos()->getEndTime())?> - <?php echo $evento->getEventos()->getSubject()?> - <?php echo $evento->getEventos()->getLugarEvento()?>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
      <?php endforeach;?>
      
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
  <div id="sf_admin_footer">
    <?php include_partial('configuracion/contenido', array("configuracion"=>$confCotizacion))?>
  </div>
</div>