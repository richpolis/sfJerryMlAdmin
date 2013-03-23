<?php use_helper('I18N', 'Date') ?>
<?php include_partial('pagos/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Pagos', array(), 'messages') ?></h1>
  <?php include_partial('pagos/flashes') ?>

  <div id="sf_admin_content">
    <?php include_partial('pagos/pago', array('pagos' => $pagos)) ?>
      
    <?php include_partial('pagos/cotizaciones', array('pagos' => $pagos,"cotizaciones"=>$cotizaciones)) ?>
      
    <ul class="sf_admin_actions">
         <li>
             <a href="<?php echo url_for('@pagos')?>">Listado</a>
         </li>
         <!--li>
             <a href="<?php echo url_for('@seleccionar_cotizaciones')?><?php echo '?modo=pagos&goto=/index.php/pagos/'.$pagos->getId().'&cliente='.$pagos->getClienteId()."&pago=".$pagos->getId();?>" id="seleccionar_cotizacion">Agregar cotizaciones</a>
         </li-->
         
         <?php 
         $aprobarPagos=false;
         $liberarPagos=false;
         foreach($pagos->getDetallesPagos() as $dp):
             if($dp->getStatus()==PagosTable::$INCOMPLETO){
                 $aprobarPagos=true;
             }elseif($dp->getStatus()==PagosTable::$APROBADO){
                 $liberarPagos=true;
             }
         endforeach;
        ?>
        <?php if($aprobarPagos):?>
         <li>
             <a href="<?php echo url_for('@aprobar_pagos?generar='.$pagos->getId())?>" id="aprobar_pagos">Aprobar Pagos</a>
         </li>
        <?php endif;?>
        <?php if($liberarPagos):?>
         <li>
             <a href="<?php echo url_for('@liberar_pagos?generar='.$pagos->getId())?>" id="liberar_pagos">Liberar Pagos</a>
        </li>
        <?php endif;?>
    </ul>
  </div>
</div>