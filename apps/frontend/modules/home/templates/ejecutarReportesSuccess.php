<?php use_helper('I18N', 'Date','Number') ?>
<?php $confCotizacion=Doctrine_Core::getTable('Configuracion')->getSeccion("cotizaciones");?>

<div id="sf_admin_container" style="min-height: 400px;">
  <div id="sf_admin_header">
      <div style="text-align: right; width: 100%">
          <?php echo date("d/m/Y")?>
      </div>
      <img src="http://<?php echo $sf_request->getHost()?>/uploads/assets/<?php echo $confCotizacion->getImagen()?>" style="max-height: 100px; max-width: 100px;" />
  </div>
  <div  style="width:100%; height: 30px;">
          
  </div>
    <div class="contenedor-reporte">
        <h1 style="text-align: center"><?php echo __('Reporte Jerry ML', array(), 'messages') ?></h1>
        <p style="text-align: center; font-size: x-small; font-weight: bold;"><?php echo date("d/M/Y",  strtotime($desde))?> - <?php echo date("d/M/Y",  strtotime($hasta))?></p>
      
    
        <?php if($tipo=="jerryml"):?>
            <?php include_partial('reportePagosJerryml', array("cotizaciones"=>$cotizaciones)) ?>
        <?php elseif($tipo=="talentos"):?>
            <?php include_partial('reportePagosTalentos', array("talentos"=>$talentos)) ?>
        <?php elseif($tipo=="clientes"):?>
            <?php include_partial('reportePagosClientes', array("clientes"=>$clientes)) ?>
        <?php elseif($tipo=="jerryml-por-concepto"):?>
            <?php include_partial('reportePagosJerrymlPorConcepto', array("dccs"=>$dccs)) ?>
        <?php endif;?>
    </div>
  <div style="clear: both;"></div>
  <div id="sf_admin_footer" >
    <?php //include_partial('clientes/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
<div style="width:100%; height: 30px;">
    <a class="button" href="javascript:void(0);" id="pdf">Generar PDF</a>
    <a class="button" href="javascript:void(0);" id="excel">Generar Excel</a>
</div>
<script>
    $(document).ready(function(){
        $('#pdf').click(function(){
            var f = document.createElement('form');
            f.style.display = 'none';
            this.parentNode.appendChild(f);
            f.method = 'post';
            f.action = '<?php echo url_for('home/generarPdf')?>';
            var m = document.createElement('input');
            m.setAttribute('type', 'hidden');
            m.setAttribute('name', 'html');
            m.setAttribute('value', $('#sf_admin_container').html());
            f.appendChild(m);
            f.submit();
            return false;
        });
        $('#excel').click(function(){
            var f = document.createElement('form');
            f.style.display = 'none';
            this.parentNode.appendChild(f);
            f.method = 'post';
            f.action = '<?php echo url_for('home/generarExcel')?>';
            var m = document.createElement('input');
            m.setAttribute('type', 'hidden');
            m.setAttribute('name', 'html');
            m.setAttribute('value', $('.contenedor-reporte').html());
            f.appendChild(m);
            var m2 = document.createElement('input');
            m2.setAttribute('type', 'hidden');
            m2.setAttribute('name', 'tipo');
            m2.setAttribute('value', '<?php echo $tipo?>');
            f.appendChild(m2);
            f.submit();
            return false;
        });
    });
</script>