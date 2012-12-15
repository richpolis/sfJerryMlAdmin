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
    <?php foreach ($detalles_cotizacions as $detalles_cotizacion): ?>
    <tr>
      <td><a href="<?php echo url_for('detcotizaciones/show?id='.$detalles_cotizacion->getId()) ?>"><?php echo $detalles_cotizacion->getId() ?></a></td>
      <td><?php echo $detalles_cotizacion->getCotizacionId() ?></td>
      <td><?php echo $detalles_cotizacion->getTalentoId() ?></td>
      <td><?php echo $detalles_cotizacion->getActividad() ?></td>
      <td><?php echo $detalles_cotizacion->getPrecio() ?></td>
      <td><?php echo $detalles_cotizacion->getIsActive() ?></td>
      <td><?php echo $detalles_cotizacion->getCreatedAt() ?></td>
      <td><?php echo $detalles_cotizacion->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('detcotizaciones/new') ?>">New</a>
