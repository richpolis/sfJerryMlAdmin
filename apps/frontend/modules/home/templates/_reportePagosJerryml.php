<?php use_helper('Number') ?>
<table border="0" class="reportes" width="100%">
    <thead>
    <tr>
        <th>Cotizacion</th>
        <th>Cliente</th>
        <th>Ganancia Jerry Ml</th>
        <th>Ganancia Comisionista</th>
    </tr>
    </thead>
<tbody>    
    <?php 
      $gananciaJerryMlTotal=0;
      $gananciaComisionistaTotal=0;
      $contTotal=0;
      $cots=array();
    ?>
                
                <?php foreach($cotizaciones as $cotizacion):?>
                    <?php 
                    $gananciaJerryMl=0;
                    $gananciaComisionista=0;
                    $cont=0;
                    ?>
                    <?php if(!in_array($cotizacion->getId(), $cots)):?>
                        <?php foreach($cotizacion->getDetallesCotizacion() as $dc):?>
                            <?php 
                                $gananciaJerryMl+=$dc->getGananciaJerryml();
                                $gananciaComisionista+=$dc->getGananciaComisionista();
                                $cont++;
                            ?>
                        <?php endforeach;?>
                        <?php $cots[]=$cotizacion->getId();?>
                        <tr align="center">
                        <td align="left">
                            <?php echo $cotizacion;?>
                            <ul>
                            <?php foreach($cotizacion->getCotizacionesEventos() as $ce):?>
                            <li style="text-transform: uppercase;font-size: xx-small;">
                                <?php echo $ce->getEventos()->getSubject()?>
                            </li>    
                            <?php endforeach;?>
                            </ul>    
                        </td>    
                        <td>
                            <?php echo $cotizacion->getClientes()?>
                        </td>        
                        <td><?php echo format_currency($gananciaJerryMl, 'USD') ?></td>
                        <td><?php echo format_currency($gananciaComisionista, 'USD') ?></td>
                        <?php 
                            $gananciaJerryMlTotal+=$gananciaJerryMl;
                            $gananciaComisionistaTotal+=$gananciaComisionista;
                            $contTotal+=$cont;
                        ?>
                        </tr>
                    <?php endif;?>    
                <?php endforeach;?>    

    <tr align="center"  style="color: black; font-weight: bold; border-bottom: 2px solid black; ">
        <td>Total general</td>        
        <td>Registros <?php echo $contTotal;?></td>
        <td><?php echo format_currency($gananciaJerryMlTotal, 'USD') ?></td>
        <td><?php echo format_currency($gananciaComisionistaTotal, 'USD') ?></td>
    </tr>        
</tbody>                

</table>


