<?php use_helper('Number') ?>
<?php foreach($detalles_cotizacion as $key=>$dc):?>
<?php 
    $dpts=$dc->getDetallesPagosTalentos();
    $importe=0;
    $totalDetalleCotizacion=$dc->getGananciaTalentoReal();
    $pt=null;
    foreach($dpts as $dpt){
        if($dpt->getStatus()==PagosTalentosTable::$PAGOS_CALCULADOS){
            $importe+=$dpt->getImporte();
        }
        $pt=$dpt->getPagosTalentos();
    }
?>
<?php if(!$pt==null):?>
    <?php if($key>0):?>
        <hr style="margin: 0;"/>
    <?php endif;?>
<div class="dash-registro" id="pago-talento-dc-id-<?php echo $dc->getId()?>">
    <?php if($totalDetalleCotizacion==$importe):?>
        <!--a href="#" onclick="$.quitarPagoTalento('<?php echo $dc->getId()?>')"-->
        <a href="#">    
            <img src="/images/dashboard/check.png" class="image-status-dash-registro cierre" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("pagos_talentos_show",$pt)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $pt->getTalentos();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $dc->getCotizaciones()->getDescripcion();?>"
    </div>
    <div class="status-dash-registro">
        <?php echo "Pagado: ".format_currency($importe, 'USD')." adeuda: " .format_currency($totalDetalleCotizacion-$importe, 'USD') ; ?>     
    </div>
</div>

<?php endif; ?>
<?php endforeach;?>
