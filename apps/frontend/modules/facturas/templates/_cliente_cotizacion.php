<!--apps/backend/modules/job/templates/_cliente_contacto.php -->
<div class="sf_admin_form_row">
    <?php $cotizaciones=$form->getObject()->getCotizaciones(); ?>
    <?php if($form->getObject()->getCotizacionId()>0): ?>
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td>
                    <label>Facturacion: </label>
                    <?php echo $cotizaciones->getEmpresas(); ?>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>
                    <label>Cliente: </label>
                    <?php echo $cotizaciones->getClientes(); ?>
                </td>
                <td>
                    <label>Cotizacion: </label>
                    <?php echo $cotizaciones; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
    <table border="0">
      <tr>
        <th><?php echo $form['cotizacion_id']->renderLabel() ?></th>  
        <td>
          <?php echo $form['cotizacion_id']->renderError() ?>
          <?php echo $form['cotizacion_id']->render() ?>
        </td> 
        
      </tr>
    </table>
    <?php endif; ?>
</div>

