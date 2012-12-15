<?php

/**
 * detpagos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detpagostalentos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detpagostalentosActions extends sfActions
{
  

  public function executeShow(sfWebRequest $request)
  {
    $this->detalle_cotizacion = Doctrine_Core::getTable('DetallesCotizacion')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->detalle_cotizacion);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detpagostalentos/show', array('detalle_cotizacion' => $this->detalle_cotizacion,'sin_div' => true));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DetallesPagosTalentosForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DetallesPagosTalentosForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($detalles_pagos = Doctrine_Core::getTable('DetallesPagosTalentos')->find(array($request->getParameter('id'))), sprintf('Object detalles_pagos does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesPagosTalentosForm($detalles_pagos);
    $this->pago=$this->form->getObject()->getPagosTalentos();
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detpagostalentos/form_ajax', array('form' => $this->form,'pago'=>$this->pago));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($detalles_pagos = Doctrine_Core::getTable('DetallesPagosTalentos')->find(array($request->getParameter('id'))), sprintf('Object detalles_pagos does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesPagosTalentosForm($detalles_pagos);
    $this->pago=null;
    $objeto=$this->processForm($request, $this->form);
    if(is_null($objeto)){
        $this->pago=$this->form->getObject()->getPagosTalentos(); 
        if($request->isXmlHttpRequest()){
            try{
               return $this->renderPartial('detpagostalentos/form_ajax', array('form' => $this->form,'pago'=>$this->pago,'sin_div' => true));
           } catch(Exception $e){
               throw $e->getMessage();
           }
        }else{    
            $this->setTemplate('edit');
        }
    }else{
       if ($request->isXmlHttpRequest()) {
          try {
            //return $this->renderPartial('detpagos/show',  array('form' => $this->form,'pago'=>$this->pago,'sin_div' => true));
            return $this->renderText("ok");  
          } catch (Exception $e) {
            throw $e->getMessage();
          }
      } else {
         $this->redirect('detpagostalentos/show?id=' . $detalles_pagos->getId());
      }
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($detalles_pagos = Doctrine_Core::getTable('DetallesPagosTalentos')->find(array($request->getParameter('id'))), sprintf('Object detalles_pagos does not exist (%s).', $request->getParameter('id')));
    $detalles_pagos->delete();

    $this->redirect('detpagostalentos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $detalles_pagos_talentos = $form->save();
      
      return $detalles_pagos_talentos;
      
    }else{
        
        return null;
    }
    
  }
  public function executeCalculo(sfWebRequest $request){
      
    $arreglo=$request->getParameter('detalles_pagos');  
    $this->precio=$arreglo['precio'];
    $this->margen_jerry_ml=$arreglo['margen_jerry_ml'];
    if(isset($arreglo['margen_comisionista'])){
        $this->margen_comisionista=$arreglo['margen_comisionista'];
    }else{
        $this->margen_comisionista=0;
    }
    
   
    if($request->isXmlHttpRequest()){
       try{
          return $this->renderPartial('calculo', array(
              'precio' => $this->precio,
              'margen_jerry_ml' => $this->margen_jerry_ml,
              'margen_comisionista' => $this->margen_comisionista
                  ));
       } catch(Exception $e){
          throw $e->getMessage();
       }
    }
  }
}
