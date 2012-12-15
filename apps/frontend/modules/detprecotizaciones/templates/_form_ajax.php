<!--div id="detalles_precotizacion_<?php echo $form->getObject()->getId()?>"-->
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $precotizacion=$form->getObject()->getPrecotizaciones()?>
                
<form onsubmit="return false;" id="form_detalles_precotizacion_<?php echo $form->getObject()->getId()?>" action="<?php echo url_for('detprecotizaciones/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
            <?php if (!$form->getObject()->getPrecotizaciones()->statusAprobada()): ?>
             &nbsp;<input type="button"  value="Eliminar talento" class="buttonEliminar" onclick="$.borrarTalento('<?php echo $form->getObject()->getId() ?>')"/>
           <?php endif; ?>
            <input type="submit"  value="Guardar" class="buttonGuardar"  onclick="tinyMCE.triggerSave();$.guardarTalento('<?php echo $form->getObject()->getId() ?>')" />
            <input type="button"  value="Calcular" class="buttonCalcular"   />
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
          <?php echo $form['actividad']->render(array('class'=>'detalles_precotizacion_actividad_'.$form->getObject()->getId())) ?>
        </td>
        <td width="20%">
            <table width="100%">
                <tr>
                    <td>Importe: </td>
                    <td>
                        <?php echo $form['precio']->renderError() ?>
                        <?php echo $form['precio'] ?>
                        <!--a href="#" class="ui-state-default ui-corner-all" title="calcular">
                            <span class="ui-icon ui-icon-calculator">&nbsp;</span>
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
            </table>
            <div id="calculo-detalle-precotizacion-<?php echo $precotizacion->getId()?>">
                
            </div>
        </td>
      </tr>
    </tbody>
  </table>
</form>
        
<!--/div-->

<script>
    $(document).ready(function(){
       $("input:submit, input:button").button(); 
       var formulario  ="#form_detalles_precotizacion_<?php echo $form->getObject()->getId()?>";
       //var $precio     =$(formulario + " input#detalles_precotizacion_precio");
       //var $margenJerryml =$(formulario + " input#detalles_precotizacion_margen_jerry_ml");
       var $divCalculoDetallePrecotizacion=$("#calculo-detalle-precotizacion-<?php echo $precotizacion->getId()?>");
       
       $("input.buttonCalcular").click(function(){
            calcular();
        });
       
       function calcular(){
        $divCalculoDetallePrecotizacion.html("Calculando...");
        $.post('<?php echo url_for('detprecotizaciones/calculo')?>', 
            $("form"+formulario).serialize(), 
            function(data){
                $divCalculoDetallePrecotizacion.html(data);
            }   
        );
       }
       calcular();
    });
    
</script>    