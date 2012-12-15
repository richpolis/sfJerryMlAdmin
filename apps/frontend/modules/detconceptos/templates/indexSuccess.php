<h1>Detalles cotizacion conceptoss List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Detalles cotizacion</th>
      <th>Concepto</th>
      <th>Precio</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($detalles_cotizacion_conceptoss as $detalles_cotizacion_conceptos): ?>
    <tr>
      <td><a href="<?php echo url_for('detconceptos/edit?id='.$detalles_cotizacion_conceptos->getId()) ?>"><?php echo $detalles_cotizacion_conceptos->getId() ?></a></td>
      <td><?php echo $detalles_cotizacion_conceptos->getDetallesCotizacionId() ?></td>
      <td><?php echo $detalles_cotizacion_conceptos->getConceptoId() ?></td>
      <td><?php echo $detalles_cotizacion_conceptos->getPrecio() ?></td>
      <td><?php echo $detalles_cotizacion_conceptos->getCreatedAt() ?></td>
      <td><?php echo $detalles_cotizacion_conceptos->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('detconceptos/new') ?>">New</a>
