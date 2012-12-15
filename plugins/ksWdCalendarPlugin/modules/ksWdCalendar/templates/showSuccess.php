<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ks_wc_event->getId() ?></td>
    </tr>
    <tr>
      <th>Evento:</th>
      <td><?php echo $ks_wc_event->getSubject() ?></td>
    </tr>
    <tr>
      <th>Descripcion:</th>
      <td><?php echo $ks_wc_event->getDescription() ?></td>
    </tr>
    <tr>
      <th>Inicia:</th>
      <td><?php echo date("d/M/Y g:i a",strtotime($ks_wc_event->getStartTime())) ?></td>
    </tr>
    <tr>
      <th>Termina:</th>
      <td><?php echo date("d/M/Y g:i a",strtotime($ks_wc_event->getEndTime())) ?></td>
    </tr>
    <tr>
      <th>Todo el dia:</th>
      <td><?php echo ($ks_wc_event->getIsAllDayEvent()?"Si":"No") ?></td>
    </tr>
    <tr>
      <th>Color:</th>
      <td><?php echo $ks_wc_event->getColor() ?></td>
    </tr>
    <tr>
      <th>Recurring rule:</th>
      <td><?php echo $ks_wc_event->getRecurringRule() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('sfCalendar/edit?id='.$ks_wc_event->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('sfCalendar/index') ?>">List</a>
