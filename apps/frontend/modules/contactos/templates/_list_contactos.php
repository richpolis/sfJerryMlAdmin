<table width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Celular</th>
            <th>Email</th>
            <th>Activo?</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="6">Total de Contactos: <?php echo count($contactos) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach($contactos as $contacto):?>
        <tr>
            <td><?php echo $contacto->getNombreCompleto();?></td>
            <td><?php echo $contacto->getTelefono();?></td>
            <td><?php echo $contacto->getCelular();?></td>
            <td><?php echo $contacto->getEmail();?></td>
            <td>
                <?php if($contacto->getIsActive()):?>
                    <img alt="Checked" title="Checked" src="/sfDoctrinePlugin/images/tick.png">
                <?php endif; ?>
            </td>
            <td>
                <ul class="sf_admin_td_actions">
                    <li class="sf_admin_action_show">
                       <?php echo link_to(__('Mostrar', array(), 'messages'), 'contactos/show?slug=' . $contacto->getSlug(), array()) ?>
                    </li>
                    <li class="sf_admin_action_Editar">
                       <?php echo link_to(__('Editar', array(), 'messages'), 'contactos/edit?slug=' . $contacto->getSlug(), array()) ?>
                    </li>
                </ul>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>


