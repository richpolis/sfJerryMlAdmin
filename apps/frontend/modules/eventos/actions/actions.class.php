<?php

/**
 * eventos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage eventos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ks_wc_events = Doctrine_Core::getTable('KsWCEvent')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ks_wc_event = Doctrine_Core::getTable('KsWCEvent')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ks_wc_event);
    if($request->isXmlHttpRequest()){
        return $this->renderPartial('eventos/show',array('evento'=>$this->ks_wc_event));
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new KsWCEventForm();
    if($request->isXmlHttpRequest()){
        return $this->renderPartial('eventos/form_ajax',array('form'=>$this->form));
    }
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new KsWCEventForm();

    $objecto=$this->processForm($request, $this->form);
    
    if($request->isXmlHttpRequest()){
     if(is_null($objecto)){
         return $this->renderPartial('eventos/form_ajax',array('form'=>$this->form));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('KsWCEvent')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $this->form = new KsWCEventForm($ks_wc_event);
    
    if($request->isXmlHttpRequest()){
        return $this->renderPartial('eventos/form_ajax',array('form'=>$this->form));
    }
    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('KsWCEvent')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $this->form = new KsWCEventForm($ks_wc_event);

    $objecto=$this->processForm($request, $this->form);
    
    if($request->isXmlHttpRequest()){
     if(is_null($objecto)){
         return $this->renderPartial('eventos/form_ajax',array('form'=>$this->form));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('KsWCEvent')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $ks_wc_event->delete();
    
    if($request->isXmlHttpRequest()){
        return $this->renderText("delete");
    }
    
    $this->redirect('eventos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()){
      $ks_wc_event = $form->save();
      return $ks_wc_event;
      //$this->redirect('eventos/edit?id='.$ks_wc_event->getId());
    }else{
      return null;  
    }
  }
}
