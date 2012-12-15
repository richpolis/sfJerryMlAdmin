<?php use_helper('I18N', 'Date','Number') ?>
<?php include_partial('talentos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Talento:', array(), 'messages') ?> <?php echo $talentos;?></h1>
  <?php include_partial('talentos/flashes') ?>
  <div id="sf_admin_content">
      <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Talento</a></li>
            <li><a href="#tabs-2">Detalles Cotizacion</a></li>
            <li><a href="#tabs-3">Pagos</a></li>
            <li><a href="#tabs-4">Eventos</a></li>
        </ul>
        <div id="tabs-1">
            <table width="100%">
                <tr>
                    <td><label>ID</label><br/><?php echo $talentos->getId();?></td>
                    <td><label>Nombre</label><br/><?php echo $talentos->getName();?></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label>Calle</label><br/><?php echo $talentos->getCalle();?></td>
                    <td><label>Numero exterior</label><br/><?php echo $talentos->getNumeroExterior();?></td>
                    <td><label>Numero interior</label><br/><?php echo $talentos->getNumeroInterior();?></td>
                </tr>
                <tr>
                    <td><label>Colonia</label><br/><?php echo $talentos->getColonia();?></td>
                    <td><label>Codigo Postal</label><br/><?php echo $talentos->getCodigoPostal();?></td>
                    <td><label>Cuidad</label><br/><?php echo $talentos->getCuidad();?></td>
                </tr>
                <tr>
                    <td><label>Municipio</label><br/><?php echo $talentos->getMunicipio();?></td>
                    <td><label>Estado</label><br/><?php echo $talentos->getEstado();?></td>
                    <td><label>Pais</label><br/><?php echo $talentos->getPais();?></td>
                </tr>
                <tr>
                    <td><label>Fecha de Creacion</label><br/><?php echo format_datetime($talentos->getCreatedAt(), 'g', 'es_CL') ;?></td>
                    <td><label>Fecha de Actualizacion</label><br/><?php echo format_datetime($talentos->getUpdatedAt(), 'g', 'es_CL');?></td>
                    <td><label>Saldo</label><br/><?php echo format_currency($talentos->getSaldo(), 'USD');?></td>
                </tr>
            </table>
        </div>
        <div id="tabs-2">
            <?php include_partial('cotizaciones/list_detalles_cotizacion', array('detalles_cotizacion'=>$detalles_cotizacion))?>
        </div>
        <div id="tabs-3">
            <?php include_partial('pagos_talentos/list_pagos_talentos', array('pagos_talentos'=>$pagos_talentos))?>
        </div>
        <div id="tabs-4">
            <?php include_partial('eventos/list_eventos', array('eventos'=>$eventos))?>
        </div>
    </div>
      
      
  </div>
  <div id="sf_admin_footer">
    <?php //include_partial('talentos/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>