<?php

require_once dirname(__FILE__).'/../lib/clientesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/clientesGeneratorHelper.class.php';

/**
 * clientes actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage clientes
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class clientesActions extends autoClientesActions
{
    public function executeSeleccionar(sfWebRequest $request)
    {
      $this->setPage(1);  
      if($request->hasParameter('contactos_slug')){
        $this->setFilters($this->configuration->getFilterDefaults());
        $this->filters=new ClientesFormFilter();
        $this->filters->setDefault('cliente_by_contactos', $request->getParameter('contactos_slug'));
        $this->setFilters($this->filters->getValues());
      }else{
        $this->setFilters($this->configuration->getFilterDefaults());
      }
      $this->pager = $this->getPager();
      $this->sort = $this->getSort();

      $this->setTemplate('index');
    }
    
    public function executeListSelect(sfWebRequest $request)
    {
      $cliente = $this->getRoute()->getObject();
      $this->getUser()->setCliente($cliente->getId());
      $ruta=$this->getUser()->getRegresarA();
      $this->redirect($ruta);
    }
    public function executeCancelarSeleccion(sfWebRequest $request)
    {
      if($this->getUser()->getModoPrecotizacion()){  
          $this->getUser()->setModoPrecotizacion(false);
      }elseif($this->getUser()->getModoCotizacion()){
          $this->getUser()->setModoCotizacion(false);
      }elseif($this->getUser()->getModoContrato()){
          $this->getUser()->setModoContrato(false);
      }elseif($this->getUser()->getModoPago()){
          $this->getUser()->setModoPago(false);
      }elseif($this->getUser()->getModoPagoTalento()){
          $this->getUser()->setModoPagoTalento(false);
      }else{
          $this->getUser()->setSeleccionarCliente(false);
      }
      
      $ruta=$this->getUser()->getCancelarRegresarA();
      $this->redirect($ruta);
    }
    public function executeNew(sfWebRequest $request)
    {
      $this->form = $this->configuration->getForm();
      $this->clientes = $this->form->getObject();
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloRazonSocial();
      }else{
          $this->ajax=false;
      }
      if($request->isXmlHttpRequest()){
          return $this->renderPartial('form',array(
              'clientes' => $this->clientes, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>'true'));
      }
      
      
    }

    public function executeCreate(sfWebRequest $request)
    {
      $respuesta=false;
      $this->form = $this->configuration->getForm();
      $this->clientes = $this->form->getObject();

      $respuesta=$this->processForm($request, $this->form);

      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloRazonSocial();
      }else{
          $this->ajax=false;
      }
     if($request->isXmlHttpRequest()){
        if($respuesta){
          return $this->renderText("ok");
        }else{
          return $this->renderPartial('form',array(
              'clientes' => $this->clientes, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>'true'));
        }  
      }
      
      $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
      $this->clientes = $this->getRoute()->getObject();
      $this->form = $this->configuration->getForm($this->clientes);
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloRazonSocial();
      }else{
          $this->ajax=false;
      }
     if($request->isXmlHttpRequest()){
          return $this->renderPartial('form',array(
              'clientes' => $this->clientes, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>'true'));
      }
    }

    public function executeUpdate(sfWebRequest $request)
    {
      $respuesta=false;  
      $this->clientes = $this->getRoute()->getObject();
      $this->form = $this->configuration->getForm($this->clientes);

      $respuesta=$this->processForm($request, $this->form);
      
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloRazonSocial();
      }else{
          $this->ajax=false;
      }
      
     if($request->isXmlHttpRequest()){
         if($respuesta){
          return $this->renderText("ok");
         }else{
        
          return $this->renderPartial('form',array(
              'clientes' => $this->clientes, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>'true'));
         } 
      }
      

      $this->setTemplate('edit');
    }
    protected function processForm(sfWebRequest $request, sfForm $form)
    {
      $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
      if ($form->isValid())
      {
        $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

        try {
          $clientes = $form->save();
        } catch (Doctrine_Validator_Exception $e) {

          $errorStack = $form->getObject()->getErrorStack();

          $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
          foreach ($errorStack as $field => $errors) {
              $message .= "$field (" . implode(", ", $errors) . "), ";
          }
          $message = trim($message, ', ');

          $this->getUser()->setFlash('error', $message);
          return sfView::SUCCESS;
        }

        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $clientes)));

        if ($request->hasParameter('_save_and_add'))
        {
          $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

          $this->redirect('@clientes_new');
        }
        else
        {
          $this->getUser()->setFlash('notice', $notice);
          
          if($this->getUser()->getSeleccionarCliente()){
              $this->getUser()->setCliente($clientes->getId());
              return true;
          }else{
              $this->redirect(array('sf_route' => 'clientes_edit', 'sf_subject' => $clientes));
          }

          
        }
      }
      else
      {
        $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        return false;
      }
    }
    public function executeShow(sfWebRequest $request)
    {
      $this->clientes = $this->getRoute()->getObject();
      //$this->clientes=  Doctrine_Core::getTable('Clientes')->getClientesShow($request->getParameter('id'));
      $this->pagos=$this->clientes->getPagos();
      $this->cotizaciones=$this->clientes->getCotizaciones();
      $this->contactos=$this->clientes->getContactos();
      
    }
    
    public function executeInactive(sfWebRequest $request)
    {
      $this->clientes = $this->getRoute()->getObject();
      $this->clientes->setIsActive(false);
      $this->clientes->save();
      $this->redirect("@clientes");
    }
    
    public function executeActive(sfWebRequest $request)
    {
      $this->clientes = $this->getRoute()->getObject();
      $this->clientes->setIsActive(true);
      $this->clientes->save();
      $this->redirect("@clientes");
     
    }
    
    
}
