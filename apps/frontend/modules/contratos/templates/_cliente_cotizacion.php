<!--apps/backend/modules/job/templates/_cliente_contacto.php -->
<div class="sf_admin_form_row">
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td>
                    <label>Cliente: </label>
                    <?php $cotizaciones=$form->getObject()->getCotizaciones(); ?>
                    <?php echo $cotizaciones->getClientes(); ?>
                </td>
                <td>
                    <label>Cotizacion: </label>
                    <?php echo $cotizaciones; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

