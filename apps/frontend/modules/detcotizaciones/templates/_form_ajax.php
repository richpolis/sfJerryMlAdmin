<!--div id="detalles_cotizacion_<?php echo $form->getObject()->getId()?>"-->
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $cotizacion=$form->getObject()->getCotizaciones()?>
<?php $detalles_cotizacion_conceptos=$form->getObject()->getDetallesCotizacionConceptos() ?>                
<form onsubmit="return false;" id="form_detalles_cotizacion_<?php echo $form->getObject()->getId()?>" action="<?php echo url_for('detcotizaciones/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table style="width: 100%">
      <thead>
        <tr>
            <th colspan="3">
                  <?php echo $form->getObject()->getTalentos() ?>
             </th>
        </tr>
      </thead>  
    <tfoot>
      <tr>
        <td colspan="3">
          <?php echo $form->renderHiddenFields(false) ?>
          <?php if (!$form->getObject()->getCotizaciones()->statusAprobada()): ?>
            &nbsp;<input type="button"  value="Eliminar talento" class="buttonEliminar" onclick="$.borrarTalento('<?php echo $form->getObject()->getId() ?>')"/>
          <?php endif; ?>
            <input type="submit"  value="Guardar" class="buttonGuardar"  onclick="tinyMCE.triggerSave();$.guardarTalento('<?php echo $form->getObject()->getId() ?>')" />
            <input type="button"  value="Calcular" class="buttonCalcular"/>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th>Imagen</th>  
        <th><?php echo $form['actividad']->renderLabel() ?></th>
        <th><?php echo $form['precio']->renderLabel() ?></th>
      </tr>
      <tr>
        <td width="20%">
          <img src="/uploads/talentos/<?php echo $form->getObject()->getTalentos()->getImagen()?>" style="max-height: 100px; max-width: 100px;"/>
        </td>  
        <td width="60%">
          <?php echo $form['actividad']->renderError() ?>
          <?php echo $form['actividad']->render(array('class'=>'detalles_cotizacion_actividad_'.$form->getObject()->getId())) ?>
            <div class="li-conceptos">
            <?php $contConceptos=0;?>    
            <?php foreach ($detalles_cotizacion_conceptos as $dcc): ?>
                <?php $contConceptos++;?>
                <?php include_partial('detconceptos/show', array("dcc"=>$dcc))?>
            <?php endforeach; ?>
            </div>
            
           <div id="detalles-cotizacion-concepto-<?php echo $form->getObject()->getId() ?>" style="widith:100%;">
              <table width="100%" >
              <tr>
              <td width="75%">
               &nbsp;
               </td>
              <td width="25%">
              <a class="buttonCrearConcepto" href="#" onclick="$.crearConcepto('<?php echo $form->getObject()->getId()?>')">Crear Concepto</a>
              </td>
              </tr>
              </table>
           </div>
            
        </td>
        <td width="20%">
            <table width="100%">
                <tr>
                    <td>Importe: </td>
                    <td>
                        <?php echo $form['precio']->renderError() ?>
                        <?php //if($contConceptos):?>
                        <?php echo $form['precio']->render(array('readonly' => 'true','class'=>'readonly')); ?>
                        <?php //else:?>
                        <?php //echo $form['precio'] ?>
                        <?php //endif;?>
                        <!--a href="#" class="ui-state-default ui-corner-all ui-icon ui-icon-calculator" title="calcular">
                            &nbsp;
                        </a-->
                    </td>
                </tr>
                <tr>
                    <td>Margen Jerry Ml:</td>
                    <td>
                        <?php echo $form['margen_jerry_ml']->renderError() ?>
                        <?php echo $form['margen_jerry_ml'] ?>
                    </td>
                </tr>
                <?php if(strlen($cotizacion->getComisionista())>0):?>
                <tr>
                    <td>Margen Comisionista:</td>
                    <td>
                        <?php echo $form['margen_comisionista']->renderError() ?>
                        <?php echo $form['margen_comisionista'] ?>
                    </td>
                </tr>
                <?php endif;?>
                
                
            </table>
            <div id="calculo-detalle-cotizacion-<?php echo $cotizacion->getId()?>">
                
            </div>
        </td>
      </tr>
    </tbody>
  </table>
</form>
        
<!--/div-->

<script>
    $(document).ready(function(){
       $("input.buttonEliminar, input.buttonGuardar,input.buttonCalcular,input.buttonEditarConcepto, a.buttonCrearConcepto").button(); 
        
       var formulario               ="#form_detalles_cotizacion_<?php echo $form->getObject()->getId()?>";
       //var $precio                  =$(formulario + " input#detalles_cotizacion_precio");
       //var $margenJerryml           =$(formulario + " input#detalles_cotizacion_margen_jerry_ml");
       //var $margenComisionista      =$(formulario + " input#detalles_cotizacion_comisionista");
       var $divCalculoDetalleCotizacion=$("#calculo-detalle-cotizacion-<?php echo $cotizacion->getId()?>");
       
       /*$precio.keyup(function() {
            calcular();
        });
        $margenJerryml.keyup(function() {
            calcular();
        });
        $margenComisionista.keyup(function() {
            calcular();
        });*/
        
        $("input.buttonCalcular").click(function(){
            calcular();
        });
        
        
       function calcular(){
        $divCalculoDetalleCotizacion.html("<div style=\"height:100px;width:100px;\">Calculando...</div>");
        $.post('<?php echo url_for('detcotizaciones/calculo')?>', 
            $("form"+formulario).serialize(), 
            function(data){
                $divCalculoDetalleCotizacion.html(data);
            }   
        );
       }
       calcular();
    });
    
</script>    