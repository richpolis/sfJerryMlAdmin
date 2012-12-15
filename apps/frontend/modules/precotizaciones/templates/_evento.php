<!--apps/backend/modules/job/templates/_evento.php -->
<?php use_helper('Escaping')?>
<div class="sf_admin_form_row" id="evento">
    <h2>Encabezado</h2>
    <table border="0" style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <label>Cliente: </label>
                    <?php echo $precotizaciones->getClientes()?>
                </td>
                <td>
                    <label>Contacto: </label>
                    <?php echo $precotizaciones->getContactos()?>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <label>Evento: </label>
                    <?php echo $precotizaciones->getEvento()?>
                </td>
                <td>
                    <label>Lugar: </label>
                    <?php echo $precotizaciones->getLugarEvento(ESC_RAW)?>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <label>Inicia: </label>
                    <?php echo sfRichSys::getStringDateTime($precotizaciones->getIniciaEvento())?>
                </td>
                <td>
                    <label>Termina: </label>
                    <?php echo sfRichSys::getStringDateTime($precotizaciones->getTerminaEvento())?>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Descripcion: </label>
                    <?php echo $precotizaciones->getDescripcion(ESC_RAW)?>
                </td>
                <td>
                    <label>Actividad General: </label>
                    <?php echo $precotizaciones->getActividadGeneral(ESC_RAW)?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <?php if($precotizaciones->getStatus()<PrecotizacionesTable::$APROBADA):?>
                    <a id="button-editar-encabezado" href="<?php echo url_for('precotizaciones_edit',array('id'=>$precotizaciones->getId()),array())?>" >Editar</a>
                    <?php else:?>
                    &nbsp;
                    <?php endif;?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<script>
$(document).ready(function(){
    $("#button-editar-encabezado").button();
});
</script>