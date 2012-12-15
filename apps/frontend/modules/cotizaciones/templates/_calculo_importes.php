<?php use_helper('Number') ?>
<table border="0" width="100%">
  <tr>
    <th>Subtotal</th>
    <td><?php echo format_currency($cotizaciones->getSubtotal(), 'USD') ?></td>
  </tr>
  <tr>
    <th>IVA</th>
    <td><?php echo format_currency($cotizaciones->getIva(), 'USD') ?></td>
  </tr>
  <tr>
    <th>Total</th>
    <td><?php echo format_currency($cotizaciones->getTotal(), 'USD') ?></td>
  </tr>
</table>