<?php use_helper('Escaping')?>

<table width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Empresa</th>
            <th>Cotizacion</th>
            <th>Importe Linea</th>
            <th>Talento $</th>
            <th>Jerryml $</th>
            <th>Intermediario $</th>
            <th>Pagado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="9">Total de detalles cotizacion: <?php echo count($detalles_cotizacion) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($detalles_cotizacion as $dc):?>
        <tr>
            <td><?php echo $dc->getId();?></td>
            <td><?php echo $dc->getCotizaciones()->getClientes();?></td>
            <td><?php echo $dc->getCotizaciones();?></td>
            <td><?php echo format_currency($dc->getPrecio(), 'USD') ?></td>
            <td><?php echo format_currency($dc->getGananciaTalento(), 'USD') ?></td>
            <td><?php echo format_currency($dc->getGananciaJerryml(), 'USD') ?></td>
            <td><?php echo format_currency($dc->getGananciaComisionista(), 'USD') ?></td>
            <td><?php echo ($dc->getIsPayTalento()?'Si': 'No') ?></td>
            <td>
                <ul class="sf_admin_td_actions">
                    <li class="sf_admin_action_show">
                       <?php echo link_to(__('Mostrar', array(), 'messages'), 'cotizaciones/show?id=' . $dc->getCotizaciones()->getId(), array()) ?>
                    </li>
                </ul>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>


