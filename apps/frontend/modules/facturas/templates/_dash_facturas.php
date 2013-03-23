<?php foreach($facturas as $factura):?>
<div class="dash-registro" id="factura-id-<?php echo $factura->getId()?>">
    <?php if($factura->getHayArchivo() && $factura->getCotizacionId()>0):?>
        <!--a href="#" onclick="$.quitarFactura('<?php echo $factura->getId()?>')"-->
        <a href="#">    
            <img src="/images/dashboard/check.png" class="image-status-dash-registro cierre" />
        </a>
    <?php elseif($factura->getHayArchivo()):?>
        <a href="<?php echo url_for("facturas_edit",$factura)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("facturas_edit",$factura)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php 
        if($factura->getCotizacionId()>0):
            echo $factura->getCotizaciones()->getClientes();
        else:
            echo "Sin cliente detectado";
        endif;
        ?>
    </div>
    <div class="contenido-dash-registro">
        "<?php 
        if($factura->getCotizacionId()>0):
            echo $factura->getCotizaciones()->getDescripcion();
        else:
            echo "Sin evento detectado";
        endif;
        ?>"
    </div>
    <div class="status-dash-registro">
        Estado: <?php echo $factura->getStringStatus();?>
    </div>
</div>
<?php endforeach;?>
