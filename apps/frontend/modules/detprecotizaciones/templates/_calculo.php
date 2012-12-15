<?php use_helper('Number') ?>
<?php
$gananciaJerryml = $precio*($margen_jerry_ml / 100);
$gananciaTalento = $precio-$gananciaJerryml;
?>
<table width="100%">
<tr>
<td>Importe <?php echo format_currency($precio, 'USD') ?></td>
</tr>    
<tr>
<td>Talento <?php echo format_currency($gananciaTalento, 'USD') ?></td>
</tr>
<tr>
<td>JerryMl <?php echo format_currency($gananciaJerryml, 'USD') ?></td>
</tr>
</table>