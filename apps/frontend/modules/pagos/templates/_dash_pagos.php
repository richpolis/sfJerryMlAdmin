<?php foreach($cotizaciones as $cotizacion):?>

<?php 
    $dps=$cotizacion->getDetallesPagos();
    $importe=0;
    $totalCotizacion=$cotizacion->getTotal();
    $pago=null;
    foreach($dps as $dp){
        if($dp->getStatus()==PagosTable::$PAGOS_CALCULADOS){
            $importe+=$dp->getImporte();
        }
        $pago=$dp->getPagos();
    }
    
?>
<?php if(!$pago==null):?>
<div class="dash-registro" id="pago-id-<?php echo $pago->getId()?>">
    <?php if(($totalCotizacion/2)<=$importe):?>
        <a href="<?php echo url_for("pagos_show",$pago)?>">
            <img src="/images/dashboard/check.png" class="image-status-dash-registro" />
        </a>
    <?php else:?>
        <a href="<?php echo url_for("pagos_show",$pago)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro"/>
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $pago->getClientes();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $cotizacion->getEvento();?>"
    </div>
    <div class="status-dash-registro">
        <?php if(($totalCotizacion/2)<=$importe):?>
            Estado 50% Anticipo
        <?php else:?>
            Estado En Curso
        <?php endif;?>
    </div>
</div>
<?php endif;?>
<?php endforeach;?>
