<?php

/**
 * detcomisionistas actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detcomisionistas
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detcomisionistasActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $detalle_cotizacion=0;  
    if($request->hasParameter("detalle_cotizacion")){
        $detalle_cotizacion=$request->getParameter("detalle_cotizacion");
    }  
    $q = Doctrine_Core::getTable('DetallesCotizacionComisionistas')
      ->createQuery('a');
     if($detalle_cotizacion>0){
         $q->addWhere('a.detalle_cotizacion_id=?',$detalle_cotizacion);
     }       
      
    $this->detalles_cotizacion_comisionistass=$q->execute();
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detcomisionistas/list', array('dccos' => $this->detalles_cotizacion_comisionistass));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->dcco = Doctrine_Core::getTable('DetallesCotizacionComisionistas')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->dcco);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detcomisionistas/show', array('dcco' => $this->dcco));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }
  
  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('detalle_cotizacion')){
        $detalle=  Doctrine_Core::getTable('DetallesCotizacion')->find($request->getParameter('detalle_cotizacion'));
        $dcco = new DetallesCotizacionComisionistas();
        $dcco->setDetallesCotizacionId($detalle->getId());
        //$dcco->setCotizacionId($detalle->getCotizacionId());
        $this->form = new DetallesCotizacionComisionistasForm($dcco);
    }else{
        $this->form = new DetallesCotizacionComisionistasForm();
    }  
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detcomisionistas/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DetallesCotizacionComisionistasForm();

    $parametros=$request->getParameter($this->form->getName());
    
    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
         return $this->renderPartial('detcomisionistas/form_ajax',array('form'=>$this->form,"comisionista_id"=>$parametros['comisionista_id']));
     }else{
         return $this->renderText("ok");
     }   
    }
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($detalles_cotizacion_comisionistas = Doctrine_Core::getTable('DetallesCotizacionComisionistas')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion_comisionistas does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesCotizacionComisionistasForm($detalles_cotizacion_comisionistas);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('detcomisionistas/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($detalles_cotizacion_comisionistas = Doctrine_Core::getTable('DetallesCotizacionComisionistas')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion_comisionistas does not exist (%s).', $request->getParameter('id')));
    $this->form = new DetallesCotizacionComisionistasForm($detalles_cotizacion_comisionistas);

    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
            return $this->renderPartial('detcomisionistas/form_ajax',array('form'=>$this->form));
        }else{
            return $this->renderText("ok");
        }   
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    //$this->forward404Unless($detalles_cotizacion_comisionistas = Doctrine_Core::getTable('DetallesCotizacionComisionistas')->find(array($request->getParameter('id'))), sprintf('Object detalles_cotizacion_comisionistas does not exist (%s).', $request->getParameter('id')));
    
    $dcco = Doctrine_Core::getTable('DetallesCotizacionComisionistas')->find($request->getParameter('id'));  
    $dc=$dcco->getDetallesCotizacion();
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $conn->beginTransaction();
    $resp=$dcco->delete();
    $conn->commit();

    if($request->isXmlHttpRequest()){
      if($resp){
          $dc->calcularComisionistas();
          $dc->getCotizaciones()->calcular();
          return $this->renderText('ok');
      }else{
          return $this->renderText('No fue posible eliminar el registro');
      }
    }
    
    $this->redirect('detcomisionistas/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()){
        $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
        $conn->beginTransaction();
        $dcco = $form->save();
        $dcco->getDetallesCotizacion()->calcularComisionistas();
        $dcco->getDetallesCotizacion()->getCotizaciones()->calcular();
        $conn->commit();
        return $dcco;
    }else{
      return null;
    }
  }
}
