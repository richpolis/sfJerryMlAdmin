<?php

/**
 * detprecotizaciones actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detprecotizaciones
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detprecotizacionesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->detalles_precotizacions = Doctrine_Core::getTable('DetallesPrecotizacion')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->detalles_precotizacion = Doctrine_Core::getTable('DetallesPrecotizacion')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->detalles_precotizacion);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detprecotizaciones/show', array('detalles_precotizacion' => $this->detalles_precotizacion));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DetallesPrecotizacionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DetallesPrecotizacionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($detalles_precotizacion = Doctrine_Core::getTable('DetallesPrecotizacion')->find(array($request->getParameter('id'))), sprintf('Object detalles_precotizacion does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesPrecotizacionForm($detalles_precotizacion);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detprecotizaciones/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($detalles_precotizacion = Doctrine_Core::getTable('DetallesPrecotizacion')->find(array($request->getParameter('id'))), sprintf('Object detalles_precotizacion does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesPrecotizacionForm($detalles_precotizacion);

    $objeto=$this->processForm($request, $this->form);
    if(is_null($objeto)){
        if($request->isXmlHttpRequest()){
            try{
               return $this->renderPartial('detprecotizaciones/form_ajax', array('form' => $this->form));
           } catch(Exception $e){
               throw $e->getMessage();
           }
        }else{    
            $this->setTemplate('edit');
        }
    }else{
       if ($request->isXmlHttpRequest()) {
          try {
            return $this->renderPartial('detprecotizaciones/show', array('detalles_precotizacion' => $objeto, 'sin_div' => true));
          } catch (Exception $e) {
            throw $e->getMessage();
          }
      } else {
         $this->redirect('detprecotizaciones/show?id=' . $detalles_precotizacion->getId());
      }
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    $arreglo= $request->getParameter('detalles_precotizacion'); 
      
    $detalles_precotizacion = Doctrine_Core::getTable('DetallesPrecotizacion')->find($arreglo['id']);  
    
    //$precotizacion=$detalles_precotizacion->getPrecotizaciones();
    
    $resp=$detalles_precotizacion->delete();

    if($request->isXmlHttpRequest()){
      if($resp){
          //$precotizacion->calcular();
          return $this->renderText('ok');
      }else{
          return $this->renderText('No fue posible eliminar el registro');
      }
    }

    $this->redirect('detprecotizaciones/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $detalles_precotizacion = $form->save();
      
      return $detalles_precotizacion;
      
    }else{
        return null;
    }
    
  }
  public function executeCalculo(sfWebRequest $request){
      
    $arreglo=$request->getParameter('detalles_precotizacion');  
    $this->precio=$arreglo['precio'];
    $this->margen_jerry_ml=$arreglo['margen_jerry_ml'];
    
   
    if($request->isXmlHttpRequest()){
       try{
          return $this->renderPartial('calculo', array(
              'precio' => $this->precio,
              'margen_jerry_ml' => $this->margen_jerry_ml
          ));
       } catch(Exception $e){
          throw $e->getMessage();
       }
    }
  }
}