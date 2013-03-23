<?php use_helper('Number') ?>
<?php
$gananciaJerryMl = $precio * ($margen_jerry_ml / 100);
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
        <td>JerryMl</td>
        <td style=" text-align: right;">  <?php echo format_currency($gananciaJerryMl, 'USD') ?></td>
    </tr>
</table>