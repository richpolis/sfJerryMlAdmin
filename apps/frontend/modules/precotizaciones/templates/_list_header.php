<form action="<?php echo url_for('cotizaciones_collection', array('action' => 'filter')) ?>" method="POST">
    <table border="0">
        <thead>
            <tr>
                <th colspan="2">Busqueda</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cliente:<br/> <input type="text" name="busqueda_razon_social" value="" size="100" /></td>
                <td>Fecha Desde:<br/> <input type="text" name="busqueda_fecha_desde" value=""  /></td>
            </tr>
            <tr>
                <td>Contacto:<br/> <input type="text" name="busqueda_contacto" value="" size="100" /></td>
                <td>Fecha Hasta:<br/> <input type="text" name="busqueda_fecha_hasta" value="" /></td>
            </tr>
            <tr>
                <td><input type="submit" value="Buscar" name="busqueda_boton" /></td>
                <td></td>
            </tr>
        </tbody>
    </table>

</form>