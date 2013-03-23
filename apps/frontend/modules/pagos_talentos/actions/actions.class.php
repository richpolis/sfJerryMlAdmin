<?php

require_once dirname(__FILE__).'/../lib/pagos_talentosGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/pagos_talentosGeneratorHelper.class.php';

/**
 * pagos_talentos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage pagos_talentos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pagos_talentosActions extends autoPagos_talentosActions
{
    public function executeAprobarPago(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalle=  Doctrine_Core::getTable('DetallesPagosTalentos')->find($request->getParameter('generar'));
            $cont=0;
            $importe=0;
            if($detalle->getStatus()<PagosTalentosTable::$APROBADO){
               $importe+=$detalle->getImporte();
               $cont++; 
               $detalle->setStatus(PagosTalentosTable::$APROBADO); //status de aprobado, ya no se puede modificar
               $detalle->save();
            }
            
            setlocale(LC_MONETARY, 'en_US');
            $this->getUser()->setFlash('notice', "Se han aprobado $cont pago(s) talentos, operacion tiene un importe :.".  number_format($importe,2));
            $this->pagos_talentos=Doctrine_Core::getTable('PagosTalentos')->find($detalle->getPagosTalentos()->getId());
            $this->redirect("pagos_talentos_show",$this->pagos_talentos);
        }else{
            $this->redirect('@pagos_talentos');
        }  
    }    
    
    public function executeCancelarAprobarPago(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalle=  Doctrine_Core::getTable('DetallesPagosTalentos')->find($request->getParameter('generar'));
            $cont=0;
            $importe=0;
            if($detalle->getStatus()==PagosTalentosTable::$APROBADO){
               $importe+=$detalle->getImporte();
               $cont++; 
               $detalle->setStatus(PagosTalentosTable::$INCOMPLETO); //status de aprobado, ya no se puede modificar
               $detalle->save();
            }
            
            setlocale(LC_MONETARY, 'en_US');
            $this->getUser()->setFlash('notice', "Se ha cancelardo la aprobacion de $cont pago(s) talentos, operacion tiene un importe :.".  number_format($importe,2));
            $this->pagos_talentos=Doctrine_Core::getTable('PagosTalentos')->find($detalle->getPagosTalentos()->getId());
            $this->redirect("pagos_talentos_show",$this->pagos_talentos);
        }else{
            $this->redirect('@pagos_talentos');
        }  
    }
    
    public function executeAprobarPagos(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalles_cotizaciones=  Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPorPagosTalentos($request->getParameter('generar'),false);
            $importeTotal=0;
            $cont=0;
            foreach($detalles_cotizaciones as $dc){
                
                foreach($dc->getDetallesPagosTalentos() as $dpt){
                    //importe sin iva
                    if($dpt->getStatus()<PagosTalentosTable::$APROBADO && $dpt->EsValido()){
                        $dpt->setStatus(PagosTalentosTable::$APROBADO);  //aprobado
                        $dpt->save();
                        $importeTotal+=$dpt->getImporte();
                        $cont++;
                    }
                }
                
            }
            setlocale(LC_MONETARY, 'en_US');
            $this->getUser()->setFlash('notice', "Se han aprobado $cont pago(s), operacion tiene un importe :.".  money_format('%(#10n', $importeTotal));
            $this->pagos_talentos=Doctrine_Core::getTable('PagosTalentos')->find($request->getParameter('generar'));
            $this->redirect("pagos_talentos_show",$this->pagos_talentos);
        }else{
            $this->redirect('@pagos_talentos');
        }  
    }
    
    public function executeLiberarPagoTalento(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalle=  Doctrine_Core::getTable('DetallesPagosTalentos')->find($request->getParameter('generar'));
            $importe=0;
            $cont=0;
            $importeAPagar=0;
            $total=0; 
            if($detalle->getStatus()==PagosTalentosTable::$APROBADO){
               $importe+=$detalle->getImporte();
               $cont++;
               $detalle->liberarPago();
            }
            $detalle->getDetallesCotizacion()->calcularPagosTalentos();
            
            $dc=$detalle->getDetallesCotizacion();
            foreach($dc->getDetallesPagosTalentos() as $dpt){
                    //importe sin iva
                    $importeAPagar+=$dpt->getImporte();
            }
            $total=$dc->getGananciaTalentoReal();
             if($importeAPagar<$total){
                    $detallePagoTalento=new DetallesPagosTalentos();
                    //$detallePagoTalento->setUserId($this->getUser()->getGuardUser()->getId());
                    $detallePagoTalento->setDetallesCotizacionId($dc->getId());
                    $detallePagoTalento->setPagosTalentosId($detalle->getPagosTalentos()->getId());
                    $detallePagoTalento->setImporte($total-$importeAPagar);
                    $detallePagoTalento->setMetodoRecibo($detalle->getMetodoRecibo());
                    $detallePagoTalento->setIva(0.00);
                    $detallePagoTalento->save();
                    
            }
            
            setlocale(LC_MONETARY, 'en_US');
            $this->pagos_talentos=Doctrine_Core::getTable('PagosTalentos')->find($detalle->getPagosTalentos()->getId());
            $this->getUser()->setFlash('notice', "Se han liberado $cont pago(s) talentos, operacion tiene un importe :.".  number_format($importe,2));
            $this->redirect("pagos_talentos_show",$this->pagos_talentos);
        }else{
            $this->redirect('@pagos_talentos');
        }  
    }
    
    public function executeLiberarPagosTalentos(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $detalles_cotizaciones=  Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPorPagosTalentos($request->getParameter('generar'),false);
            $importe=0;
            $cont=0;
            foreach($detalles_cotizaciones as $dc){
                $importe=0;
                foreach($dc->getDetallesPagosTalentos() as $detalle){
                    $importe+=$detalle->getImporte();
                    if($detalle->getStatus()==PagosTalentosTable::$APROBADO){
                        $cont++;
                        $detalle->liberarPago();
                        $importeTotal+=$dpt->getImporte();
                    }
                }
                $dc->calcularPagosTalentos();
                $total=$dc->getGananciaTalentoReal();
                if($importe<$total){
                    $detallePagoTalento=new DetallesPagosTalentos();
                    //$detallePagoTalento->setUserId($this->getUser()->getGuardUser()->getId());
                    $detallePagoTalento->setDetallesCotizacionId($dc->getId());
                    $detallePagoTalento->setPagosTalentosId($dpt->getPagosTalentos()->getId());
                    $detallePagoTalento->setImporte($total-$importe);
                    $detallePagoTalento->setMetodoRecibo($dpt->getMetodoRecibo());
                    $detallePagoTalento->setIva(0.00);
                    $detallePagoTalento->save();
                    
                }
                
            }
            setlocale(LC_MONETARY, 'en_US');
            $this->pagos_talentos=Doctrine_Core::getTable('PagosTalentos')->find($request->getParameter('generar'));
            $this->getUser()->setFlash('notice', "Se han liberado $cont pago(s) clientes, operacion tiene un importe :.".  number_format($importe,2));
            $this->redirect("pagos_talentos_show",$this->pagos_talentos);
        }else{
            $this->redirect('@pagos_talentos');
        }  
    }

    public function executeCrearPago(sfWebRequest $request) {
        if (!$this->getUser()->getModoPagoTalento()) {
            $this->getUser()->setModoPagoTalento(true);
            $this->getUser()->setRegresarA('@crear_pago_talento');
            $this->getUser()->setCancelarRegresarA('@pagos_talentos');
        }
        if (count($this->getUser()->getTalentos()) == 0) {
            $this->redirect('@seleccionar_talentos');
        } elseif (count($this->getUser()->getCotizaciones()) == 0) {
            $this->redirect('@seleccionar_cotizaciones');
        } else {
            //$this->getUser()->setModoPago(false);
            $this->redirect('@pagos_talentos_new');
        }
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->pagos_talentos = $this->form->getObject();
        $this->pagos_talentos->save();
        $this->redirect("pagos_talentos_show",$this->pagos_talentos);
    }

    private function crearFormulario(PagosTalentos $pago = null) {
        if (is_null($pago)) {
            $talentos = $this->getUser()->getTalentos();
            if (count($talentos) > 0) {
                $talento=Doctrine_Core::getTable('Talentos')->find($talentos[0]);
                $pago = new PagosTalentos();
                $pago->setTalentoId($talentos[0]);
                $form = new PagosTalentosForm($pago);
            } else {
                $this->getUser()->setFlash('error', 'No se ha seleccionado correctamente al cliente');
                $this->getUser()->setModoPagoTalento(false);
                $this->redirect('@pagos_talentos');
            }
        } else {
            $form = new PagosTalentosForm($pago);
        }
        return $form;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->pagos_talentos = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->pagos_talentos = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->pagos_talentos);
        $this->form=$this->crearFormulario($this->pagos_talentos);
    }
    public function executeShow(sfWebRequest $request) {
        //$this->pagos_talentos = $this->getRoute()->getObject();
        $this->pagos_talentos=  Doctrine_Core::getTable('PagosTalentos')->getPagosConTalentoDetallesForId($request->getParameter('id'));
        
        if(count($this->getUser()->getCotizaciones())>0){
            $cotizaciones=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPorArreglo($this->getUser()->getCotizaciones());
            foreach($cotizaciones as $cotizacion){
                foreach($cotizacion->getDetallesCotizacion() as $dc){
                    if($dc->getTalentoId()==$this->pagos_talentos->getTalentoId()){
                        $existeDetallePagoTalento=false;
                        foreach($this->pagos_talentos->getDetallesPagosTalentos() as $dpt){
                            if($dc->getId()==$dpt->getDetallesCotizacionId()){
                                $existeDetallePagoTalento=true;
                            }
                        }
                        if(!$existeDetallePagoTalento){
                            $dp=new DetallesPagosTalentos();
                            $dp->setUserId($this->getUser()->getGuardUser()->getId());
                            $dp->setDetallesCotizacionId($dc->getId());
                            $dp->setPagosTalentosId($this->pagos_talentos->getId());
                            $dp->setImporte($dc->getGananciaTalento());
                            $dp->setMetodoRecibo(1);//factura.
                            $dp->setIva($dc->getGananciaTalento()* CotizacionesTable::$IVA);
                            $dp->save();
                        }
                    }
                }
            }
            $this->getUser()->setModoPagoTalento(false);
        }
        $this->detalles_cotizacion=  Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPorPagosTalentos($this->pagos_talentos->getId());
        
        
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->pagos_talentos = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->pagos_talentos);
        $this->form=$this->crearFormulario($this->pagos_talentos);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }
  
    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $pagos_talentos = $form->save();
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

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $pagos_talentos)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@pagos_talentos_edit');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'pagos_talentos_show', 'sf_subject' => $pagos_talentos));
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
      $this->pagos_talentos = $this->getRoute()->getObject();
      $this->pagos_talentos->setIsCerrado(true);
      $this->pagos_talentos->save();
      $this->redirect("@pagos_talentos");
     
    }
}
