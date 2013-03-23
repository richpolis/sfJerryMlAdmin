<?php use_helper('Escaping') ?>
<?php use_helper('Number') ?>
<?php if (!isset($sin_div)) $sin_div = false; ?>
<?php $detalles_pagos = $cotizacion->getDetallesPagos(); ?>
<?php $pago = $detalles_pagos[0]->getPagos(); ?>
<?php if (!$sin_div): ?>
<div id="cotizacion_<?php echo $cotizacion->getId() ?>">
<?php endif; ?>
    <h2><?php echo 'Cotizacion: '.$cotizacion; ?> <?php if($cotizacion->getIsPay()) echo 'Pagado'?></h2>    
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Fecha Pago:</th>
                <th>Tipo de pago:</th>
                <th>Status:</th>
                <th>Importes:</th>
                <th>Acciones</th>
            </tr>
        </thead> 
        <tbody>
            <?php 
            $importeTotal=0.0; 
            $ivaTotal=0.0;
            ?>
            <?php foreach($detalles_pagos as $detalles_pago):?>
            <?php include_partial('detpagos/show',array('detalles_pago'=>$detalles_pago))?>
            <?php 
                    $importeTotal+=$detalles_pago->getImporte();
                    $ivaTotal+=$detalles_pago->getIva();
            ?>
            <?php endforeach; ?>
            <?php if(($importeTotal+$ivaTotal)>0):?>
            <tr>
                <td width="15%">&nbsp;</td>  
                <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>
                <td width="30%">
                    <table width="100%" class="no-padding">
                        <tr>
                            <th width="50%">Subtotal: </th>
                            <td><?php echo format_currency($importeTotal,'USD') ?></td>
                        </tr>
                        <tr>
                            <th width="50%">IVA: </th>
                            <td><?php echo format_currency($ivaTotal,'USD') ?></td>
                        </tr>
                        <tr>
                            <th width="50%">Total: </th>
                            <td><?php echo format_currency($importeTotal+$ivaTotal, 'USD') ?></td>
                        </tr>
                    </table>
                </td>
                <td width="25%">&nbsp;</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <div id="importes-detalles-pago">
                      <?php
                      $total_pagado=0;
                      foreach($detalles_pagos as $detalles_pago):
                          if($detalles_pago->getStatus()==PagosTable::$PAGOS_CALCULADOS):
                                  $total_pagado+=$detalles_pago->getImporte();
                          endif;
                      endforeach;  
                      
                        $saldo=$cotizacion->getSubtotal()-$total_pagado;
                        echo "Saldo por pagar: ". format_currency($saldo, 'USD'); 
                      ?>
                    </div>
                    <?php // if(!$cotizacion->getIsPay()):?>
                    <!--a href="#" onclick="$.crearDetallesPago('<?php echo $detalles_pagos[0]->getPagosId()?>','<?php echo $detalles_pagos[0]->getCotizacionId()?>')">Nuevo registro</a-->
                    <?php //endif; ?>
                </td>  
            </tr>
        </tfoot>
    </table>
    <?php //include_component('detpagos','new', array('pago_id'=>$pago->getId(),'cotizacion_id'=>$cotizacion->getId()))?>
    <?php //include_partial('detpagos/form_ajax', array('form'=>$form))?>
    
<?php if (!$sin_div): ?>    
    </div>
<?php endif; ?>