<?php use_helper('Number') ?>
<?php 
    $dps=$cotizacion->getDetallesPagos();
    $importe=0;
    $totalCotizacion=$cotizacion->getSubtotal();
    $pago=null;
    foreach($dps as $dp){
        if($dp->getStatus()==PagosTable::$PAGOS_CALCULADOS){
            $importe+=$dp->getImporte();
        }
        $pago=$dp->getPagos();
    }
?>
<?php if(!$pago==null):?>
<div class="dash-registro" id="pago-cotizacion-id-<?php echo $cotizacion->getId()?>">
    <?php if($totalCotizacion==$importe):?>
        <!--a href="#" onclick="$.quitarPagoCliente('<?php echo $cotizacion->getId()?>')"-->
        <a href="#">    
            <img src="/images/dashboard/check.png" class="image-status-dash-registro cierre" />
        </a>
    <?php else: //if($totalCotizacion<$importe):?>
        <a href="<?php echo url_for("pagos_show",$pago)?>">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro seguimiento" />
        </a>
    <?php endif?>
    <div class="titulo-dash-registro">
        <?php echo $pago->getClientes();?>
    </div>
    <div class="contenido-dash-registro">
        "<?php echo $cotizacion->getDescripcion();?>"
    </div>
    <div class="status-dash-registro">
        <?php echo "Pagado: ".format_currency($importe, 'USD')." adeuda: " .format_currency($totalCotizacion-$importe, 'USD') ; ?> 
    </div>
</div>
<?php endif;?>
