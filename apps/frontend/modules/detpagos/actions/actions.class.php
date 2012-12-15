<?php

/**
 * detpagos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detpagos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detpagosActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->cotizaciones = Doctrine_Core::getTable('Cotizaciones')->find(array($request->getParameter('cotizacion_id')));
    $this->forward404Unless($this->cotizaciones);
    if($request->hasParameter('sin_div')){
        $div=true;
    }else{
        $div=false;
    }
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detpagos/list', array('cotizacion' => $this->cotizaciones,'sin_div' => $div));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $dp=new DetallesPagos();
    $dp->setUserId($this->getUser()->getGuardUser()->getId());
    $dp->setCotizacionId($request->getParameter('cotizacion_id'));
    $dp->setPagosId($request->getParameter('pago_id'));
    $this->form=new DetallesPagosForm($dp);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detpagos/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DetallesPagosForm();

    $objeto=$this->processForm($request, $this->form);
    if(is_null($objeto)){
        if($request->isXmlHttpRequest()){
            try{
               return $this->renderPartial('detpagos/form_ajax', array('form' => $this->form));
           } catch(Exception $e){
               throw $e->getMessage();
           }
        }else{    
            $this->setTemplate('edit');
        }
    }else{
       $this->form=new DetallesPagosForm();
       if ($request->isXmlHttpRequest()) {
          try {
            return $this->renderText('ok');
          } catch (Exception $e) {
            throw $e->getMessage();
          }
      } else {
         $this->redirect('detpagos/show?cotizacion_id=' . $objecto->getCotizacionId());
      }
    }
    

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($detalles_pagos = Doctrine_Core::getTable('DetallesPagos')->find(array($request->getParameter('id'))), sprintf('Object detalles_pagos does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesPagosForm($detalles_pagos);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detpagos/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($detalles_pagos = Doctrine_Core::getTable('DetallesPagos')->find(array($request->getParameter('id'))), sprintf('Object detalles_pagos does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesPagosForm($detalles_pagos);
    $objeto=$this->processForm($request, $this->form);
    if(is_null($objeto)){
        if($request->isXmlHttpRequest()){
            try{
               return $this->renderPartial('detpagos/form_ajax', array('form' => $this->form));
           } catch(Exception $e){
               throw $e->getMessage();
           }
        }else{    
            $this->setTemplate('edit');
        }
    }else{
       $this->form=new DetallesPagosForm();
       if ($request->isXmlHttpRequest()) {
          try {
            return $this->renderText('ok');
          } catch (Exception $e) {
            throw $e->getMessage();
          }
      } else {
         $this->redirect('detpagos/show?cotizacion_id=' . $objecto->getCotizacionId());
      }
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($detalles_pagos = Doctrine_Core::getTable('DetallesPagos')->find(array($request->getParameter('id'))), sprintf('Object detalles_pagos does not exist (%s).', $request->getParameter('id')));
    $detalles_pagos->delete();

    $this->redirect('detpagos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $detalles_pagos = $form->save();
      
      return $detalles_pagos;
      
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
