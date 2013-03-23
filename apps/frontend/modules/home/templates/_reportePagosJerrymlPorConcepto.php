<?php use_helper('Number') ?>
<?php
    const CONCEPTO=0;
    const TALENTO=1;
    const TOTAL=2;
    const TOTAL_TALENTO=3;
    const MARGEN_JERRYML=4;
    const TOTAL_JERRYML=5;
    const COMISIONISTA=6;
    const MARGEN_COMISIONISTA=7;
    const TOTAL_COMISIONISTA=8;

class reporteConceptosJerryMl {
    
    
    private $totalTotal=0;
    private $totalTalentoTotal=0;
    private $totalJerryMlTotal=0;
    private $totalComisionistasTotal=0;
    private $contTotal=0;

    private $arrayConceptos=array();

    public function loadConceptos($dccs){
        foreach ($dccs as $dcc) {
            $dc = $dcc->getDetallesCotizacion();
            $talento=$dc->getTalentosArray();
            $comisionistas=$dc->getComisionistasString();
            
            $sConcepto = $dcc->getConceptos()->getConcepto();

            if (!array_key_exists($sConcepto, $this->arrayConceptos)) {
                $this->arrayConceptos[$sConcepto] = array();
                $this->arrayConceptos[$sConcepto][CONCEPTO] = $sConcepto;
                $this->arrayConceptos[$sConcepto][TALENTO] = $talento[0];
                $this->arrayConceptos[$sConcepto][TOTAL] = 0;
                $this->arrayConceptos[$sConcepto][TOTAL_TALENTO] = 0;
                $this->arrayConceptos[$sConcepto][MARGEN_JERRYML] = 0;
                $this->arrayConceptos[$sConcepto][TOTAL_JERRYML] = 0;
                $this->arrayConceptos[$sConcepto][COMISIONISTA] = $comisionistas;
                $this->arrayConceptos[$sConcepto][MARGEN_COMISIONISTA] = 0;
                $this->arrayConceptos[$sConcepto][TOTAL_COMISIONISTA] = 0;
                
            }else{
                $this->arrayConceptos[$sConcepto][TALENTO] .= ", ".$talento[0];
                $this->arrayConceptos[$sConcepto][TOTAL] +=$dc->getPrecio();
                $this->arrayConceptos[$sConcepto][TOTAL_TALENTO] +=$dc->getGananciaTalento();
                $this->arrayConceptos[$sConcepto][TOTAL_JERRYML] +=$dc->getGananciaJerryMl();
                $this->arrayConceptos[$sConcepto][MARGEN_JERRYML] = ($this->arrayConceptos[$sConcepto][TOTAL_JERRYML]/$this->arrayConceptos[$sConcepto][TOTAL])*100;
                $this->arrayConceptos[$sConcepto][COMISIONISTA] .= ", ".$comisionistas;
                $this->arrayConceptos[$sConcepto][TOTAL_COMISIONISTA] += $dc->getGananciaComisionistas();
                $this->arrayConceptos[$sConcepto][MARGEN_COMISIONISTA] = ($this->arrayConceptos[$sConcepto][TOTAL_COMISIONISTA]/$this->arrayConceptos[$sConcepto][TOTAL_TALENTO])*100;
                
            }
            
        }
    }
    public function loadTotales(){
        foreach($this->arrayConceptos as $arrayConcepto){
            $this->totalTotal+=$arrayConcepto[TOTAL];
            $this->totalTalentoTotal+=$arrayConcepto[TOTAL_TALENTO];
            $this->totalJerryMlTotal+=$arrayConcepto[TOTAL_JERRYML];
            $this->totalComisionistasTotal+=$arrayConcepto[TOTAL_COMISIONISTA];
            $this->contTotal++;
        }
    }
    public function getArrayConceptos(){
        return $this->arrayConceptos;
    }
    public function getTotalGeneral(){
        return $this->totalTotal;
    }
    public function getTalentoTotal(){
        return $this->totalTalentoTotal;
    }
    public function getJerryMlTotal(){
        return $this->totalJerryMlTotal;
    }
    public function getComisionistasTotal(){
        return $this->totalComisionistasTotal;
    }
    public function getContTotal(){
        return $this->contTotal;
    }
    public function getMargenJerryMl(){
        if($this->totalTotal>0){
            return $this->getJerryMlTotal()/$this->getTotalGeneral()*100;
        }else{
            return 0;
        }
    }
    public function getMargenComisionistas(){
        if($this->totalTalentoTotal>0){
            return $this->getComisionistasTotal()/$this->getTalentoTotal()*100;
        }else{
            return 0;
        }
    }
}

