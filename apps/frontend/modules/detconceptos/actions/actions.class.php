<?php

/**
 * detconceptos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detconceptos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detconceptosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $detalle_cotizacion=0;  
    if($request->hasParameter("detalle_cotizacion")){
        $detalle_cotizacion=$request->getParameter("detalle_cotizacion");
    }  
    $q = Doctrine_Core::getTable('DetallesCotizacionConceptos')
      ->createQuery('a');
     if($detalle_cotizacion>0){
         $q->addWhere('a.detalle_cotizacion_id=?',$detalle_cotizacion);
     }       
      
    $this->detalles_cotizacion_conceptoss=$q->execute();
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detconceptos/list', array('dccs' => $this->detalles_cotizacion_conceptoss));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
    
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->dcc = Doctrine_Core::getTable('DetallesCotizacionConceptos')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->dcc);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detconceptos/show', array('dcc' => $this->dcc));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('detalle_cotizacion')){
        $dcc = new DetallesCotizacionConceptos();
        $dcc->setDetallesCotizacionId($request->getParameter('detalle_cotizacion'));
        $this->form = new DetallesCotizacionConceptosForm($dcc);
    }else{
        $this->form = new DetallesCotizacionConceptosForm();
    }  
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detconceptos/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DetallesCotizacionConceptosForm();

    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
         return $this->renderPartial('detconceptos/form_ajax',array('form'=>$this->form));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($detalles_cotizacion_conceptos = Doctrine_Core::getTable('DetallesCotizacionConceptos')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion_conceptos does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesCotizacionConceptosForm($detalles_cotizacion_conceptos);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detconceptos/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($detalles_cotizacion_conceptos = Doctrine_Core::getTable('DetallesCotizacionConceptos')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion_conceptos does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesCotizacionConceptosForm($detalles_cotizacion_conceptos);

    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
         return $this->renderPartial('detconceptos/form_ajax',array('form'=>$this->form));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();

    //$this->forward404Unless($detalles_cotizacion_conceptos = Doctrine_Core::getTable('DetallesCotizacionConceptos')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion_conceptos does not exist (%s).', $request->getParameter('id')));
    
    $dcc = Doctrine_Core::getTable('DetallesCotizacionConceptos')->find($request->getParameter('id'));  
    
    $dc=$dcc->getDetallesCotizacion();
    
    $resp=$dcc->delete();

    if($request->isXmlHttpRequest()){
      if($resp){
          $dc->calcularConceptos();
          return $this->renderText('ok');
      }else{
          return $this->renderText('No fue posible eliminar el registro');
      }
    }
    

    $this->redirect('detconceptos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $detalles_cotizacion_conceptos = $form->save();
      return $detalles_cotizacion_conceptos;
    }else{
      return null;
    }
  }
}
