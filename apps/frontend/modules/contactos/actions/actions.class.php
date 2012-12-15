<?php

require_once dirname(__FILE__).'/../lib/contactosGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/contactosGeneratorHelper.class.php';

/**
 * contactos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage contactos
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contactosActions extends autoContactosActions
{
    public function executeSeleccionar(sfWebRequest $request)
    {
      $this->setPage(1);  
      if($request->hasParameter('contactos_slug')){
        $this->setFilters($this->configuration->getFilterDefaults());
        $this->filters=new ContactosFormFilter();
        $this->filters->setDefault('contacto_by_cliente', $request->getParameter('contactos_slug'));
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
      $contacto = $this->getRoute()->getObject();
      $this->getUser()->setContacto($contacto->getId());
      if($this->getUser()->getCliente()>0){
        $existe=false;
        $clientes=$contacto->getClientesContactos();
        $idCliente=$this->getUser()->getCliente();
        foreach($clientes as $cliente){
            if($cliente->getClienteId()==$idCliente){
                $existe=true;
            }
        }
        if(!$existe){
            $clientecontacto=new ClientesContactos();
            $clientecontacto->setClienteId($idCliente);
            $clientecontacto->setContactos($contacto);
            $clientecontacto->save();
        }
      }
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
          $this->getUser()->setSeleccionarContacto(false);
      }
      
      $ruta=$this->getUser()->getCancelarRegresarA();
      $this->redirect($ruta);
    }
    
    public function executeNew(sfWebRequest $request)
    {
      $this->form = $this->configuration->getForm();
      $this->contactos = $this->form->getObject();
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloDatosNecesarios();
      }else{
          $this->ajax=false;
      }
      if($request->isXmlHttpRequest()){
          return $this->renderPartial('form',array(
              'contactos' => $this->contactos, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>$this->ajax));
         
      }
      
      
    }

    public function executeCreate(sfWebRequest $request)
    {
      $respuesta=false;
      $this->form = $this->configuration->getForm();
      $this->contactos = $this->form->getObject();
      
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloDatosNecesarios();
      }else{
          $this->ajax=false;
      }
      
      $respuesta=$this->processForm($request, $this->form);
      
      
     if($request->isXmlHttpRequest()){
         if($respuesta){
          return $this->renderText("ok");   
         }else{
          return $this->renderPartial('form',array(
              'contactos' => $this->contactos, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>$this->ajax));
         } 
      }
      
      $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
      $this->contactos = $this->getRoute()->getObject();
      $this->form = $this->configuration->getForm($this->contactos);
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloDatosNecesarios();
      }else{
          $this->ajax=false;
      }
     if($request->isXmlHttpRequest()){
          return $this->renderPartial('form',array(
              'contactos' => $this->contactos, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>$this->ajax));
      }
    }

    public function executeUpdate(sfWebRequest $request)
    {
      $respuesta=false;  
      $this->contactos = $this->getRoute()->getObject();
      $this->form = $this->configuration->getForm($this->contactos);

      $respuesta=$this->processForm($request, $this->form);
      
      if($request->hasParameter('ajax')){
          $this->ajax=true;
          $this->form->configureSoloDatosNecesarios();
      }else{
          $this->ajax=false;
      }
      
      if($request->isXmlHttpRequest()){
         if($respuesta){
          return $this->renderText("ok");   
         }else{
          return $this->renderPartial('form',array(
              'contactos' => $this->contactos, 
              'form' => $this->form, 
              'configuration' => $this->configuration, 
              'helper' => $this->helper,
              'ajax'=>$this->ajax));
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
          $contactos = $form->save();
          if($this->getUser()->getCliente()>0){
              $cliente=Doctrine_Core::getTable('Clientes')->find($this->getUser()->getCliente());
              $clientecontacto=new ClientesContactos();
              $clientecontacto->setClienteId($cliente->getId());
              $clientecontacto->setContactoId($contactos->getId());
              $clientecontacto->save();
          }
          
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

        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $contactos)));

        if ($request->hasParameter('_save_and_add'))
        {
          $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

          $this->redirect('@contactos_new');
        }
        else
        {
          $this->getUser()->setFlash('notice', $notice);
          
          if($this->getUser()->getSeleccionarContacto()){
              $this->getUser()->setContacto($contactos->getId());
              return true;
          }else{
              $this->redirect(array('sf_route' => 'contactos_edit', 'sf_subject' => $contactos));
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
      $this->contactos = $this->getRoute()->getObject();
      $this->cotizaciones=$this->contactos->getCotizaciones();
      $this->clientes=$this->contactos->getClientes();
     
    }
    
    public function executeInactive(sfWebRequest $request)
    {
      $this->contactos = $this->getRoute()->getObject();
      $this->contactos->setIsActive(false);
      $this->contactos->save();
      $this->redirect("@contactos");
    }
    
    public function executeActive(sfWebRequest $request)
    {
      $this->contactos = $this->getRoute()->getObject();
      $this->contactos->setIsActive(true);
      $this->contactos->save();
      $this->redirect("@contactos");
     
    }
}
