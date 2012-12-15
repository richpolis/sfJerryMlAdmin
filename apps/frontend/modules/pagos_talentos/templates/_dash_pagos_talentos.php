<?php foreach($detalles_cotizacion as $dc):?>

<?php 
    $dpts=$dc->getDetallesPagosTalentos();
    $importe=0;
    $totalDetalleCotizacion=$dc->getGananciaTalento();
    $pt=null;
    foreach($dpts as $dpt){
        if($dpt->getStatus()==PagosTalentosTable::$PAGOS_CALCULADOS){
            $importe+=$dpt->getImporte();
        }
        $pt=$dpt->getPagosTalentos();
    }
    
?>
<?php if(!$pt==null):?>
<div class="dash-registro" id="pago-talento-id-<?php echo $pt->getId()?>">
    <?php if(($totalDetalleCotizacion/2)<=$importe):?>
        <a href="<?php echo url_for("pagos_talentos_show",$pt)?>">
            <img src="/images/dashboard/check.png" class="image-status-dash-registro" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("pagos_talentos_show",$pt)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $pt->getTalentos();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $dc->getCotizaciones();?>"
    </div>
    <div class="status-dash-registro">
        <?php if(($totalDetalleCotizacion/2)<=$importe):?>
            Estado: <?php echo (($importe/$totalDetalleCotizacion)*100)."%"?> Anticipo
        <?php else:?>
            Estado: En Curso
        <?php endif;?>
    </div>
</div>
<?php endif; ?>
<?php endforeach;?>
