<h1>Detalles cotizacions List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Cotizacion</th>
      <th>Talento</th>
      <th>Actividad</th>
      <th>Precio</th>
      <th>Is active</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($detalles_precotizacions as $detalles_precotizacion): ?>
    <tr>
      <td><a href="<?php echo url_for('detcotizaciones/show?id='.$detalles_precotizacion->getId()) ?>"><?php echo $detalles_precotizacion->getId() ?></a></td>
      <td><?php echo $detalles_precotizacion->getCotizacionId() ?></td>
      <td><?php echo $detalles_precotizacion->getTalentoId() ?></td>
      <td><?php echo $detalles_precotizacion->getActividad() ?></td>
      <td><?php echo $detalles_precotizacion->getPrecio() ?></td>
      <td><?php echo $detalles_precotizacion->getIsActive() ?></td>
      <td><?php echo $detalles_precotizacion->getCreatedAt() ?></td>
      <td><?php echo $detalles_precotizacion->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('detcotizaciones/new') ?>">New</a>
