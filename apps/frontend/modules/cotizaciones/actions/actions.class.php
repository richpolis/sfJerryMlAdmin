<?php

require_once dirname(__FILE__) . '/../lib/cotizacionesGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/cotizacionesGeneratorHelper.class.php';

/**
 * cotizaciones actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage cotizaciones
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cotizacionesActions extends autoCotizacionesActions {

    
    public function executeSeleccionar(sfWebRequest $request)
    {
        
      $this->setPage(1);  
      if($request->hasParameter('clientes_slug')){
        $this->setFilters($this->configuration->getFilterDefaults());
        $this->filters=new CotizacionesFormFilter();
        $this->filters->setDefault('cotizaciones_by_cliente', $request->getParameter('clientes_slug'));
        $this->setFilters($this->filters->getValues());
      }else{
        $this->setFilters($this->configuration->getFilterDefaults());
      }
      
      if($request->hasParameter('modo')){
         $this->getUser()->activarModo($request->getParameter('modo')); 
         $this->getUser()->setRegresarA($request->getParameter('goto'));
         $this->getUser()->setCancelarRegresarA($request->getParameter('goto'));
      }
      
      if($request->hasParameter('cliente')){
        $this->getUser()->setCliente($request->getParameter('cliente'));
      }
      
      if($request->hasParameter('contacto')){
        $this->getUser()->setContacto($request->getParameter('contacto'));
      }
      
      if($request->hasParameter('talento')){
        $this->getUser()->setTalentos(array($request->getParameter('contacto')));
      }
      
      if($request->hasParameter('pago')){
        $this->getUser()->setPago($request->getParameter('pago'));
      }
      
      if($request->hasParameter('pago_talento')){
        $this->getUser()->setPagoTalento($request->getParameter('pago_talento'));
      }
      
      
      $this->redirect('@cotizaciones');
    }
    public function executeBatchSelect(sfWebRequest $request)
    {
      $ids = $request->getParameter('ids');
      $this->getUser()->addCotizaciones($ids);
      if($this->getUser()->getModoContrato() && count($this->getUser()->getCotizaciones())>0){
          $this->redirect('cotizaciones/finalizarSeleccion');
      }
      $this->redirect('@cotizaciones');
    }
    public function executeListSelect(sfWebRequest $request)
    {
      $cotizacion = $this->getRoute()->getObject();
      $this->getUser()->addCotizaciones($cotizacion->getId());
      if($this->getUser()->getModoContrato() && count($this->getUser()->getCotizaciones())>0){
          $this->redirect('cotizaciones/finalizarSeleccion');
      }
      $this->redirect('@cotizaciones');
    }
    
    public function executeListRemove(sfWebRequest $request)
    {
      $cotizacion = $this->getRoute()->getObject();
      $this->getUser()->removeCotizaciones($cotizacion->getId());
      $this->redirect('@cotizaciones');
    }
    
    public function executeCancelarSeleccion(sfWebRequest $request)
    {
      if($this->getUser()->getModoContrato()){
          $this->getUser()->setModoContrato(false);
      }elseif($this->getUser()->getModoPago()){
          $this->getUser()->setModoPago(false);
      }elseif($this->getUser()->getModoPagoTalento()){
          $this->getUser()->setModoPagoTalento(false);
      }else{
          $this->getUser()->setSeleccionarCotizaciones(false);
      }
      
      $ruta=$this->getUser()->getCancelarRegresarA();
      $this->redirect($ruta);
    }
    
    public function executeFinalizarSeleccion(sfWebRequest $request)
    {
      $ruta=$this->getUser()->getRegresarA();
      $this->redirect($ruta);
    }
    
    public function executeVistaPrevia(sfWebRequest $request) {
        $this->cotizaciones = Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
        $this->detalles_cotizaciones=  $this->cotizaciones->getDetallesCotizacion();
        $this->setLayout('simple_layout');
    }
    public function executeGenerarPdf(sfWebRequest $request) {
        ProjectConfiguration::registerMPDF();
        $html = $request->getPostParameter('html');
        
        $footer=  Doctrine_Core::getTable('Configuracion')->getSeccion('footer-cotizacion');
        
        $mpdf = new mPDF('es_ES','Letter','12px','Arial',15,15,15,15,16,13);
        $mpdf->useOnlyCoreFonts = true;
        
        if(!$footer==null){
            $mpdf->SetHTMLFooter($footer->getContenido());
        }
        
        // load a stylesheet
        //$stylesheet = file_get_contents(sfConfig::get('sf_web_dir').'/css/mypdf.css');
        //$mpdf->WriteHTML($stylesheet,1); // el parámetro le dice que sólo es css y no contenido html
        $mpdf->WriteHTML($html,2);

        $mpdf->Output('mpdf.pdf','D');
        throw new sfStopException();
    }
    protected function generarPdf($cotizacion,$html) {
        ProjectConfiguration::registerMPDF();
        
        $footer=  Doctrine_Core::getTable('Configuracion')->getSeccion('footer-cotizacion');
        
        $mpdf = new mPDF('es_ES','Letter','12px','Arial',15,15,15,15,16,13);
        $mpdf->useOnlyCoreFonts = true;
        
        if(!$footer==null){
            $mpdf->SetHTMLFooter($footer->getContenido());
        }
        
        $mpdf->WriteHTML($html,2);
        
        $file="Cotizacion_".$cotizacion->getId().'.pdf';
        if(!file_exists(sfConfig::get('sf_upload_dir').'/cotizaciones/'.$cotizacion->getId().'/')){
            mkdir(sfConfig::get('sf_upload_dir').'/cotizaciones/'.$cotizacion->getId().'/', 0777);
        }
        $cont=1;
        while (file_exists(sfConfig::get('sf_upload_dir').'/cotizaciones/'.$cotizacion->getId().'/'.$file)){
            $file="Cotizacion_".$cotizacion->getId().'v'.$cont.'.pdf';
            $cont++;
        }
        
        $filename=sfConfig::get('sf_upload_dir').'/cotizaciones/'.$cotizacion->getId().'/'.$file;
        
        $mpdf->Output($filename,'F');
        return $file;
    }
            
    public function executeAprobarCotizacion(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            if($cotizacion->getSubtotal()==0){
                $cotizacion->calcular();
            }
            if($cotizacion->validarAprobar()){
                if($cotizacion->getPdf()=="sin_archivo.pdf"){
                    $html=$this->getPartial('vistaPrevia',array('cotizaciones'=>$cotizacion,'detalles_cotizaciones'=>$cotizacion->getDetallesCotizacion()));
                    $pdf=$this->generarPdf($cotizacion, $html);
                    $cotizacion->setPdf($pdf);
                }
                if($request->hasParameter('enviar_cotizacion')){
                   $enviarACliente=true;
                }
                $cotizacion->setStatus(CotizacionesTable::$APROBADA);//Aprobado por el cliente
                $cotizacion->save();
                $cotizacion->aprobarCotizacion();
                if($enviarACliente){
                    $this->redirect("home/enviarCotizacion?cotizacion=".$cotizacion->getId()."&enviarAutomatico=true");
                }
                $this->getUser()->setFlash('notice', 'Se ha aprobado la cotizacion.');
                //$this->redirect("cotizaciones_show",$cotizacion);
                $this->redirect("@homepage");
            }else{
                $this->getUser()->setFlash('error', $cotizacion->getErroresAprobar());
                $this->redirect("cotizaciones_show",$cotizacion);
            }
        }else{
            $this->redirect('@cotizaciones');
        }  
    }
    
    public function executeCancelarCotizacion(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            $cotizacion->setStatus(CotizacionesTable::$INCOMPLETO);//cancelada
            $cotizacion->save();
            $cotizacion->cancelarCotizacion();
            $this->getUser()->setFlash('notice', 'Se ha cancelado la aprobacion de la cotizacion.');
            $this->redirect("cotizaciones_show",$cotizacion);
        }else{
            $this->redirect('@cotizaciones');
        }  
    }
    
    public function executeLiberarPagosCotizacion(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            $cotizacion->setStatus(CotizacionesTable::$PAGOS_LIBERADOS);//pagos liberados
            $cotizacion->save();
            $cotizacion->liberarPagosCotizacion();
            $this->getUser()->setFlash('notice', 'Se ha liberado el pago a los talentos.');
            $this->redirect("cotizaciones_show",$cotizacion);
        }else{
            $this->redirect('@cotizaciones');
        }  
    }
    
    public function executePrepararEnvioCotizacion(sfWebRequest $request) {
        if($request->hasParameter('generar')){
            $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            if($cotizacion==null){
                $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->find($request->getParameter('generar'));
            }
            if($cotizacion->validarAprobar()){
                $html=$this->getPartial('vistaPrevia',array('cotizaciones'=>$cotizacion,'detalles_cotizaciones'=>$cotizacion->getDetallesCotizacion()));
                $pdf=$this->generarPdf($cotizacion, $html);
                $cotizacion->setPdf($pdf);
                //if($cotizacion->getStatus()<=CotizacionesTable::$MEDIACION){
                //    $cotizacion->setStatus(CotizacionesTable::$MEDIACION);//Enviado a cliente
                //}
                $cotizacion->save(); 
                $this->redirect('home/enviarCotizacion?cotizacion='.$cotizacion->getId());
            }else{
                $this->getUser()->setFlash('error', 'Revisar que los registros esten completos.');
                $this->redirect("cotizaciones_show",$cotizacion);
            }
        }else{
            $this->redirect('@cotizaciones');
        }
    }

    public function executeCrearCotizacion(sfWebRequest $request) {
        if (!$this->getUser()->getModoCotizacion()) {
            $this->getUser()->setModoCotizacion(true);
            $this->getUser()->setSeleccionarTalentos(true);
            $this->getUser()->setRegresarA('@crear_cotizacion');
            $this->getUser()->setCancelarRegresarA('@cotizaciones');
        }
        if ($this->getUser()->getCliente() == 0) {
            $this->redirect('@seleccionar_cliente');
        } elseif ($this->getUser()->getContacto() == 0) {
            $this->redirect('@seleccionar_contacto');
        } elseif (count($this->getUser()->getTalentos()) == 0) {
            $this->redirect('@seleccionar_talentos');
        } else {
            //$this->getUser()->setModoCotizacion(false);
            $this->redirect('@cotizaciones_new');
        }
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->cotizaciones = $this->form->getObject();
        $this->cotizaciones->save();
        $this->redirect("cotizaciones_show",$this->cotizaciones);
    }

    private function crearFormulario(Cotizaciones $cotizacion = null,$crear=false) {
        if (is_null($cotizacion)) {
            $cliente = $this->getUser()->getCliente();
            $contacto = $this->getUser()->getContacto();
            $talentos = $this->getUser()->getTalentos();
            if (($cliente > 0) && ($contacto > 0)) {
                $cotizacion = new Cotizaciones();
                $cotizacion->setClienteId($cliente);
                $cotizacion->setContactoId($contacto);
                $cotizacion->setUserId($this->getUser()->getGuardUser()->getId());
                $cotizacion->setFechaDesde(date("Y-m-d"));
                $cotizacion->setFechaHasta(date("Y-m-d"));
                $form = new CotizacionesForm($cotizacion);
            } else {
                $this->getUser()->setFlash('error', 'No se ha seleccionado correctamente el cliente y contacto.');
                $this->getUser()->setModoCotizacion(false);
                $this->redirect('@cotizaciones');
                
            }
        } else {
            $form = new CotizacionesForm($cotizacion);
        }
        return $form;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->cotizaciones = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->cotizaciones = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->cotizaciones);
        $this->form=$this->crearFormulario($this->cotizaciones);
    }
    public function executeShow(sfWebRequest $request) {
        //$this->cotizaciones = $this->getRoute()->getObject();
        $this->cotizaciones=Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('id'));
        
        if(count($this->getUser()->getTalentos())>0){
            $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
            $conn->beginTransaction();
            $talentos=  Doctrine_Core::getTable('Talentos')->getTalentosPorArreglo($this->getUser()->getTalentos());
            foreach($talentos as $talento){
                $existeTalento=false;
                $DetalleDeCotizacionId=0;
                foreach($this->cotizaciones->getDetallesCotizacion() as $detalle){
                    if($detalle->getTalentoId()==$talento->getId()){
                        $existeTalento=true;
                        $DetalleDeCotizacionId=$detalle->getId();
                    }
                }
                if(!$existeTalento){
                    $dt=new DetallesCotizacion();
                    $dt->setTalentos($talento);
                    $dt->setCotizacionId($this->cotizaciones->getId());
                    $dt->setActividad($talento->getDescripcion());
                    $dt->setMargenJerryMl($talento->getMargenJerryMl());
                    $dt->save();
                    $this->addTalentoConceptosComisionistasEventos($this->cotizaciones,$dt);
                    $DetalleDeCotizacionId=$dt->getId();
                }
                if(count($this->getUser()->getEventos($talento->getId()))){
                    
                    $eventos=  Doctrine_Core::getTable('KsWCEvent')->getEventosPorArreglo($this->getUser()->getEventos($talento->getId()));
                    foreach($eventos as $evento){
                        $existeEvento=false;
                        $cotizacionEventos=Doctrine_Core::getTable('KsWCEvent')->findBy('detalles_cotizacion_id', $DetalleDeCotizacionId);
                        foreach($cotizacionEventos as $cotizacionEvento){
                            if($evento->getId()==$cotizacionEvento->getId()){
                                $existeEvento=true;
                            }
                        }
                        if(!$existeEvento){
                            $evento->setDetallesCotizacionId($DetalleDeCotizacionId);
                            $evento->save();
                        }
                    }
                    $this->getUser()->setEventos(array(),$talento->getId());
                }
                
            }
            $conn->commit();
        }
        $this->getUser()->setModoCotizacion(false);
        $this->getUser()->setTalentosCotizacion(array());
        $this->getUser()->setCotizacion(0);
        $this->detalles_cotizaciones=  Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPorCotizacion($this->cotizaciones->getId());
        
        
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->cotizaciones = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->cotizaciones);
        $this->form=$this->crearFormulario($this->cotizaciones);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }
  
    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $cotizaciones = $form->save();
                $cotizaciones->actualizarEventos();
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

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $cotizaciones)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@cotizaciones_edit');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'cotizaciones_show', 'sf_subject' => $cotizaciones));
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
      
      if(count($this->getUser()->getCotizaciones())){
          $this->cotizacionesSeleccionadas=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPorArreglo($this->getUser()->getCotizaciones());
      }else{
          $this->cotizacionesSeleccionadas=null;
      }
      
      
    }
    public function executeCalcularImportes(sfWebRequest $request)
    {
        if($request->hasParameter('id')){
            $this->cotizaciones=Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('id'));
            $this->cotizaciones->calcular();
            if($request->isXmlHttpRequest()){
                try{
                   return $this->renderPartial('calculo_importes', array('cotizaciones' => $this->cotizaciones));
               } catch(Exception $e){
                   throw $e->getMessage();
               }
            }
            
        }else{
            return ;
        }
        
    }
    public function executeAjaxRegistroOrder(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest()) {
            $cotizacion = Doctrine_Core::getTable('Cotizaciones')->find($request->getParameter("id"));
            $registro_order = $request->getParameter('detalles_cotizacion');
            foreach($registro_order as $order=>$id)
            {
                $registro=  Doctrine_Core::getTable('DetallesCotizacion')->find($id);
                //if($registro->getPosition()!=($order+1)){
                  try{
                        $registro->setPosition($order+1);
                        $registro->save();

                  }catch(Exception $e){
                        return $this->renderText($e->getMessage());
                  }
                //}
            }
            $this->detalles_cotizaciones=  Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPorCotizacion($cotizacion->getId());
            return $this->renderPartial('talentos',array('cotizaciones' => $cotizacion,"detalles_cotizaciones"=>$this->detalles_cotizaciones));
            
        }
        else {
            $this->redirect404();
        }
    }
    public function executeInactivar(sfWebRequest $request){
        if($request->hasParameter('id')){
            $cotizacion=  Doctrine_Core::getTable("Cotizaciones")->findOneBy('id',$request->getParameter('id'));
            if(!$cotizacion==null){
                $cotizacion->setIsActive(false);
                $cotizacion->save();
                if($request->isXmlHttpRequest()){
                    return $this->renderText("ok");
                }else{
                    $this->redirect("@cotizaciones");
                }
            }
        }
    }
    
    public function executeSeCayoCotizacion(sfWebRequest $request){
        if($request->hasParameter('generar')){
            $cotizacion=  Doctrine_Core::getTable("Cotizaciones")->findOneBy('id',$request->getParameter('generar'));
            if(!$cotizacion==null){
                $cotizacion->setIsActive(false);
                $cotizacion->setStatus(CotizacionesTable::$CAYO_COTIZACION);
                $cotizacion->save();
                $this->getUser()->setFlash('notice', 'Se cayo cotizacion.');
                $this->redirect("cotizaciones_show",$cotizacion);
            }
        }
    }
    
    public function executeReactivarCotizacion(sfWebRequest $request){
        if($request->hasParameter('generar')){
            $cotizacion=  Doctrine_Core::getTable("Cotizaciones")->findOneBy('id',$request->getParameter('generar'));
            if(!$cotizacion==null){
                $cotizacion->setIsActive(true);
                $cotizacion->setStatus(CotizacionesTable::$INCOMPLETO);
                $cotizacion->save();
                $this->getUser()->setFlash('notice', 'Se reactivo la cotizacion.');
                $this->redirect("cotizaciones_show",$cotizacion);
            }
        }
    }
    
    /*
     * function inactivada
     */
    public function executeInactivarShowPay(sfWebRequest $request){
        if($request->hasParameter('id')){
            $cotizacion=  Doctrine_Core::getTable("Cotizaciones")->findOneBy('id',$request->getParameter('id'));
            if(!$cotizacion==null){
                $cotizacion->setIsShowPay(false);
                $cotizacion->save();
                if($request->isXmlHttpRequest()){
                    return $this->renderText("ok");
                }else{
                    $this->redirect("@cotizaciones");
                }
            }
        }
    }
    
    protected function addTalentoConceptosComisionistasEventos(Cotizaciones $cot, DetallesCotizacion $dc){
        
        $totalConceptos=0; $margenComisionistas=0;
        foreach($cot->getCotizacionesConceptos() as $cotc){
            $dcc1=new DetallesCotizacionConceptos();
            //$dcc1->setCotizacionId($cot->getId());
            $dcc1->setDetallesCotizacionId($dc->getId());
            $dcc1->setConceptoId($cotc->getConceptoId());
            $dcc1->setPrecio($cotc->getPrecio());
            $dcc1->setNivel(CotizacionesTable::$NIVEL_COTIZACION);
            $dcc1->saveOnly();
            $totalConceptos+=$cotc->getPrecio();
        }
        
        $margenJerryMl=$dc->getMargenJerryMl();
        $gananciaJerryMl=$totalConceptos*($margenJerryMl/100);
        $gananciaTalento=$totalConceptos-$gananciaJerryMl;
        
        foreach($cot->getCotizacionesComisionistas() as $cotco){
            $dcco1=new DetallesCotizacionComisionistas();
            //$dcco1->setCotizacionId($cot->getId());
            $dcco1->setDetallesCotizacionId($dc->getId());
            $dcco1->setComisionistaId($cotco->getComisionistaId());
            $dcco1->setMargen($cotco->getMargen());
            $dcco1->setNivel(CotizacionesTable::$NIVEL_COTIZACION);
            $dcco1->setGanancia($gananciaTalento*($cotco->getMargen()/100));
            $dcco1->saveOnly();
            $margenComisionistas+=$cotco->getMargen();
        }
        $dc->setPrecio($totalConceptos);
        $dc->setMargenComisionistas($margenComisionistas);
        $dc->save();
        
        $dateInicial=new DateTime($cot->getFechaDesde());
        $dateFinal= new DateTime($cot->getFechaHasta());
        $dc->actualizarEventosDesdeCotizacion($dateInicial, $dateFinal, $cot, $dc);
        
        
    }
   
}
