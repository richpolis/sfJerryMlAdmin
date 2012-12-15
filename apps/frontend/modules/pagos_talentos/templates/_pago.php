<!--apps/backend/modules/job/templates/_evento.php -->
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<div class="sf_admin_form_row" id="evento">
    <h2>Encabezado de Pago</h2>
    <table border="0" style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <label>Talento: </label><br/>
                    <?php echo $pagos_talentos->getTalentos()?>
                </td>
                <td>
                    <label>Referencia: </label><br/>
                    <?php echo $pagos_talentos->getReferencia()?>
                </td>
                <td>
                    <label>Cuenta de Deposito: </label><br/>
                    <?php echo $pagos_talentos->getCuentaDeposito()?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table width="100%">
                        <tr>
                            <td>
                                Importe:<br/>
                                <?php echo format_currency($pagos_talentos->getImporte(), 'USD') ?>
                            </td>
                            <td>
                                IVA: <br/>
                                <?php echo format_currency($pagos_talentos->getIva(), 'USD') ?>
                            </td>
                            <td>
                                ISR: <br/>
                                <?php echo format_currency($pagos_talentos->getIsr(), 'USD') ?>
                            </td>
                            <td>
                                Saldo: <br/>
                                <?php echo format_currency($pagos_talentos->getAdeudo(), 'USD') ?>
                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <a id="button-editar-encabezado" href="<?php echo url_for('pagos_talentos_edit',array('id'=>$pagos_talentos->getId()),array())?>" >Editar</a>
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