<form action="<?php echo url_for('contactos_collection', array('action' => 'filter')) ?>" method="POST">
    <table border="0">
        <thead>
            <tr>
                <th colspan="2">Busqueda</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cliente:<br/> <input type="text" name="busqueda_razon_social" value="" size="70" /></td>
                <td>Contacto:<br/> <input type="text" name="busqueda_contacto" value="" size="70" /></td>
            </tr>
            <tr>
                <td>Direccion:<br/> <input type="text" name="busqueda_direccion" value="" size="70" /></td>
                <td>RFC:<br/> <input type="text" name="busqueda_rfc" value="" size="70" /></td>
            </tr>
            <tr>
                <td><input type="submit" value="Buscar" name="busqueda_boton" /></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</form>
<?php if($sf_user->getSeleccionarContacto()):?>
<script type="text/javascript">
    $(document).ready(function(){
        $("input[type=checkbox]").hide();
    });

</script>
<?php endif; ?>
