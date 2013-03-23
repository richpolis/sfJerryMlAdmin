<h1>Cotizaciones conceptoss List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Cotizacion</th>
      <th>Concepto</th>
      <th>Precio</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cotizaciones_conceptoss as $cotizaciones_conceptos): ?>
    <tr>
      <td><a href="<?php echo url_for('cotconceptos/edit?id='.$cotizaciones_conceptos->getId()) ?>"><?php echo $cotizaciones_conceptos->getId() ?></a></td>
      <td><?php echo $cotizaciones_conceptos->getCotizacionId() ?></td>
      <td><?php echo $cotizaciones_conceptos->getConceptoId() ?></td>
      <td><?php echo $cotizaciones_conceptos->getPrecio() ?></td>
      <td><?php echo $cotizaciones_conceptos->getCreatedAt() ?></td>
      <td><?php echo $cotizaciones_conceptos->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('cotconceptos/new') ?>">New</a>
