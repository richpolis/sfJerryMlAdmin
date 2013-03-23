<!--apps/backend/modules/job/templates/_evento.php -->
<?php use_helper('Escaping')?>
<div class="sf_admin_form_row" id="pie_evento">
    <h2>Pie de cotizacion</h2>
    <table border="0" style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <label>Requerimientos: </label>
                    <?php echo $cotizaciones->getRequerimientos(ESC_RAW)?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <?php if(!$cotizaciones->statusAprobada()):?>
                    <a id="button-editar-pie" href="<?php echo url_for('cotizaciones_edit',array('id'=>$cotizaciones->getId()),array())?>" >Editar</a>
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
    $("#button-editar-pie").button();
});
</script>