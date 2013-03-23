<?php

/**
 * sfGuardUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class sfGuardUser extends PluginsfGuardUser
{
    public function __toString() {
        return $this->getNombreCompleto();
    }
    public function getNombreCompleto(){
        return sprintf("%s %s", $this->getFirstName(),$this->getLastName());
    }
    
    public function getEmail(){
        return $this->getEmailAddress();
    }
    
    public function addEventoDesdeCotizacion(Cotizaciones $cotizacion){
        /*$dateInicial=new DateTime($cotizacion->getFechaDesde());
        $dateFinal=new DateTime($cotizacion->getFechaHasta());
        
        try {
            $eventos = EventosUsuariosTable::getInstance()->getEventosPorFechaYUsuario(
                    $dateInicial->format("Y-m-d"), $dateFinal->format("Y-m-d"), $cotizacion->getManagerId()
            );
        } catch (PDOException $e) {
            $eventos = array();
        }*/

        foreach ($cotizacion->getEventosUsuarios() as $evento) {
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
            $evento1 = new EventosUsuarios();
            $evento1->crearEventoDesdeCotizacion($cotizacion);
        } else {
            $evento1->actualizarEventoDesdeCotizacion($cotizacion);
        }
    }
}
