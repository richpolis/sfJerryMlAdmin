<?php foreach($cotizaciones as $cotizacion):?>
<div class="dash-registro" id="cotizacion-id-<?php echo $cotizacion->getId()?>">
    <?php if($cotizacion->statusPagosLiberados()):?>
        <a href="#" onclick="$.quitarCotizacion('<?php echo $cotizacion->getId()?>')">
            <img src="/images/dashboard/borrar.png" class="image-status-dash-registro" />
        </a>
    <?php //elseif($cotizacion->statusAprobada()):?>
        <!--a href="<?php echo url_for("cotizaciones_show",$cotizacion)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro" />
        </a-->
    <?php else:?>
        <a href="<?php echo url_for("cotizaciones_show",$cotizacion)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $cotizacion->getContactos();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $cotizacion->getEvento();?>"
    </div>
    <div class="status-dash-registro">
        Estado: <?php echo $cotizacion->getStringStatus();?>
    </div>
</div>
<?php endforeach;?>
