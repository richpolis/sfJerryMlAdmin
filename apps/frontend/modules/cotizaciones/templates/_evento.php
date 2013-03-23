<!--apps/backend/modules/job/templates/_evento.php -->
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<div class="sf_admin_form_row" id="evento">
    <h2>Encabezado</h2>
    <table border="0" style="width: 100%">
        <tbody>
            <tr>
                <td colspan="2">
                    <label>Empresa Jerry ML: </label>
                    <?php
                    try {
                    if(!$cotizaciones->getEmpresaId()==null):
                        echo $cotizaciones->getEmpresas();
                    else:
                        echo "Seleccionar Empresa Jerry ML";
                    endif;
                    }catch(Exception $e){
                        echo $e->getMessage();
                    }
                    ?>
                </td>
                <td>
                    <label>Tipo cotizacion: </label>
                    <?php echo $cotizaciones->getTipoCotizacionString()?>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Cliente: </label>
                    <?php echo $cotizaciones->getClientes()?>
                </td>
                <td>
                    <label>Contacto: </label>
                    <?php echo $cotizaciones->getContactos()?>
                </td>
                <td>
                    <label>Personal Manager: </label>
                    <?php echo $cotizaciones->getManager()?>
                </td>
            </tr>
            <?php if($cotizaciones->getTipoCotizacion()==CotizacionesTable::$TIPO_COTIZACION_CAMPANA):?>
            <tr>
                <td>
                    <label>Descripcion: </label><?php echo $cotizaciones->getDescripcion()?>
                </td>
                <td>
                    <label>Dias de trabajo: </label>
                    <?php echo $cotizaciones->getActividad(ESC_RAW)?>
                </td>
                <td>
                    <label>Territorio: </label><?php echo $cotizaciones->getPlaza()?>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Vigencia: </label><?php echo $cotizaciones->getVigencia()?>
                </td>
                <td colspan="2">
                    <label>Medios: </label><?php echo $cotizaciones->getMedios()?>
                </td>
            </tr>
            <?php else:?>
            <tr>
                <td>
                    <label>Descripcion: </label><?php echo $cotizaciones->getDescripcion()?>
                </td>
                <td>
                    <label>Actividad: </label>
                    <?php echo $cotizaciones->getActividadLimpia(ESC_RAW)?>
                </td>
                <td>
                    <label>Plaza: </label><?php echo $cotizaciones->getPlaza()?>
                </td>
            </tr>
            <?php endif;?>
            <tr>
                <td>
                    <label>Comisionistas: </label>
                    <ul>
                    <?php foreach($cotizaciones->getCotizacionesComisionistas() as $cotco):?>
                        <li><?php echo $cotco->getComisionistas();?></li>
                    <?php endforeach;?>
                    </ul>    
                </td>
                <td>
                    <label>Conceptos: </label>
                    <ul>
                    <?php foreach($cotizaciones->getCotizacionesConceptos() as $cotc):?>
                        <li><?php echo $cotc->getConceptos();?></li>
                    <?php endforeach;?>
                    </ul>    
                </td>
                <td>
                    <label>Desde: </label><?php echo $cotizaciones->getFechaDesde("d/M/Y")?><br/>
                    <label>Hasta: </label><?php echo $cotizaciones->getFechaHasta("d/M/Y")?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
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