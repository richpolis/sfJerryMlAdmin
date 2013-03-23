<?php use_helper('I18N', 'Date','Number') ?>
<?php include_partial('clientes/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Empresa:', array(), 'messages') ?> <?php echo $clientes;?></h1>
  <?php include_partial('clientes/flashes') ?>
  <div id="sf_admin_content">
      <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Empresa</a></li>
            <li><a href="#tabs-2">Contactos</a></li>
            <li><a href="#tabs-3">Cotizaciones</a></li>
            <li><a href="#tabs-4">Pagos</a></li>
        </ul>
        <div id="tabs-1">
            <table width="100%">
                <tr>
                    <td><label>ID</label><br/><?php echo $clientes->getId();?></td>
                    <td><label>Nombre Empresa</label><br/><?php echo $clientes->getRazonSocial();?></td>
                    <td><label>RFC</label><br/><?php echo $clientes->getRfc();?></td>
                </tr>
                <tr>
                    <td><label>Calle</label><br/><?php echo $clientes->getCalle();?></td>
                    <td><label>Numero exterior</label><br/><?php echo $clientes->getNumeroExterior();?></td>
                    <td><label>Numero interior</label><br/><?php echo $clientes->getNumeroInterior();?></td>
                </tr>
                <tr>
                    <td><label>Colonia</label><br/><?php echo $clientes->getColonia();?></td>
                    <td><label>Codigo Postal</label><br/><?php echo $clientes->getCodigoPostal();?></td>
                    <td><label>Cuidad</label><br/><?php echo $clientes->getCuidad();?></td>
                </tr>
                <tr>
                    <td><label>Municipio</label><br/><?php echo $clientes->getMunicipio();?></td>
                    <td><label>Estado</label><br/><?php echo $clientes->getEstado();?></td>
                    <td><label>Pais</label><br/><?php echo $clientes->getPais();?></td>
                </tr>
                <tr>
                    <td><label>Fecha de Creacion</label><br/><?php echo format_datetime($clientes->getCreatedAt(), 'g', 'es_CL') ;?></td>
                    <td><label>Fecha de Actualizacion</label><br/><?php echo format_datetime($clientes->getUpdatedAt(), 'g', 'es_CL');?></td>
                    <td><label>Saldo</label><br/><?php echo format_currency($clientes->getSaldo(), 'USD');?></td>
                </tr>
            </table>
        </div>
        <div id="tabs-2">
            <?php include_partial('contactos/list_contactos', array('contactos'=>$contactos))?>
        </div>
        <div id="tabs-3">
            <?php include_partial('cotizaciones/list_cotizaciones', array('cotizaciones'=>$cotizaciones))?>
        </div>
        <div id="tabs-4">
            <?php include_partial('pagos/list_pagos', array('pagos'=>$pagos))?>
        </div>
    </div>
      
      
  </div>
  <div id="sf_admin_footer">
    <?php //include_partial('clientes/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>