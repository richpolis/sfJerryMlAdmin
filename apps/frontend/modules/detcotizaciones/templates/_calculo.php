<?php use_helper('Number') ?>
<?php
$gananciaJerryml = $precio*($margen_jerry_ml / 100);
$gananciaTalento = $precio-$gananciaJerryml;
if ($margen_comisionista > 0) {
    $gananciaComisionista = $gananciaJerryml*($margen_comisionista / 100);
} else {
    $gananciaComisionista = 0;
}
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
<?php if ($gananciaComisionista > 0): ?>
<tr>
<td>Comisionista <?php echo format_currency($gananciaComisionista, 'USD') ?></td>
</tr>
<?php endif; ?>
</table>