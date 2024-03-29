<?php

/**
 * CotizacionesConceptos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CotizacionesConceptos extends BaseCotizacionesConceptos
{
    public function save(\Doctrine_Connection $conn = null) {
        parent::save($conn);
        //$this->actualizarCambios();
    }
    
    public function delete(\Doctrine_Connection $conn = null) {
        $resp=$this->eliminarRegistros();
        parent::delete($conn);
        return $resp;
    }
    
    public function deleteOnly(){
        parent::delete();
    }
    
    public function actualizarCambios(){
        $cotizaciones=$this->getCotizaciones();
        foreach($cotizaciones->getDetallesCotizacion() as $dc){
            $existeConcepto=false;
            foreach($dc->getDetallesCotizacionConceptos() as $dcc){
                if($dcc->getConceptoId()==$this->getConceptoId()){
                    $existeConcepto=true;
                    $dcc1=$dcc;
                    break;
                }
            }
            if(!$existeConcepto){
                $dcc1=new DetallesCotizacionConceptos();
                //$dcc1->setCotizacionId($cotizaciones->getId());
                $dcc1->setDetallesCotizacionId($dc->getId());
                $dcc1->setConceptoId($this->getConceptoId());
                $dcc1->setPrecio($this->getPrecio());
                $dcc1->setNivel(CotizacionesTable::$NIVEL_COTIZACION);
                $dcc1->save();
                if($this->getPrecio()>0){
                    //$dc->setPrecio($dc->getPrecio()+$this->getPrecio());
                    $dc->calcularConceptos();
                    $dc->getCotizaciones()->calcular();
                }
            }elseif($dcc1->getPrecio()<>$this->getPrecio()){
                $precioAnterior=$dc->getPrecio();
                $dcc1->setPrecio($this->getPrecio());
                $dcc1->setNivel(CotizacionesTable::$NIVEL_COTIZACION);
                $dcc1->save();
                //$dc->setPrecio(($dc->getPrecio()-$precioAnterior)+$this->getPrecio());
                $dc->calcularConceptos();
                $dc->getCotizaciones()->calcular();
            }elseif($dcc1->getNivel()<>CotizacionesTable::$NIVEL_COTIZACION){
                $dcc1->setNivel(CotizacionesTable::$NIVEL_COTIZACION);
                $dcc1->save();
            }
        }
    }
    
    public function eliminarRegistros(){
        $cotizaciones=$this->getCotizaciones();
        foreach($cotizaciones->getDetallesCotizacion() as $dc){
            foreach($dc->getDetallesCotizacionConceptos() as $dcc){
                if($dcc->getConceptoId()==$this->getConceptoId()){
                    $dcc->delete();
                }
            }
            $dc->calcularConceptos();
        }
        return true;
    }

}