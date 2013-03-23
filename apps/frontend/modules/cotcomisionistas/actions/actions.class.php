<?php

/**
 * cotcomisionistas actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage cotcomisionistas
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cotcomisionistasActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $cotizacion=0;  
    if($request->hasParameter("cotizacion")){
        $cotizacion=$request->getParameter("cotizacion");
    }  
    $q = Doctrine_Core::getTable('CotizacionesComisionistas')
      ->createQuery('a');
     if($cotizacion>0){
         $q->addWhere('a.cotizacion_id=?',$cotizacion);
     }       
      
    $this->cotcos=$q->execute();
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotcomisionistas/list', array('cotcos' => $this->cotcos));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->cotco = Doctrine_Core::getTable('CotizacionesComisionistas')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->cotco);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotcomisionistas/show', array('cotco' => $this->cotco));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
  }
  
  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('cotizacion')){
        $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->find($request->getParameter('cotizacion'));
        $cotco = new CotizacionesComisionistas();
        $cotco->setCotizacionId($cotizacion->getId());
        $this->form = new CotizacionesComisionistasForm($cotco);
    }else{
        $this->form = new CotizacionesComisionistasForm();
    }  
    
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotcomisionistas/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CotizacionesComisionistasForm();

    $parametros=$request->getParameter($this->form->getName());
    
    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
         return $this->renderPartial('cotcomisionistas/form_ajax',array('form'=>$this->form,"comisionista_id"=>$parametros['comisionista_id']));
     }else{
         return $this->renderText("ok");
     }   
    }
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($cotco = Doctrine_Core::getTable('CotizacionesComisionistas')->find(array($request->getParameter('id'))), sprintf('Object cotco does not exist (%s).', $request->getParameter('id')));
    $this->form = new CotizacionesComisionistasForm($cotco);
    if($request->isXmlHttpRequest()){
        try{
           return $this->renderPartial('cotcomisionistas/form_ajax', array('form' => $this->form));
       } catch(Exception $e){
           throw $e->getMessage();
       }
    }
    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($cotco = Doctrine_Core::getTable('CotizacionesComisionistas')->find(array($request->getParameter('id'))), sprintf('Object cotco does not exist (%s).', $request->getParameter('id')));
    $this->form = new CotizacionesComisionistasForm($cotco);

    $objeto=$this->processForm($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objeto)){
            return $this->renderPartial('cotcomisionistas/form_ajax',array('form'=>$this->form));
        }else{
            return $this->renderText("ok");
        }   
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    //$this->forward404Unless($cotco = Doctrine_Core::getTable('CotizacionesComisionistas')->find(array($request->getParameter('id'))), sprintf('Object cotco does not exist (%s).', $request->getParameter('id')));
    
    $cotco = Doctrine_Core::getTable('CotizacionesComisionistas')->find($request->getParameter('id'));  
    $cot=$cotco->getCotizaciones();
    
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $conn->beginTransaction();
    $resp=$cotco->delete();
    $conn->commit();
           

    if($request->isXmlHttpRequest()){
      if($resp){
          $cot->calcular();
          return $this->renderText('ok');
      }else{
          return $this->renderText('No fue posible eliminar el registro');
      }
    }
    
    $this->redirect('cotcomisionistas/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()){
        $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
        $conn->beginTransaction(); 
        $cotco = $form->save();
        $cotco->actualizarCambios();
        $conn->commit();
        return $cotco;
    }else{
      return null;
    }
  }
}
