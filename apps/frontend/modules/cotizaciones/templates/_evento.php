<!--apps/backend/modules/job/templates/_evento.php -->
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<div class="sf_admin_form_row" id="evento">
    <h2>Encabezado</h2>
    <table border="0" style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <label>Cliente: </label>
                    <?php echo $cotizaciones->getClientes()?>
                </td>
                <td>
                    <label>Contacto: </label>
                    <?php echo $cotizaciones->getContactos()?>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <label>Titulo: </label>
                    <?php echo $cotizaciones->getEvento()?>
                </td>
                <td>
                    <label>Descripcion: </label>
                    <?php echo $cotizaciones->getDescripcion(ESC_RAW)?>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Personal Manager: </label>
                    <?php echo $cotizaciones->getManager()?>
                </td>
                <td>
                    <label>Intermediario: </label>
                    <?php echo $cotizaciones->getComisionista()?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <?php if(!$cotizaciones->statusAprobada()):?>
                    <a id="button-editar-encabezado" href="<?php echo url_for('cotizaciones_edit',array('id'=>$cotizaciones->getId()),array())?>" >Editar</a>
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