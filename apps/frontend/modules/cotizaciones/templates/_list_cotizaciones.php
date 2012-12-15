<?php use_helper('Escaping')?>

<table width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Empresa</th>
            <th>Contacto</th>
            <th>Personal Manager</th>
            <th>Titulo</th>
            <th>Importe sin iva</th>
            <th>Pagado</th>
            <th>Status</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="9">Total de cotizaciones: <?php echo count($cotizaciones) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($cotizaciones as $cotizacion):?>
        <tr>
            <td><?php echo $cotizacion->getId();?></td>
            <td><?php echo $cotizacion->getClientes();?></td>
            <td><?php echo $cotizacion->getContactos();?></td>
            <td><?php echo $cotizacion->getManager();?></td>
            <td><?php echo $cotizacion->getEvento();?></td>
            <td>
                <?php include_partial('cotizaciones/importe_sin_iva',array('cotizaciones'=>$cotizacion))?>
            </td>
            <td>
                <?php if($cotizacion->getIsPay()):?>
                    <img alt="Checked" title="Checked" src="/sfDoctrinePlugin/images/tick.png">
                <?php endif; ?>
            </td>
            <td><?php echo $cotizacion->getStringStatus();?></td>
            <td>
                <ul class="sf_admin_td_actions">
                    <li class="sf_admin_action_show">
                       <?php echo link_to(__('Mostrar', array(), 'messages'), 'cotizaciones/show?slug=' . $cotizacion->getSlug(), array()) ?>
                    </li>
                </ul>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>


