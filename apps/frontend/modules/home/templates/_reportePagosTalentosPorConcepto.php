<?php use_helper('Number') ?>
<table border="0" class="reportes" width="100%">
    <thead>
    <tr>
        <th>Concepto</th>
        <th>Ganancia Talento</th>
        <th>Ganancia Jerry Ml</th>
        <th>Ganancia Comisionista</th>
    </tr>
    </thead>
<tbody>    
    <?php 
      $CONCEPTO=0;
      $TALENTO=1;
      $JERRYML=2;
      $COMISIONISTA=3;
      $gananciaTalentoTotal=0;
      $gananciaJerrymlTotal=0;
      $gananciaComisionistaTotal=0;
      $cont=0;
      $arrayConceptos=array();
      foreach($dccs as $dcc){
          $dc=$dcc->getDetallesCotizacion();
          $margenJerryml=$dc->getMargenJerryMl();
          $margenComisionista=$dc->getMargenComisionista();
          $gananciaTalento=0;
          $gananciaJerryml=0;
          $gananciaComisionista=0;
          $precio=$dcc->getPrecio();
          $sConcepto=$dcc->getConceptos()->getConcepto();
          
          if(!array_key_exists($sConcepto, $arrayConceptos)){
              $arrayConceptos[$sConcepto]=array();
              $arrayConceptos[$sConcepto][$CONCEPTO]=$sConcepto;
              $arrayConceptos[$sConcepto][$TALENTO]=0;
              $arrayConceptos[$sConcepto][$JERRYML]=0;
              $arrayConceptos[$sConcepto][$COMISIONISTA]=0;
          }
          $gananciaTalento=$precio*(1-($margenJerryml/100));
          $arrayConceptos[$sConcepto][$TALENTO]+=$gananciaTalento;
          $gananciaJerryml=$precio-$gananciaTalento;
          if($margenComisionista>0){
             $gananciaComisionista=$gananciaJerryml*($margenComisionista/100);
             $gananciaJerryml-=$gananciaComisionista;
          }
          $arrayConceptos[$sConcepto][$JERRYML]+=$gananciaJerryml;
          $arrayConceptos[$sConcepto][$COMISIONISTA]+=$gananciaComisionista;
      }
      
    ?>
                
    <?php foreach($arrayConceptos as $arrayConcepto):?>
    <tr align="center">
    <td align="left">
       <?php echo $arrayConcepto[$CONCEPTO];?>
    </td>    
    <td><?php echo format_currency($arrayConcepto[$TALENTO], 'USD') ?></td>
    <td><?php echo format_currency($arrayConcepto[$JERRYML], 'USD') ?></td>
    <td><?php echo format_currency($arrayConcepto[$COMISIONISTA], 'USD') ?></td>
    <?php 
        $gananciaTalentoTotal+=$arrayConcepto[$TALENTO];
        $gananciaJerrymlTotal+=$arrayConcepto[$JERRYML];
        $gananciaComisionistaTotal+=$arrayConcepto[$COMISIONISTA];
        $cont++;
    ?>
    </tr>
    <?php endforeach;?>    

    <tr align="center"  style="color: black; font-weight: bold; border-bottom: 2px solid black; ">
        <td>Total general</td>        
        <td><?php echo format_currency($gananciaTalentoTotal, 'USD') ?></td>
        <td><?php echo format_currency($gananciaJerrymlTotal, 'USD') ?></td>
        <td><?php echo format_currency($gananciaComisionistaTotal, 'USD') ?></td>
    </tr>        
</tbody>                

</table>


