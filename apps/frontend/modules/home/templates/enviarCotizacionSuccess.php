<?php use_helper('Escaping') ?>
<div id="sf_admin_container">
<h1 class="titulo-pagina-h1">
    <span class="titulo-pagina-span">
        Enviar cotizacion
    </span>
</h1>

<div id="dialog-form" style="float: left; width: 100%;">                
      <?php include_partial('form_enviar_cotizacion', array('form'=>$form,'cotizacion'=>$cotizacion,"mensaje_ok"=>$mensaje_ok,"enviado"=>$enviado)) ?>
</div>
<div id="documento-adjunto" style="padding-top: 10px;text-align: center; float: left; width: 100%;">
    <iframe src="/uploads/cotizaciones/<?php echo $cotizacion->getId()?>/<?php echo $cotizacion->getPdf()?>" style="width: 100%; height: 400px;">
        
    </iframe>
</div>    
<div style="clear: both;"></div>
   
</div>
 


