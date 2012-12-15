<!--apps/backend/modules/job/templates/_evento.php -->
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<div class="sf_admin_form_row" id="evento">
    <h2>Encabezado de Pago</h2>
    <table border="0" style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <label>Cliente: </label><br/>
                    <?php echo $pagos->getClientes()?>
                </td>
                <td>
                    <label>Referencia: </label><br/>
                    <?php echo $pagos->getReferencia()?>
                </td>
                <td>
                    <label>Lote: </label><br/>
                    <?php echo ($pagos->getIsCerrado()?"Cerrado":"Abierto")?>
                </td>
            </tr>
            <tr>
                        <td>
                            <label>Importe: </label><br/>
                            <?php echo format_currency($pagos->getImporte(), 'USD')?>
                        </td>
                        <td>
                            <label>IVA: </label><br/>
                            <?php echo format_currency($pagos->getIva(), 'USD')?>
                        </td>
                        <td>
                            <label>Saldo: </label><br/>
                            <?php echo format_currency($pagos->getAdeudo(), 'USD')?>
                        </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <a id="button-editar-encabezado" href="<?php echo url_for('pagos_edit',array('id'=>$pagos->getId()),array())?>" >Editar</a>
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