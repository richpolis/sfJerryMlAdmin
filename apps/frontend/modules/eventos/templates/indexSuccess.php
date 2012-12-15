<h1>Ks wc events List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Talento</th>
      <th>Subject</th>
      <th>Description</th>
      <th>Start time</th>
      <th>End time</th>
      <th>Is all day event</th>
      <th>Color</th>
      <th>Recurring rule</th>
      <th>Calle</th>
      <th>Numero exterior</th>
      <th>Numero interior</th>
      <th>Colonia</th>
      <th>Codigo postal</th>
      <th>Cuidad</th>
      <th>Municipio</th>
      <th>Estado</th>
      <th>Pais</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ks_wc_events as $ks_wc_event): ?>
    <tr>
      <td><a href="<?php echo url_for('eventos/show?id='.$ks_wc_event->getId()) ?>"><?php echo $ks_wc_event->getId() ?></a></td>
      <td><?php echo $ks_wc_event->getTalentoId() ?></td>
      <td><?php echo $ks_wc_event->getSubject() ?></td>
      <td><?php echo $ks_wc_event->getDescription() ?></td>
      <td><?php echo $ks_wc_event->getStartTime() ?></td>
      <td><?php echo $ks_wc_event->getEndTime() ?></td>
      <td><?php echo $ks_wc_event->getIsAllDayEvent() ?></td>
      <td><?php echo $ks_wc_event->getColor() ?></td>
      <td><?php echo $ks_wc_event->getRecurringRule() ?></td>
      <td><?php echo $ks_wc_event->getCalle() ?></td>
      <td><?php echo $ks_wc_event->getNumeroExterior() ?></td>
      <td><?php echo $ks_wc_event->getNumeroInterior() ?></td>
      <td><?php echo $ks_wc_event->getColonia() ?></td>
      <td><?php echo $ks_wc_event->getCodigoPostal() ?></td>
      <td><?php echo $ks_wc_event->getCuidad() ?></td>
      <td><?php echo $ks_wc_event->getMunicipio() ?></td>
      <td><?php echo $ks_wc_event->getEstado() ?></td>
      <td><?php echo $ks_wc_event->getPais() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eventos/new') ?>">New</a>
