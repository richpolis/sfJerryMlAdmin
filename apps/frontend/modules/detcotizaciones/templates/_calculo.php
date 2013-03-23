<?php use_helper('Number') ?>
<?php
$gananciaJerryMl = $precio * ($margen_jerry_ml / 100);

$gananciaTalento = $precio - ($gananciaJerryMl);

$gananciaComisionistas = $gananciaTalento * ($margen_comisionistas / 100);
?>
<table width="100%">
    <tr>
        <td>Importe</td>
        <td style="text-align: right;"> <?php echo format_currency($precio, 'USD') ?></td>
    </tr>    
    <tr>
        <td>Talento</td>
        <td style="text-align: right;"> <?php echo format_currency($gananciaTalento, 'USD') ?></td>
    </tr>
    <tr>
        <td>Comisionistas</td>
        <td style="text-align: right;"> <?php echo format_currency($gananciaComisionistas, 'USD') ?></td>
    </tr>
    <tr style="border-top: 1px solid black;">
        <td>Pagar a talento</td>
        <td style="text-align: right;"> <?php echo format_currency($gananciaTalento-$gananciaComisionistas, 'USD') ?></td>
    </tr>
    <tr>
        <td>JerryML</td>
        <td style="text-align: right;"> <?php echo format_currency($gananciaJerryMl, 'USD') ?></td>
    </tr>
</table>