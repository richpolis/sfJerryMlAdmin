<?php use_helper('I18N', 'Date') ?>
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<?php $confPrecotizaciones=  Doctrine_Core::getTable('Configuracion')->getSeccion("precotizaciones");?>

<div id="sf_admin_container">
  <div id="sf_admin_header">
      <div style="text-align: right; width: 100%">
          <?php echo date("d/m/Y")?>
      </div>
      <img src="http://<?php echo $sf_request->getHost()?>/uploads/assets/<?php echo $confPrecotizaciones->getImagen()?>" style="height: 100px;" />
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>  
  <h1><?php echo __('Pre-Cotizacion JerryML', array(), 'messages') ?></h1>
  <div id="sf_admin_content">
      <table border="0">
          <tbody>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Cliente:
                  </td>
                  <td>
                      <?php echo $precotizaciones->getClientes()?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Evento:
                  </td>
                  <td>
                      <?php echo $precotizaciones->getEvento()?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Descripcion:
                  </td>
                  <td>
                      <?php echo $precotizaciones->getDescripcion(ESC_RAW)?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Actividad: 
                  </td>
                  <td>
                      <?php echo $precotizaciones->getActividadGeneral(ESC_RAW)?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Fecha Evento:
                  </td>
                  <td>
                      <?php echo sfRichSys::getStringFechasInicialFinal($precotizaciones->getIniciaEvento(),$precotizaciones->getTerminaEvento())?> 
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;"> 
                      Lugar:
                  </td>
                  <td>
                      <?php echo $precotizaciones->getLugarEvento(ESC_RAW)?> 
                  </td>
              </tr>
          </tbody>
      </table>
      <div  style="width:100%; height: 30px;">
          
      </div>
      <?php $detalles_precotizacion=$precotizaciones->getDetallesPrecotizacion()?>
      <?php foreach($detalles_precotizacion as $detalle):?>
        <table style="width: 100%;">
            <tbody>
                <tr aling="center" valing="middle"  style="background-color: #1188FF;  text-transform: uppercase; font-weight: bold;">
                    <th style="color: white;">ARTISTA</th>
                    <th style="color: white;">INFORMACIÓN</th>
                    <th style="color: white;">COSTO</th>
                </tr>
                <tr>
                    <td width="20%"  style="text-align: center; vertical-align: central;">
                        <?php echo $detalle->getTalentos() ?> <br/>
                        <?php if(file_exists(sfConfig::get('sf_upload_dir').'/talentos/'.$detalle->getTalentos()->getImagen())):?>
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/talentos/<?php echo $detalle->getTalentos()->getImagen() ?>" style="max-height: 100px; max-width: 100px;" width="100" height="100"/>
                        <?php else:?>
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/assets/<?php echo $confPrecotizaciones->getImagen()?>" style="max-height: 100px; max-width: 100px;" />
                        <?php endif;?>    
                    </td>  
                    <td width="60%" style="vertical-align: central; text-align: justify;">
                        <?php echo $detalle->getActividad(ESC_RAW) ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 </td>
                    <td width="20%" style="text-align: center; vertical-align: central;">
                        <?php echo format_currency($detalle->getPrecio(), 'USD')  ?>
                    </td>
                </tr>
            </tbody>
        </table>
      <?php endforeach;?>
      
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
  <div id="sf_admin_footer">
    <?php include_partial('configuracion/contenido', array("configuracion"=>$confPrecotizaciones))?>
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
  <div  style="width:100%; height: 30px;">
      Atte. <?php echo $sf_user->getGuardUser()->getNombreCompleto()?><br/>
      <?php echo $sf_user->getGuardUser()->getEmail()?>
  </div>
</div>