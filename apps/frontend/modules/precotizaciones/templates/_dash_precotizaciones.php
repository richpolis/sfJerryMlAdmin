<?php foreach($precotizaciones as $key=>$precotizacion):?>
    <?php if($key>0):?>
        <hr style="margin: 0;"/>
    <?php endif;?>
<div class="dash-registro" id="precotizacion-id-<?php echo $precotizacion->getId()?>">
    <?php if($precotizacion->statusAprobada()):?>
        <a href="#" onclick="$.quitarPrecotizacion('<?php echo $precotizacion->getId()?>')">
            <img src="/images/dashboard/borrar.png" class="image-status-dash-registro" />
        </a>
    <?php elseif($precotizacion->statusEnMediacion()):?>
        <a href="<?php echo url_for("precotizaciones_show",$precotizacion)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("precotizaciones_show",$precotizacion)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $precotizacion->getContactos();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $precotizacion->getEvento();?>"
    </div>
    <div class="status-dash-registro">
        Estado: <?php echo $precotizacion->getStringStatus();?>
    </div>
</div>
<?php endforeach;?>
