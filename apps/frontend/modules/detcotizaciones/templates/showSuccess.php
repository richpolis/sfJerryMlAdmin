<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $detalles_cotizacion->getId() ?></td>
    </tr>
    <tr>
      <th>Cotizacion:</th>
      <td><?php echo $detalles_cotizacion->getCotizacionId() ?></td>
    </tr>
    <tr>
      <th>Talento:</th>
      <td><?php echo $detalles_cotizacion->getTalentoId() ?></td>
    </tr>
    <tr>
      <th>Actividad:</th>
      <td><?php echo $detalles_cotizacion->getActividad() ?></td>
    </tr>
    <tr>
      <th>Precio:</th>
      <td><?php echo $detalles_cotizacion->getPrecio() ?></td>
    </tr>
    <tr>
      <th>Is active:</th>
      <td><?php echo $detalles_cotizacion->getIsActive() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $detalles_cotizacion->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $detalles_cotizacion->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('detcotizaciones/edit?id='.$detalles_cotizacion->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('detcotizaciones/index') ?>">List</a>
