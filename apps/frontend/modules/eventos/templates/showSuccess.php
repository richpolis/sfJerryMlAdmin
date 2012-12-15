<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ks_wc_event->getId() ?></td>
    </tr>
    <tr>
      <th>Talento:</th>
      <td><?php echo $ks_wc_event->getTalentoId() ?></td>
    </tr>
    <tr>
      <th>Subject:</th>
      <td><?php echo $ks_wc_event->getSubject() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $ks_wc_event->getDescription() ?></td>
    </tr>
    <tr>
      <th>Start time:</th>
      <td><?php echo $ks_wc_event->getStartTime() ?></td>
    </tr>
    <tr>
      <th>End time:</th>
      <td><?php echo $ks_wc_event->getEndTime() ?></td>
    </tr>
    <tr>
      <th>Is all day event:</th>
      <td><?php echo $ks_wc_event->getIsAllDayEvent() ?></td>
    </tr>
    <tr>
      <th>Color:</th>
      <td><?php echo $ks_wc_event->getColor() ?></td>
    </tr>
    <tr>
      <th>Recurring rule:</th>
      <td><?php echo $ks_wc_event->getRecurringRule() ?></td>
    </tr>
    <tr>
      <th>Calle:</th>
      <td><?php echo $ks_wc_event->getCalle() ?></td>
    </tr>
    <tr>
      <th>Numero exterior:</th>
      <td><?php echo $ks_wc_event->getNumeroExterior() ?></td>
    </tr>
    <tr>
      <th>Numero interior:</th>
      <td><?php echo $ks_wc_event->getNumeroInterior() ?></td>
    </tr>
    <tr>
      <th>Colonia:</th>
      <td><?php echo $ks_wc_event->getColonia() ?></td>
    </tr>
    <tr>
      <th>Codigo postal:</th>
      <td><?php echo $ks_wc_event->getCodigoPostal() ?></td>
    </tr>
    <tr>
      <th>Cuidad:</th>
      <td><?php echo $ks_wc_event->getCuidad() ?></td>
    </tr>
    <tr>
      <th>Municipio:</th>
      <td><?php echo $ks_wc_event->getMunicipio() ?></td>
    </tr>
    <tr>
      <th>Estado:</th>
      <td><?php echo $ks_wc_event->getEstado() ?></td>
    </tr>
    <tr>
      <th>Pais:</th>
      <td><?php echo $ks_wc_event->getPais() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('eventos/edit?id='.$ks_wc_event->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('eventos/index') ?>">List</a>