$reporte=new reporteConceptosJerryMl();
$reporte->loadConceptos($dccs);
$reporte->loadTotales();
?>


<link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.css" />
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<table border="0" class="reportes table table-striped" width="100%">
    <thead>
        <tr>
            <th>Concepto</th>
            <th>Talentos</th>
            <th style="text-align: right;">$ Total</th>
            <th style="text-align: right;">$ Talento</th>
            <th style="text-align: right;">% Jerry ML</th>
            <th style="text-align: right;">$ Jerry ML</th>
            <th style="text-align: left;">Comisionistas</th>
            <th style="text-align: right;">% Comisionista</th>
            <th style="text-align: right;">$ Comisionista</th>
        </tr>
    </thead>
    <tbody>    
        
        <?php foreach ($reporte->getArrayConceptos() as $arrayConcepto): ?>
            <tr align="center">
                <td align="left">
                    <?php echo $arrayConcepto[CONCEPTO]; ?>
                </td>
                <td align="left">
                    <?php echo $arrayConcepto[TALENTO]; ?>
                </td>    
                <td style="text-align: right;"><?php echo format_currency($arrayConcepto[TOTAL], 'USD') ?></td>
                <td style="text-align: right;"><?php echo format_currency($arrayConcepto[TOTAL_TALENTO], 'USD') ?></td>
                <td style="text-align: right;">
                    <?php echo number_format($arrayConcepto[MARGEN_JERRYML], 2,".","") ?>%
                </td>
                <td style="text-align: right;"><?php echo format_currency($arrayConcepto[TOTAL_JERRYML], 'USD') ?></td>
                <td align="left">
                    <?php echo $arrayConcepto[COMISIONISTA]; ?>
                </td>
                <td style="text-align: right;">
                    <?php echo number_format($arrayConcepto[MARGEN_COMISIONISTA], 2,".","") ?>%
                </td>
                <td style="text-align: right;"><?php echo format_currency($arrayConcepto[TOTAL_COMISIONISTA], 'USD') ?></td>
            </tr>
        <?php endforeach; ?>    

        <tr align="center"  style="color: black; font-weight: bold; border: 2px solid black; ">
            <td>Total general</td>
            <td>Registro<?php echo ($reporte->getContTotal()==1?' (1)':'s ('.$reporte->getContTotal().')')?></td>
            <td style="text-align: right;"><?php echo format_currency($reporte->getTotalGeneral(), 'USD') ?></td>
            <td style="text-align: right;"><?php echo format_currency($reporte->getTalentoTotal(), 'USD') ?></td>
            <td style="text-align: right;">
                <?php echo number_format($reporte->getMargenJerryMl() ,2,".","") ?>%
            </td>    
            <td style="text-align: right;"><?php echo format_currency($reporte->getJerryMlTotal(), 'USD') ?></td>
            <td></td>
            <td style="text-align: right;"><?php echo number_format($reporte->getMargenComisionistas(),2,".","") ?>%</td>
            <td style="text-align: right;"><?php echo format_currency($reporte->getComisionistasTotal(), 'USD') ?></td>
        </tr>        
    </tbody>                

</table>