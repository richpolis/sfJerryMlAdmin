<?php

/**
 * detcotizaciones actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detcotizaciones
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detcotizacionesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->detalles_cotizacions = Doctrine_Core::getTable('DetallesCotizacion')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->detalles_cotizacion = Doctrine_Core::getTable('DetallesCotizacion')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->detalles_cotizacion);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detcotizaciones/show', array('detalles_cotizacion' => $this->detalles_cotizacion));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DetallesCotizacionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DetallesCotizacionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($detalles_cotizacion = Doctrine_Core::getTable('DetallesCotizacion')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesCotizacionForm($detalles_cotizacion);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detcotizaciones/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($detalles_cotizacion = Doctrine_Core::getTable('DetallesCotizacion')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesCotizacionForm($detalles_cotizacion);

    $objeto=$this->processForm($request, $this->form);
    if(is_null($objeto)){
        if($request->isXmlHttpRequest()){
           try{
               return $this->renderPartial('detcotizaciones/form_ajax', array('form' => $this->form));
           } catch(Exception $e){
               throw $e->getMessage();
           }
        }else{    
            $this->setTemplate('edit');
        }
    }else{
       if ($request->isXmlHttpRequest()) {
          try {
            return $this->renderPartial('detcotizaciones/show', array('detalles_cotizacion' => $objeto, 'sin_div' => true));
          } catch (Exception $e) {
            throw $e->getMessage();
          }
      } else {
         $this->redirect('detcotizaciones/show?id=' . $detalles_cotizacion->getId());
      }
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();

    //$this->forward404Unless($detalles_cotizacion = Doctrine_Core::getTable('DetallesCotizacion')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion does not exist (%s).', $request->getParameter('id')));
    $arreglo= $request->getParameter('detalles_cotizacion'); 
      
    $detalles_cotizacion = Doctrine_Core::getTable('DetallesCotizacion')->find($arreglo['id']);  
    
    $cotizacion=$detalles_cotizacion->getCotizaciones();
    
    $resp=$detalles_cotizacion->delete();

    if($request->isXmlHttpRequest()){
      if($resp){
          $cotizacion->calcular();
          return $this->renderText('ok');
      }else{
          return $this->renderText('No fue posible eliminar el registro');
      }
    }
    
    
    $this->redirect('detcotizaciones/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $detalles_cotizacion = $form->save();
      
      return $detalles_cotizacion;
      
    }else{
        return null;
    }
    
  }
  public function executeCalculo(sfWebRequest $request){
      
    $arreglo=$request->getParameter('detalles_cotizacion');  
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
