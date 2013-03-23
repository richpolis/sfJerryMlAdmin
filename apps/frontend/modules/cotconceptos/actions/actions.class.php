<?php

/**
 * cotconceptos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage cotconceptos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cotconceptosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $cotizacion=0;  
    if($request->hasParameter("cotizacion")){
        $cotizacion=$request->getParameter("cotizacion");
    }  
    $q = Doctrine_Core::getTable('CotizacionesConceptos')
      ->createQuery('a');
     if($cotizacion>0){
         $q->addWhere('a.cotizacion_id=?',$cotizacion);
     }         
      
    $this->cotcs = $q->execute();
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotconceptos/list', array('cotcs' => $this->cotcs));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->cotco = Doctrine_Core::getTable('CotizacionConceptos')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->cotc);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotconceptos/show', array('cotco' => $this->cotco));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }
  
  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('cotizacion')){
        $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->find($request->getParameter('cotizacion'));
        $cotc = new CotizacionesConceptos();
        $cotc->setCotizacionId($cotizacion->getId());
        $this->form = new CotizacionesConceptosForm($cotc);
    }else{
        $this->form = new CotizacionesConceptosForm();
    }  
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotconceptos/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CotizacionesConceptosForm();
    $parametros=$request->getParameter($this->form->getName());
    
    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
         return $this->renderPartial('cotconceptos/form_ajax',array('form'=>$this->form,"cotizacion_id"=>$parametros['cotizacion_id']));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($cotizaciones_conceptos = Doctrine_Core::getTable('CotizacionesConceptos')->find(array($request->getParameter('id'))), sprintf('Object cotizacion_conceptos does not exist (%s).', $request->getParameter('id')));
    $this->form = new CotizacionesConceptosForm($cotizaciones_conceptos);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotconceptos/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($cotizaciones_conceptos = Doctrine_Core::getTable('CotizacionesConceptos')->find(array($request->getParameter('id'))), sprintf('Object cotizacion_conceptos does not exist (%s).', $request->getParameter('id')));
    $this->form = new CotizacionesConceptosForm($cotizaciones_conceptos);

    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
         return $this->renderPartial('cotconceptos/form_ajax',array('form'=>$this->form));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    //$this->forward404Unless($cotizaciones_conceptos = Doctrine_Core::getTable('CotizacionesConceptos')->find(array($request->getParameter('id'))), sprintf('Object cotizacion_conceptos does not exist (%s).', $request->getParameter('id')));
    
    $cotc = Doctrine_Core::getTable('CotizacionesConceptos')->find($request->getParameter('id'));  
    
    $cot=$cotc->getCotizaciones();
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $conn->beginTransaction();
    $resp=$cotc->delete();
    $conn->commit();

    
    if($request->isXmlHttpRequest()){
      if($resp){
          $cot->calcular();
          return $this->renderText('ok');
      }else{
          return $this->renderText('No fue posible eliminar el registro');
      }
    }
    

    $this->redirect('cotconceptos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
      $conn->beginTransaction();  
      $cotc = $form->save();
      $cotc->actualizarCambios();
      $conn->commit();
      return $cotc;
    }else{
      return null;
    }
  }
}
