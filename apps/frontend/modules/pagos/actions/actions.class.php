<?php

require_once dirname(__FILE__).'/../lib/pagosGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/pagosGeneratorHelper.class.php';

/**
 * pagos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage pagos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pagosActions extends autoPagosActions
{
    public function executeSeleccionar(sfWebRequest $request)
    {
      $this->setPage(1);  
      if($request->hasParameter('clientes_slug')){
        $this->setFilters($this->configuration->getFilterDefaults());
        $this->filters=new PagosFormFilter();
        $this->filters->setDefault('pagos_by_cliente', $request->getParameter('clientes_slug'));
        $this->setFilters($this->filters->getValues());
      }else{
        $this->setFilters($this->configuration->getFilterDefaults());
      }
      
      if($request->hasParameter('select')){
         $this->getUser()->setSeleccionarPagos(true);
         $this->getUser()->setRegresarA($request->getParameter('goto'));
         $this->getUser()->setCancelarRegresarA($request->getParameter('goto'));
      }
      
      if($request->hasParameter('cliente')){
        $this->getUser()->setClienteCotizaciones($request->getParameter('cliente'));
      }
      
           
      $this->redirect('@pagos');
    }
    
    public function executeAprobarPago(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalle=  Doctrine_Core::getTable('DetallesPagos')->find($request->getParameter('generar'));
            $cont=0;
            $importe=0;
            if($detalle->getStatus()<PagosTable::$APROBADO){
               $importe+=$detalle->getImporte();
               $cont++; 
               $detalle->setStatus(PagosTable::$APROBADO); //status de aprobado, ya no se puede modificar
               $detalle->save();
            }
            setlocale(LC_MONETARY, 'en_US');
            $this->pagos=Doctrine_Core::getTable('Pagos')->find($detalle->getPagos()->getId());
            $this->getUser()->setFlash('notice', "Se han aprobado $cont pago(s), operacion tiene un importe :.".  number_format($importe,2));
            $this->redirect("pagos_show",$this->pagos);
        }else{
            $this->redirect('@pagos');
        }  
    }
    
            
    public function executeAprobarPagos(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $cotizaciones=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPorPago($request->getParameter('generar'),false);
            $cont=0;
            $importe=0;
            foreach($cotizaciones as $cotizacion){
                foreach($cotizacion->getDetallesPagos() as $detalle){
                    if($detalle->getStatus()<PagosTable::$APROBADO){
                        $importe+=$detalle->getImporte();
                        $cont++; 
                        $detalle->setStatus(PagosTable::$APROBADO); //status de aprobado, ya no se puede modificar
                        $detalle->save();
                    }
                }
            }
            setlocale(LC_MONETARY, 'en_US');
            $this->pagos=Doctrine_Core::getTable('Pagos')->find($request->getParameter('generar'));
            $this->getUser()->setFlash('notice', "Se han aprobado $cont pago(s), operacion tiene un importe :.".  number_format($importe,2));
            $this->redirect("pagos_show",$this->pagos);
        }else{
            $this->redirect('@pagos');
        }  
    }
    
    public function executeLiberarPago(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalle=  Doctrine_Core::getTable('DetallesPagos')->find($request->getParameter('generar'));
            $importe=0;
            $cont=0;
            if($detalle->getStatus()==PagosTable::$APROBADO){
               $importe+=$detalle->getImporte();
               $cont++;
               $detalle->liberarPago();
            }
            
            $detalle->getCotizaciones()->calcularPagos();
            setlocale(LC_MONETARY, 'en_US');
            $this->pagos=Doctrine_Core::getTable('Pagos')->find($detalle->getPagos()->getId());
            $this->getUser()->setFlash('notice', "Se han liberado $cont pago(s) clientes, operacion tiene un importe :.".  number_format($importe,2));
            $this->redirect("pagos_show",$this->pagos);
        }else{
            $this->redirect('@pagos');
        }  
    }
    
    public function executeLiberarPagos(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $cotizaciones=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPorPago($request->getParameter('generar'),false);
            $importe=0;
            $cont=0;
            foreach($cotizaciones as $cotizacion){
                foreach($cotizacion->getDetallesPagos() as $detalle){
                    if($detalle->getStatus()==PagosTable::$APROBADO){
                        $importe+=$detalle->getImporte();
                        $cont++;
                        $detalle->liberarPago();
                    }
                }
                $cotizacion->calcularPagos();
            }
            setlocale(LC_MONETARY, 'en_US');
            $this->pagos=Doctrine_Core::getTable('Pagos')->find($request->getParameter('generar'));
            $this->getUser()->setFlash('notice', "Se han liberado $cont pago(s) clientes, operacion tiene un importe :.".  number_format($importe,2));
            $this->redirect("pagos_show",$this->pagos);
        }else{
            $this->redirect('@pagos');
        }  
    }

    public function executeCrearPago(sfWebRequest $request) {
        if (!$this->getUser()->getModoPago()) {
            $this->getUser()->setModoPago(true);
            $this->getUser()->setSeleccionarCotizaciones(true);
            $this->getUser()->setSeleccionarCliente(true);
            $this->getUser()->setRegresarA('@crear_pago');
            $this->getUser()->setCancelarRegresarA('@pagos');
        }
        if ($this->getUser()->getCliente() == 0) {
            $this->redirect('@seleccionar_cliente');
        } elseif (count($this->getUser()->getCotizaciones()) == 0) {
            $this->redirect('@seleccionar_cotizaciones');
        } else {
            //$this->getUser()->setModoPago(false);
            $this->redirect('@pagos_new');
        }
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->pagos = $this->form->getObject();
        $this->pagos->save();
        $this->redirect("pagos_show",$this->pagos);
    }

    private function crearFormulario(Pagos $pago = null) {
        if (is_null($pago)) {
            $cliente = $this->getUser()->getCliente();
            if ($cliente > 0) {
                $pago = new Pagos();
                $pago->setClienteId($cliente);
                $form = new PagosForm($pago);
            } else {
                $this->getUser()->setFlash('error', 'No se ha seleccionado correctamente al cliente');
                $this->getUser()->setModoPago(false);
                $this->redirect('@pagos');
            }
        } else {
            $form = new PagosForm($pago);
        }
        return $form;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->pagos = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->pagos = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->pagos);
        $this->form=$this->crearFormulario($this->pagos);
    }
    public function executeShow(sfWebRequest $request) {
        $this->pagos = $this->getRoute()->getObject();
        
        if(count($this->getUser()->getCotizaciones())>0){
            $cotizaciones=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPorArreglo($this->getUser()->getCotizaciones());
            foreach($cotizaciones as $cotizacion){
                $dp=new DetallesPagos();
                $dp->setCotizaciones($cotizacion);
                $dp->setUserId($this->getUser()->getGuardUser()->getId());
                $dp->setPagos($this->pagos);
                $dp->setImporte(0.00);
                $dp->setIva(0.00);
                $dp->save();
            }
            $this->getUser()->setModoPago(false);
        }
        
        $this->cotizaciones=Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPorPago($this->pagos->getId(),false);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->pagos = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->pagos);
        $this->form=$this->crearFormulario($this->pagos);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }
  
    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $pagos = $form->save();
                //$this->getUser()->setModoPago(false);
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $pagos)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@pagos_edit');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'pagos_show', 'sf_subject' => $pagos));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }
    public function executeIndex(sfWebRequest $request)
    {
      // sorting
      if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
      {
        $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
      }

      // pager
      if ($request->getParameter('page'))
      {
        $this->setPage($request->getParameter('page'));
      }

      $this->pager = $this->getPager();
      $this->sort = $this->getSort();
      
          
      
    }
    
    public function executeClose(sfWebRequest $request)
    {
      $this->pagos = $this->getRoute()->getObject();
      $this->pagos->setIsCerrado(true);
      $this->pagos->save();
      $this->redirect("@pagos");
     
    }
}    

