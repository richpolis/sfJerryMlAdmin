<?php use_helper('Escaping')?>

<table width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Evento</th>
            <th>Descripcion</th>
            <th>Inicia</th>
            <th>Termina</th>
            <th>Status</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="6">Total de eventos: <?php echo count($eventos) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($eventos as $evento):?>
        <tr>
            <td><?php echo $evento->getId();?></td>
            <td><?php echo $evento->getSubject();?></td>
            <td><?php echo $evento->getDescription(ESC_RAW);?></td>
            <td><?php echo date("d/M/Y g:i a",strtotime($evento->getStartTime()));?></td>
            <td><?php echo date("d/M/Y g:i a",strtotime($evento->getEndTime()));?></td>
            
            <td><?php echo $evento->getStatusString();?></td>
     
        </tr>
<?php endforeach;?>
    </tbody>
</table>


