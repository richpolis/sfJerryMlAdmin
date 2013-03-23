<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php $talento = $detalles_precotizacion->getTalentos(); ?>
<?php $precotizacion = $detalles_precotizacion->getPrecotizaciones(); ?>
<?php if (!$sin_div): ?>
    <div id="detalles_precotizacion_<?php echo $detalles_precotizacion->getId() ?>">
    <?php endif; ?>    
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Artista:</th>
                <th>Información:</th>
                <th>Costo:</th>
            </tr>
        </thead> 
        <tbody>
            <tr>
                <td width="20%">
                    <?php echo $talento; ?><br/>
                    <img class="manejador" src="http://<?php echo $sf_request->getHost() ?>/uploads/talentos/<?php echo $talento->getImagen() ?>" style="max-height: 100px; max-width: 100px;" width="100" height="100"/>

                </td>  
                <td width="60%"><?php echo $detalles_precotizacion->getActividad(ESC_RAW) ?></td>
                <td width="20%">
                    <?php
                    $precio = $detalles_precotizacion->getPrecio();
                    $margenJerry = $detalles_precotizacion->getMargenJerryMl() / 100;
                    $gananciaJerryMl = $precio * $margenJerry;
                    $gananciaTalento = $precio - $gananciaJerryMl;
                    ?>
                    <table width="100%">
                        <tr>
                            <td>Importe</td>
                            <td style=" text-align: right;">  <?php echo format_currency($precio, 'USD') ?></td>
                        </tr>
                        <tr>
                            <td>Talento</td>
                            <td style=" text-align: right;">  <?php echo format_currency($gananciaTalento, 'USD') ?></td>
                        </tr>
                        <tr>
                            <td>Jerry ML</td>
                            <td style=" text-align: right;">  <?php echo format_currency($gananciaJerryMl, 'USD') ?></td>
                        </tr>
                    </table>    
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <?php if($precotizacion->getStatus()<PrecotizacionesTable::$APROBADA):?>
                    &nbsp; <input type="button" value="Editar" class="buttonEditar" onclick="$.editarTalento('<?php echo $detalles_precotizacion->getId() ?>');" />
                    <?php else:?>
                    &nbsp;
                    <?php endif; ?>
                </td>  
            </tr>
        </tfoot>
    </table>
<?php if (!$sin_div): ?>    
    </div>
<?php endif; ?>
<script>
    $(document).ready(function(){
       $("input:submit, input:button").button(); 
    });
</script>