<?php

/**
 * Contratos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Contratos extends BaseContratos
{
    public function getStringStatus(){
        if($this->getEstaFirmado()){
            return "Contrato Firmado";
        }elseif($this->getHayContrato()){
            return "En mediacion";
        }else{
            return "Generar contrato";
        }
    }
    
    function getHayContrato(){
        $ruta=sfConfig::get('sf_upload_dir').'/contratos/'.$this->getFile();
        return file_exists($ruta);
    }
    
    function save(\Doctrine_Connection $conn = null) {
        if($this->getEstaFirmado()){
            if(!$this->getHayContrato()){
                $this->setEstaFirmado(false);
                sfContext::getInstance()->getUser()->setFlash('error', 'No se ha subido ningun archivo');
            }
        }
        
        if(!$this->isNew()){
          $this->setUserId(sfContext::getInstance()->getUser()->getGuardUser()->getId());
        }
        
        parent::save($conn);
        
        if($this->getEstaFirmado()){
            $this->contratoFirmado();
        }
        
    }
    function delete(\Doctrine_Connection $conn = null) {
        if(!$this->getEstaFirmado()){
            if(file_exists(sfConfig::get('sf_uploads_dir').'/contratos/'.$this->getFile())){
                unlink(sfConfig::get('sf_uploads_dir').'/contratos/'.$this->getFile());
                sfContext::getInstance()->getUser()->setFlash('info', "Archivo {$this->getFile()} borrado");
            }
            parent::delete($conn);
        }else{
            sfContext::getInstance()->getUser()->setFlash('error', 'Para borrar el contrato, por favor desactive la casilla de Esta firmado y guarde');
        }
    }
    
    function contratoFirmado(){
        $cotizacion=$this->getCotizaciones();
        if($cotizacion->statusEnMediacion()){
            $cotizacion->aprobarCotizacion();
        }
    }
    
}
