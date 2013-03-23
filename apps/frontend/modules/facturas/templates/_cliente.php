<?php $cotizaciones=$facturas->getCotizaciones(); ?>
<?php 
if(!$cotizaciones==null):
    echo $cotizaciones->getClientes();
else:
    echo "Sin cotizacion";
endif;
?>
