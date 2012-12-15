<?php

require_once dirname(__FILE__).'/../lib/contratosGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/contratosGeneratorHelper.class.php';

/**
 * contratos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage contratos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contratosActions extends autoContratosActions
{
    public function executeCrearContrato(sfWebRequest $request) {
        if (!$this->getUser()->getModoContrato()) {
            $this->getUser()->setModoContrato(true);
            $this->getUser()->setRegresarA('@crear_contrato');
            $this->getUser()->setCancelarRegresarA('@contratos');
        }
        if (count($this->getUser()->getCotizaciones()) == 0) {
            $this->redirect('@seleccionar_cotizaciones');
        } else {
            //$this->getUser()->setModoCotizacion(false);
            $this->redirect('@contratos_new');
        }
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->contratos = $this->form->getObject();
    }
    
    public function executeCreate(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->contratos = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }
    
    public function executeEdit(sfWebRequest $request) {
        $this->contratos = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->contratos);
        $this->form=$this->crearFormulario($this->contratos);
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->contratos = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->contratos);
        $this->form=$this->crearFormulario($this->contratos);
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }
    
    private function crearFormulario(Contratos $contratos = null) {
        if (is_null($contratos)) {
            $cotizaciones = $this->getUser()->getCotizaciones();
            if (count($cotizaciones)>0) {
                $contrato = new Contratos();
                $contrato->setCotizacionId($cotizaciones[0]);
                $contrato->setUserId($this->getUser()->getGuardUser()->getId());
                $form = new ContratosForm($contrato);
            } else {
                $this->getUser()->setFlash('error', 'No se ha seleccionado correctamente la cotizacion.');
                $this->getUser()->setModoContrato(false);
                $this->redirect('@contratos');
            }
        } else {
            $form = new ContratosForm($contratos);
        }
        return $form;
    }
    
    protected function processForm(sfWebRequest $request, sfForm $form)
    {
      $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
      if ($form->isValid())
      {
        $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

        try {
          $contratos = $form->save();
          $this->getUser()->setModoContrato(false);
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

        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $contratos)));

        if ($request->hasParameter('_save_and_add'))
        {
          $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

          $this->redirect('@contratos_new');
        }
        else
        {
          $this->getUser()->setFlash('notice', $notice);

          $this->redirect(array('sf_route' => 'contratos_edit', 'sf_subject' => $contratos));
        }
      }
      else
      {
        $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
      }
    }
    
    public function executeInactivar(sfWebRequest $request){
        if($request->hasParameter('id')){
            $contrato=  Doctrine_Core::getTable("Contratos")->findOneBy('id',$request->getParameter('id'));
            if(!$contrato==null){
                $contrato->setIsActive(false);
                $contrato->save();
                if($request->isXmlHttpRequest()){
                    return $this->renderText("ok");
                }else{
                    $this->redirect("@contratos");
                }
            }
        }
    }
    
}
