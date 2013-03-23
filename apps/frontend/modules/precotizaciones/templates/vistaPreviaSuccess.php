<?php use_helper('I18N', 'Date') ?>
<?php use_helper('Escaping')?>
<?php use_helper('Number') ?>
<?php include_partial('precotizaciones/assets') ?>
<div>
    <a class="generarPDF" href="javascript:void(0);" id="pdf">Generar PDF</a>
</div>
<div  style="width:100%; height: 30px;">
          
</div>
<?php echo include_partial('vistaPrevia', array('precotizaciones'=>$precotizaciones,'detalles_precotizacion'=>$detalles_precotizacion))?>
<script>
    $(document).ready(function(){
         $('#pdf').button();
        
        $('#pdf').click(function(){
            var f = document.createElement('form');
            f.style.display = 'none';
            this.parentNode.appendChild(f);
            f.method = 'post';
            f.action = '<?php echo url_for('@generar_pdf_precotizacion?generar='.$precotizaciones->getId())?>';
            var m = document.createElement('input');
            m.setAttribute('type', 'hidden');
            m.setAttribute('name', 'html');
            m.setAttribute('value', $('#sf_admin_container').html());
            f.appendChild(m);
            f.submit();
            return false;
        });
    });
</script>