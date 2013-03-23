<?php use_helper('Number') ?>
<link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.css" />
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<table border="0" class="reportes table table-striped" width="100%">
    <thead>
    <tr>
        <th>Talento</th>
        <th>Concepto</th>
        <th>Total</th>
        <th>Ganancia Talento</th>
        <th>Ganancia Jerry Ml</th>
        <th>Comisionista %</th>
        <th>Ganancia Comisionista</th>
    </tr>
    </thead>
<tbody>    
    <?php
      $NOMBRE_TALENTO=0;
      $CONCEPTO=1;
      $TOTAL=2;
      $TALENTO=3;
      $JERRYML=4;
      $COMISIONISTA=5;
      $gananciaCotizacion=0;
      $gananciaTalentoTotal=0;
      $gananciaJerryMlTotal=0;
      $gananciaComisionistaTotal=0;
      $cont=0;
      $arrayConceptos=array();
      foreach($talentos as $talento){
          $dcs=$talento->getDetallesCotizacion();
          foreach($dcs as $dc){
            $gananciaTalento=$dc->getGananciaTalento();
            $gananciaJerryMl=$dc->getGananciaJerryMl();
            $gananciaComisionista=$dc->getGananciaComisionista();
            $gananciaDetalleCotizacion=$dc->getSubtotal();
            $dccs=$dc->getDetallesCotizacionConceptos();
            $sConcepto=$dccs[0]->getConceptos()->getConcepto();

            if(!array_key_exists($sConcepto, $arrayConceptos)){
                $arrayConceptos[$sConcepto]=array();
                $arrayConceptos[$sConcepto][$NOMBRE_TALENTO]=$talento;
                $arrayConceptos[$sConcepto][$CONCEPTO]=$sConcepto;
                $arrayConceptos[$sConcepto][$TOTAL]=0;
                $arrayConceptos[$sConcepto][$TALENTO]=0;
                $arrayConceptos[$sConcepto][$JERRYML]=0;
                $arrayConceptos[$sConcepto][$COMISIONISTA]=0;
            }
            $arrayConceptos[$sConcepto][$TOTAL]+=$gananciaDetalleCotizacion;
            $arrayConceptos[$sConcepto][$TALENTO]+=$gananciaTalento;
            $arrayConceptos[$sConcepto][$JERRYML]+=$gananciaJerryMl;
            $arrayConceptos[$sConcepto][$COMISIONISTA]+=$gananciaComisionista;
          }
      }
      
    ?>
                
    <?php foreach($arrayConceptos as $arrayConcepto):?>
    <tr align="center">
    <td align="left">
       <?php echo $arrayConcepto[$NOMBRE_TALENTO];?>
    </td>    
    <td align="left">
       <?php echo $arrayConcepto[$CONCEPTO];?>
    </td>
    <td><?php echo format_currency($arrayConcepto[$TOTAL], 'USD') ?></td>
    <td><?php echo format_currency($arrayConcepto[$TALENTO], 'USD') ?></td>
    <td><?php echo format_currency($arrayConcepto[$JERRYML], 'USD') ?></td>
    <td><?php echo ($arrayConcepto[$COMISIONISTA]/$arrayConcepto[$TOTAL])*100 ?>%</td>
    <td><?php echo format_currency($arrayConcepto[$COMISIONISTA], 'USD') ?></td>
    <?php 
        $gananciaTalentoTotal+=$arrayConcepto[$TALENTO];
        $gananciaJerryMlTotal+=$arrayConcepto[$JERRYML];
        $gananciaComisionistaTotal+=$arrayConcepto[$COMISIONISTA];
        $gananciaCotizacion+=$arrayConcepto[$TOTAL];
        $cont++;
    ?>
    </tr>
    <?php endforeach;?>    

    <tr align="center"  style="color: black; font-weight: bold; border: 2px solid black; ">
        <td>Total general</td>
        <td>&nbsp;</td>
        <td><?php echo format_currency($gananciaCotizacion, 'USD') ?></td>
        <td><?php echo format_currency($gananciaTalentoTotal, 'USD') ?></td>
        <td><?php echo format_currency($gananciaJerryMlTotal, 'USD') ?></td>
        <td><?php echo ($gananciaComisionistaTotal/$gananciaCotizacion)*100 ?>%</td>
        <td><?php echo format_currency($gananciaComisionistaTotal, 'USD') ?></td>
    </tr>        
</tbody>                

</table>


