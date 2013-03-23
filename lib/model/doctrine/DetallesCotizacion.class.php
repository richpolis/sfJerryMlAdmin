<?php

/**
 * DetallesCotizacion
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class DetallesCotizacion extends BaseDetallesCotizacion
{
    public function __toString() {
        return sprintf("%s.-%s",$this->getId(),$this->getTalentos());
    }
    public function getTotal(){
        //$this->save();
        return $this->getSubtotal()+$this->getIva();
    }

    public function getSubtotal(){
        return $this->getPrecio();
    }
    public function setSubtotal($valor){
        $this->setPrecio($valor);
    }

    public function save(\Doctrine_Connection $conn = null) {
        
        if($this->getPrecio()){
            $precio=$this->getPrecio();
            $gananciaJerryMl=$precio*($this->getMargenJerryMl()/100);
            $gananciaTalento=$precio-$gananciaJerryMl;
            $gananciaComisionista=$gananciaTalento*($this->getMargenComisionistas()/100);
            $this->setGananciaTalento($gananciaTalento);
            $this->setGananciaJerryMl($gananciaJerryMl);
            $this->setGananciaComisionistas($gananciaComisionista);
            $this->setIva($precio * CotizacionesTable::$IVA);
        }
        
        if(!$this->getPosition()){
            $this->setPosition(Doctrine_Core::getTable('DetallesCotizacion')->getMaximo());
        }
        
        parent::save($conn);
        
        //$cotizacion=$this->getCotizaciones();
        //$cotizacion->calcular();
    }
    
    public function getGananciaTalentoReal(){
        return $this->getGananciaTalento()-$this->getGananciaComisionistas();
    }
    
    public function getPrecioConIva(){
        return $this->getPrecio() * CotizacionesTable::$CON_IVA;
    }
    public function calcularPagosTalentos(){
        $detalles_pagos_talentos=$this->getDetallesPagosTalentos();
        $importe=0.0;
        foreach ($detalles_pagos_talentos as $detalle){
            if($detalle->getStatus()==PagosTalentosTable::$PAGOS_CALCULADOS){
                $importe+=$detalle->getImporte();
            }
        }
        if($importe==$this->getGananciaTalento()){
            $this->setIsPayTalento(true);
            $this->save();
        }
    }
    public function validarCancelarAprobacion(){
        $importe=0;
        foreach($this->getDetallesPagosTalentos() as $dpt){
            if($dpt->getStatus()==PagosTalentosTable::$PAGOS_CALCULADOS){
                $importe+=$dpt->getImporte();
            }
        }
        return ($importe>0?false:true);
    }
    
    public function delete(\Doctrine_Connection $conn = null) {
        
        if($this->validarCancelarAprobacion()){
            $eventos=$this->getEventos();
            foreach($eventos as $evento){
               $evento->delete();
            }
            
            foreach($this->getDetallesCotizacionConceptos() as $dcc){
                $dcc->delete();
            }
            foreach($this->getDetallesCotizacionComisionistas() as $dcco){
                $dcco->delete();
            }
            parent::delete($conn);
            return true;
        }else{
            return false;
        }
        
    }
    public function calcularConceptos($calcular=true){
        if($calcular){
            $dccs=$this->getDetallesCotizacionConceptos();
            $precio=0;
            $precioInicial=$this->getPrecio();
            foreach ($dccs as $dcc){
                $precio+=$dcc->getPrecio();
            }
            $this->setPrecio($precio);

            if($precioInicial!=$precio){
                $this->calcularComisionistas();    
            }else{
                $this->save();
            }
            
        }
    }

    public function calcularComisionistas($calcular=true){
        if($calcular){
            $dccos=$this->getDetallesCotizacionComisionistas();
            $margen=0;
            $margenTotal=0;
            $gananciaJerryMl=($this->getPrecio()*($this->getMargenJerryMl()/100));
            $gananciaTalento=($this->getPrecio()-$gananciaJerryMl);
            

            foreach ($dccos as $dcco){
                $margen=$dcco->getMargen();
                $dcco->setGanancia($gananciaTalento*($margen/100));    
                $dcco->saveOnly();
                $margenTotal+=$margen;
            }
            $this->setMargenComisionistas($margenTotal);
            $this->save();
        }
    }
    
    public function actualizarEventosDesdeCotizacion(
            DateTime $dateInicial,
            DateTime $dateFinal, 
            Cotizaciones $cot,
            DetallesCotizacion $dc) {
        $existeEvento = false;
        try {
            $eventos = KsWCEventTable::getInstance()->getEventosPorFechaYTalento(
                    $dateInicial->format("Y-m-d"), $dateFinal->format("Y-m-d"), $dc->getTalentoId()
            );
        } catch (PDOException $e) {
            $eventos = array();
        }

        foreach ($dc->getEventos() as $evento) {
            if ($evento->getNivel() == CotizacionesTable::$NIVEL_COTIZACION) {
                $existeEvento = true;
                $evento1 = $evento;
                break;
            } elseif ($evento->getStartTime() == $this->getFechaDesde()) {
                $existeEvento = true;
                $evento1 = $evento;
                break;
            }
        }
        if (!$existeEvento) {
            $existeEventoApartado = false;
            $contEventosDisponibles = 0;
            foreach ($eventos as $e) {
                if ($e->getStatus() == KsWcEventTable::$APARTADO) {
                    $existeEventoApartado = true;
                } else {
                    $contEventosDisponibles++;
                }
            }
            if (count($eventos) == $contEventosDisponibles) {
                $evento1 = new KsWCEvent();
                $evento1->crearEventoDesdeCotizacion($cot, $dc);
            }
        } else {
            $evento1->actualizarEventoDesdeCotizacion($cot);
        }
    }
    
    public function getConceptosString() {
        $dccs = $this->getDetallesCotizacionConceptos();
        $sConceptos = "";
        foreach ($dccs as $dcc) {
            if(strlen($sConceptos)>0){
                $sConceptos.=', '.$dcc->getConceptos();
            }else{
                $sConceptos.=$dcc->getConceptos();
            }
        }
        return $sConceptos;
    }
    public function getTalentosArray(){
        return array($this->getTalentos());
    }
    public function getComisionistasArray(){
        $arreglo=array();
        foreach($this->getDetallesCotizacionComisionistas() as $dcco){
            $arreglo[]=$dcco->getComisionistas();
        }
        return $arreglo;
    }
    public function getComisionistasString(){
        $sComisionista="";
        foreach($this->getDetallesCotizacionComisionistas() as $dcco){
            if(strlen($sComisionista)>0){
                $sComisionista.=', '.$dcco->getComisionistas();
            }else{
                $sComisionista.=$dcco->getComisionistas();
            }
        }
        return $sComisionista;
    }
    
}