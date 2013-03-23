<?php foreach($contratos as $contrato):?>
<div class="dash-registro" id="contrato-id-<?php echo $contrato->getId()?>">
    <?php if($contrato->getEstaFirmado()):?>
        <!--a href="#" onclick="$.quitarContrato('<?php echo $contrato->getId()?>')"-->
        <a href="#">    
            <img src="/images/dashboard/check.png" class="image-status-dash-registro cierre" />
        </a>
    <?php elseif($contrato->getHayContrato()):?>
        <a href="<?php echo url_for("contratos_edit",$contrato)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("contratos_edit",$contrato)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $contrato->getCotizaciones()->getClientes();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $contrato->getCotizaciones()->getDescripcion();?>"
    </div>
    <div class="status-dash-registro">
        Estado: <?php echo $contrato->getStringStatus();?>
    </div>
</div>
<?php endforeach;?>
