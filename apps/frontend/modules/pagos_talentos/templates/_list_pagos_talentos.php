<?php use_helper('Escaping')?>
<?php use_helper('Number')?>
<table width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Referencia</th>
            <th>Cuenta deposito</th>
            <th>Importe</th>
            <th>IVA</th>
            <th>ISR</th>
            <th>Saldo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="8">Lotes de pagos talentos: <?php echo count($pagos_talentos) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($pagos_talentos as $pago):?>
        <tr>
            <td><?php echo $pago->getId();?></td>
            <td><?php echo $pago->getReferencia();?></td>
            <td><?php echo $pago->getCuentaDeposito();?></td>
            <td><?php echo format_currency($pago->getImporte(), 'USD') ?></td>
            <td><?php echo format_currency($pago->getIva(), 'USD') ?></td>
            <td><?php echo format_currency($pago->getIsr(), 'USD') ?></td>
            <td><?php echo format_currency($pago->getAdeudo(), 'USD') ?></td>
            <td>
                <ul class="sf_admin_td_actions">
                    <li class="sf_admin_action_show">
                       <?php echo link_to(__('Mostrar', array(), 'messages'), 'pagos_talentos/show?id=' . $pago->getId(), array()) ?>
                    </li>
                </ul>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>


