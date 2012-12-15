<?php use_helper('I18N', 'Date') ?>
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>


<div id="sf_admin_container">
  <div id="sf_admin_header">
      <div style="text-align: right; width: 100%">
          <?php echo date("d/m/Y")?>
      </div>
      <img src="http://<?php echo $sf_request->getHost()?>/images/Logojerryml.png" style="max-height: 100px; max-width: 100px;" />
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>  
  <h1><?php echo __('Cotizacion JerryML', array(), 'messages') ?></h1>
  <div id="sf_admin_content">
      <table border="0">
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
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Actividad: 
                  </td>
                  <td>
                      <?php echo $cotizaciones->getActividadGeneral(ESC_RAW)?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;">
                      Fecha Evento:
                  </td>
                  <td>
                      <?php echo $cotizaciones->getIniciaEvento('dd/MM/YY HH:MM')?> -
                      <?php echo $cotizaciones->getTerminaEvento('dd/MM/YY HH:MM')?>
                  </td>
              </tr>
              <tr>
                  <td class="titulo_celda" style="background-color: #1188FF; color: white; font-weight: bold;"> 
                      Lugar:
                  </td>
                  <td>
                      <?php echo $cotizaciones->getLugarEvento(ESC_RAW)?> 
                  </td>
              </tr>
          </tbody>
      </table>
      <div  style="width:100%; height: 30px;">
          
      </div>
      <?php foreach($detalles_cotizaciones as $detalle):?>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: small; text-transform: uppercase;">
                        <?php echo $detalle->getTalentos() ?>    
                    </th>
                </tr>
            </thead> 
            <tbody>
                <tr style="background-color: #1188FF; color: white; font-weight: bold;">
                    <th style="color: white;">Foto:</th>
                    <th style="color: white;">Actividad:</th>
                    <th style="color: white;">Precio:</th>
                </tr>
                <tr>
                    <td width="20%">
                        <img src="http://<?php echo $sf_request->getHost()?>/uploads/talentos/<?php echo $detalle->getTalentos()->getImagen() ?>" style="max-height: 100px; max-width: 100px;" width="100" height="100"/>
                            
                    </td>  
                    <td width="60%"><?php echo $detalle->getActividad(ESC_RAW) ?></td>
                    
                    <td width="20%">
                        <?php 
                            $precio=$detalle->getPrecio();
                            $margenJerry=$detalle->getMargenJerryMl()/100;
                            $gananciaJerryMl=$precio*$margenJerry;
                            echo format_currency($precio+$gananciaJerryMl, 'USD') 
                            ?>
                    </td>
                </tr>
            </tbody>
        </table>
      <?php endforeach;?>
      
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
  <div id="sf_admin_footer">
    <p>  
        <strong>
            NOTA: Los costos son exclusivos para este evento en las actividades mencionadas y no se podrá ligar al artista con otras marcas ni empresas fuera de lo que se indica en esta cotización. 
        </strong>
    <br/>
    Consideraciones:
    <br/> 
    El precio es en pesos mexicanos.<br/>
    El precio es más I.V.A. <br/>
    Se requiere de un pago del 50% de anticipo a la firma del contrato y el 50% 2 días hábiles antes del evento.<br/>
    El costo deberá ser libre de retenciones<br/>
    <br/>
    Costos vigentes 10 días a partir de la fecha del documento.<br/>
    </p>
  </div>
</div>