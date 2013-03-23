<?php use_helper('Escaping')?>
<?php if($sf_user->getSeleccionarCotizaciones()): ?>

<?php if(count($sf_user->getCotizaciones())>0): ?>
<br/>
<br/>

<h2>Cotizaciones Seleccionados</h2>
<table style="width: 100%;">
    <tr>
        <th>Id</th>
            <th>Empresa</th>
            <th>Contacto</th>
            <th>Personal Manager</th>
            <th>Descripcion</th>
            <th>Actividad</th>
            <th>Status</th>
        <th>
            Acciones
        </th>
    </tr>
    <?php foreach($cotizacionesSeleccionadas as $cotizacion):?>
    <tr>
        <td>
            <?php echo $cotizacion->getId();?>
        </td>
        <td>
            <?php echo $cotizacion->getClientes();?>
        </td>
        <td>
            <?php echo $cotizacion->getContactos();?>
        </td>
        <td>
            <?php echo $cotizacion->getManager()?>
        </td>
        <td>
            <?php echo $cotizacion->getDescripcion()?>
        </td>
        <td>
            <?php echo $cotizacion->getActividad(ESC_RAW)?>
        </td>
        <td>
            <?php echo $cotizacion->getStringStatus()?>
        </td>
        <td>
            <ul class="sf_admin_td_actions">
               <li class="sf_admin_action_select">
                    <?php echo link_to(__('Quitar Seleccion', array(), 'messages'), 'cotizaciones/ListRemove?id=' . $cotizacion->getId(), array()) ?>
               </li>
            </ul>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php endif; ?>
