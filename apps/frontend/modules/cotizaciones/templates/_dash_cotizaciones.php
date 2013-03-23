<?php //foreach($cotizaciones as $cotizacion):?>
<div class="dash-registro" id="registro-cotizacion-id-<?php echo $cotizacion->getId()?>">
    <?php if($cotizacion->statusPagosLiberados()):?>
        <!--a href="#" onclick="$.quitarCotizacion('<?php echo $cotizacion->getId()?>')"-->
        <a href="#">    
            <img src="/images/dashboard/check.png" class="image-status-dash-registro cierre" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("cotizaciones_show",$cotizacion)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $cotizacion->getContactos();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $cotizacion->getDescripcion();?>"
    </div>
    <div class="status-dash-registro">
        Estado: <?php echo $cotizacion->getStringStatus();?>
    </div>
</div>
<?php //endforeach;?>
