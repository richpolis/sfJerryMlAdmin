<?php use_helper('Escaping')?>
<?php use_helper('Number')?>
<table width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Referencia</th>
            <th>Importe</th>
            <th>IVA</th>
            <th>Saldo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="7">Lotes de pagos: <?php echo count($pagos) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($pagos as $pago):?>
        <tr>
            <td><?php echo $pago->getId();?></td>
            <td><?php echo $pago->getReferencia();?></td>
            <td><?php echo format_currency($pago->getImporte(), 'USD') ?></td>
            <td><?php echo format_currency($pago->getIva(), 'USD') ?></td>
            <td><?php echo format_currency($pago->getAdeudo(), 'USD') ?></td>
            <td>
                <ul class="sf_admin_td_actions">
                    <li class="sf_admin_action_show">
                       <?php echo link_to(__('Mostrar', array(), 'messages'), 'pagos/show?id=' . $pago->getId(), array()) ?>
                    </li>
                </ul>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>


