<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of components
 *
 * @author richpolis@gmail.com
 */
class detcotizacionesComponents extends sfComponents {
    public function executeShow(sfWebRequest $request)
    {
            $this->slides = Doctrine_Core::getTable('GeneraMusicaEventos')->getEventsPromoted()->execute();
    }

    public function executeEdit(sfWebRequest $request){
      $this->eventos = Doctrine_Core::getTable('GeneraMusicaEventos')->getEventsLateral()->execute();
    }



}
?>
