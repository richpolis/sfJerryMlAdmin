<?php use_helper('Escaping')?>
<p>
    <strong> NOTA: Los costos son exclusivos para este evento en las actividades mencionadas y no se podr&aacute; ligar al artista con otras marcas ni empresas fuera de lo que se indica en esta cotizaci&oacute;n. 
    </strong>
</p>
<?php echo $configuracion->getContenido(ESC_RAW); ?>

<?php if(strlen($cotizaciones->getRequerimientos())>10):?>
    <h3>Requerimientos</h3>
    <?php echo $cotizaciones->getRequerimientos(ESC_RAW); ?>
<?php endif; ?>
    
<p>
    <br /> 
    <strong>
        Costos vigentes 10 d&iacute;as a partir de la fecha del documento.
    </strong>
</p>
    