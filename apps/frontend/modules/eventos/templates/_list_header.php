<form action="<?php echo url_for('talentos_collection', array('action' => 'filter')) ?>" method="POST">
    <table border="0">
        <thead>
            <tr>
                <th colspan="2">Busqueda</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nombre:<br/> <input type="text" name="busqueda_name_completo" value="" size="70" /></td>
                <td>Representante:<br/> <input type="text" name="busqueda_representante" value="" size="70" /></td>
            </tr>
            <tr>
                <td><input type="submit" value="Buscar" name="busqueda_boton" /></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</form>

