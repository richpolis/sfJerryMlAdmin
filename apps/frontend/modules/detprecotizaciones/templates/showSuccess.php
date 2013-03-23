<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $detalles_precotizacion->getId() ?></td>
    </tr>
    <tr>
      <th>Cotizacion:</th>
      <td><?php echo $detalles_precotizacion->getCotizacionId() ?></td>
    </tr>
    <tr>
      <th>Talento:</th>
      <td><?php echo $detalles_precotizacion->getTalentoId() ?></td>
    </tr>
    <tr>
      <th>Actividad:</th>
      <td><?php echo $detalles_precotizacion->getActividad() ?></td>
    </tr>
    <tr>
      <th>Precio:</th>
      <td><?php echo $detalles_precotizacion->getPrecio() ?></td>
    </tr>
    <tr>
      <th>Is active:</th>
      <td><?php echo $detalles_precotizacion->getIsActive() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $detalles_precotizacion->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $detalles_precotizacion->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('detcotizaciones/edit?id='.$detalles_precotizacion->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('detcotizaciones/index') ?>">List</a>
