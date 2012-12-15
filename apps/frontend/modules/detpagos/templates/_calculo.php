<?php use_helper('Number') ?>

<?php
$gananciaTalento = $precio;
$margenJerry = $margen_jerry_ml / 100;
$gananciaJerryMl = $gananciaTalento * $margenJerry;
$subtotal = $gananciaTalento + $gananciaJerryMl;

if ($margen_comisionista > 0) {
    $margenComisionista = $margen_comisionista / 100;
    $gananciaComisionista = $gananciaJerryMl * $margenComisionista;
    $gananciaJerryMl-=$gananciaComisionista;
} else {
    $margenComisionista = 0;
    $gananciaComisionista = 0;
}
?>
<table width="100%">
<tr>
   <td>$ Talento <?php echo format_currency($gananciaTalento, 'USD') ?></td>
</tr>
<tr>
   <td>% Margen <?php echo ($margenJerry * 100) . "%" ?></td>
</tr>
<tr>
    <td>$ Subtotal <?php echo format_currency($subtotal, 'USD') ?></td>
</tr>
<tr>
    <td>$ JerryMl <?php echo format_currency($gananciaJerryMl, 'USD') ?></td>
</tr>
<?php if ($gananciaComisionista > 0): ?>
<tr>
    <td>$ Comisionista <?php echo format_currency($gananciaComisionista, 'USD') ?></td>
</tr>
<?php endif; ?>
</table>