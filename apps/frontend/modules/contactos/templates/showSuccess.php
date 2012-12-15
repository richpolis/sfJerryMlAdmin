<?php use_helper('I18N', 'Date','Number') ?>
<?php include_partial('contactos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Contacto:', array(), 'messages') ?> <?php echo $contactos;?></h1>
  <?php include_partial('contactos/flashes') ?>
  <div id="sf_admin_content">
      <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Contacto</a></li>
            <li><a href="#tabs-2">Empresas</a></li>
            <li><a href="#tabs-3">Cotizaciones</a></li>
            <li><a href="#tabs-4">Pagos</a></li>
        </ul>
        <div id="tabs-1">
            <table width="100%">
                <tr>
                    <td><label>ID</label><br/><?php echo $contactos->getId();?></td>
                    <td><label>Nombre</label><br/><?php echo $contactos->getName();?></td>
                    <td><label>Apellidos</label><br/><?php echo $contactos->getApellidos();?></td>
                </tr>
                <tr>
                    <td><label>Calle</label><br/><?php echo $contactos->getCalle();?></td>
                    <td><label>Numero exterior</label><br/><?php echo $contactos->getNumeroExterior();?></td>
                    <td><label>Numero interior</label><br/><?php echo $contactos->getNumeroInterior();?></td>
                </tr>
                <tr>
                    <td><label>Colonia</label><br/><?php echo $contactos->getColonia();?></td>
                    <td><label>Codigo Postal</label><br/><?php echo $contactos->getCodigoPostal();?></td>
                    <td><label>Cuidad</label><br/><?php echo $contactos->getCuidad();?></td>
                </tr>
                <tr>
                    <td><label>Municipio</label><br/><?php echo $contactos->getMunicipio();?></td>
                    <td><label>Estado</label><br/><?php echo $contactos->getEstado();?></td>
                    <td><label>Pais</label><br/><?php echo $contactos->getPais();?></td>
                </tr>
                <tr>
                    <td><label>Fecha de Creacion</label><br/><?php echo format_datetime($contactos->getCreatedAt(), 'g', 'es_CL') ;?></td>
                    <td><label>Fecha de Actualizacion</label><br/><?php echo format_datetime($contactos->getUpdatedAt(), 'g', 'es_CL');?></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label>Telefono</label><br/><?php echo $contactos->getTelefono();?></td>
                    <td><label>Celular</label><br/><?php echo $contactos->getCelular();?></td>
                    <td><label>Email</label><br/><?php echo $contactos->getEmail();?></td>
                </tr>
            </table>
        </div>
        <div id="tabs-2">
            <?php include_partial('clientes/list_clientes', array('clientes'=>$clientes))?>
        </div>
        <div id="tabs-3">
            <?php include_partial('cotizaciones/list_cotizaciones', array('cotizaciones'=>$cotizaciones))?>
        </div>
        <div id="tabs-4">
            <?php //include_partial('pagos/list_pagos', array('pagos'=>$pagos))?>
        </div>
    </div>
      
      
  </div>
  <div id="sf_admin_footer">
    <?php //include_partial('contactos/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>