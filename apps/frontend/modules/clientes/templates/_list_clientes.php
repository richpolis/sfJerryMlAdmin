<table width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Empresa</th>
            <th>RFC</th>
            <th>Activo?</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="6">Total de Empresas: <?php echo count($clientes) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($clientes as $cliente):?>
        <tr>
            <td><?php echo $cliente->getId();?></td>
            <td><?php echo $cliente->getRazonSocial();?></td>
            <td><?php echo $cliente->getRfc();?></td>
            <td><?php echo $cliente->getIsActive();?></td>
            <td>
                <ul class="sf_admin_td_actions">
                    <li class="sf_admin_action_show">
                       <?php echo link_to(__('Mostrar', array(), 'messages'), 'clientes/show?slug=' . $cliente->getSlug(), array()) ?>
                    </li>
                    <li class="sf_admin_action_Editar">
                       <?php echo link_to(__('Editar', array(), 'messages'), 'clientes/edit?slug=' . $cliente->getSlug(), array()) ?>
                    </li>
                </ul>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>


