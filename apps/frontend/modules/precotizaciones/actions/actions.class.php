<?php

require_once dirname(__FILE__).'/../lib/precotizacionesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/precotizacionesGeneratorHelper.class.php';

/**
 * precotizaciones actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage precotizaciones
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class precotizacionesActions extends autoPrecotizacionesActions
{
    public function executeVistaPrevia(sfWebRequest $request) {
        $this->precotizaciones = Doctrine_Core::getTable('Precotizaciones')->getPrecotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
        $this->detalles_precotizacion=  $this->precotizaciones->getDetallesPrecotizacion();
        $this->setLayout('simple_layout');
    }
    public function executeGenerarPdf(sfWebRequest $request) {
        ProjectConfiguration::registerMPDF();
        $html = $request->getPostParameter('html');
        
        $mpdf = new mPDF('es_ES','Letter','','',32,25,27,25,16,13);
        $mpdf->useOnlyCoreFonts = true;

        // load a stylesheet
        //$stylesheet = file_get_contents(sfConfig::get('sf_web_dir').'/css/mypdf.css');
        //$mpdf->WriteHTML($stylesheet,1); // el parámetro le dice que sólo es css y no contenido html
        $mpdf->WriteHTML($html,2);

        $mpdf->Output('mpdf.pdf','D');
        throw new sfStopException();
    }
    protected function generarPdf($precotizacion,$html) {
        ProjectConfiguration::registerMPDF();
        
        $mpdf = new mPDF('es_ES','Letter','','',32,25,27,25,16,13);
        $mpdf->useOnlyCoreFonts = true;

        $mpdf->WriteHTML($html,2);
        $file="Precotizacion_".$precotizacion->getId().'.pdf';
        if(!file_exists(sfConfig::get('sf_upload_dir').'/precotizaciones/'.$precotizacion->getId().'/')){
            mkdir(sfConfig::get('sf_upload_dir').'/precotizaciones/'.$precotizacion->getId().'/', 0777);
        }
        $cont=1;
        while (file_exists(sfConfig::get('sf_upload_dir').'/precotizaciones/'.$precotizacion->getId().'/'.$file)){
            $file="Precotizacion_".$precotizacion->getId().'v'.$cont.'.pdf';
            $cont++;
        }
        
        $filename=sfConfig::get('sf_upload_dir').'/precotizaciones/'.$precotizacion->getId().'/'.$file;
        
        $mpdf->Output($filename,'F');
        return $file;
    }
            
    public function executeAprobarPrecotizacion(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $precotizacion=  Doctrine_Core::getTable('Precotizaciones')->getPrecotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            if($precotizacion->validarAprobar()){
                if(!$precotizacion->getPdf()=="sin_archivo.pdf"){
                    $html=$this->getPartial('vistaPrevia',array('precotizaciones'=>$precotizacion,'detalles_precotizaciones'=>$precotizacion->getDetallesPrecotizacion()));
                    $pdf=$this->generarPdf($precotizacion, $html);
                    $precotizacion->setPdf($pdf);
                }
                if($request->hasParameter('enviar_cotizacion') && $request->getParameter('enviar_cotizacion')=="true"){
                   $enviarACliente=true;
                }
                $precotizacion->setStatus(PrecotizacionesTable::$APROBADA);//aprobado por el cliente

                $precotizacion->save();
                if($enviarACliente){
                    $this->redirect("home/enviarPrecotizacion?precotizacion=".$precotizacion->getId()."&enviarAutomatico=true");
                }
                $this->getUser()->setFlash('notice', 'Se ha aprobado la precotizacion.');
                $this->redirect("precotizaciones_show",$precotizacion);
            }else{
                $this->getUser()->setFlash('error', $precotizacion->getErroresAprobar());
                $this->redirect("precotizaciones_show",$precotizacion);
            }
        }else{
            $this->redirect('@precotizaciones');
        }  
    }
    public function executeCancelarPrecotizacion(sfWebRequest $request){
         if($request->hasParameter('generar')){
            $precotizacion=  Doctrine_Core::getTable('Precotizaciones')->getPrecotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            $precotizacion->setStatus(PrecotizacionesTable::$INCOMPLETO);//cancelada
            $precotizacion->save();
            $this->getUser()->setFlash('notice', 'Se ha cancelado la aprobacion de la precotizacion.');
            $this->redirect("precotizaciones_show",$precotizacion);
        }else{
            $this->redirect('@precotizaciones');
        }  
    }
    
    public function executePrepararEnvioPrecotizacion(sfWebRequest $request) {
        if($request->hasParameter('generar')){
            $precotizacion=  Doctrine_Core::getTable('Precotizaciones')->getPrecotizacionConClienteTalentoDetallesForId($request->getParameter('generar'));
            if($precotizacion->validarAprobar()){
                if($precotizacion==null){
                    $precotizacion=  Doctrine_Core::getTable('Precotizaciones')->find($request->getParameter('generar'));
                }
                $html=$this->getPartial('vistaPrevia',array('precotizaciones'=>$precotizacion,'detalles_precotizaciones'=>$precotizacion->getDetallesPrecotizacion()));
                $pdf=$this->generarPdf($precotizacion, $html);
                $precotizacion->setPdf($pdf);
                //if($precotizacion->getStatus()<=PrecotizacionesTable::$MEDIACION){
                //    $precotizacion->setStatus(PrecotizacionesTable::$MEDIACION);//Enviado a cliente
                //}
                $precotizacion->save();
                $this->redirect("home/enviarPrecotizacion?precotizacion=".$precotizacion->getId());
            }else{
                $this->getUser()->setFlash('error', 'Revisar que los registros esten completos.');
                $this->redirect("precotizaciones_show",$precotizacion);
            }
            
        }
        
        $this->redirect('@precotizaciones');
    }

    public function executeCrearPrecotizacion(sfWebRequest $request) {
        if (!$this->getUser()->getModoPrecotizacion()) {
            $this->getUser()->setModoPrecotizacion(true);
            $this->getUser()->setRegresarA('@crear_precotizacion');
            $this->getUser()->setCancelarRegresarA('@precotizaciones');
        }
        if ($this->getUser()->getCliente() == 0) {
            $this->redirect('@seleccionar_cliente');
        } elseif ($this->getUser()->getContacto() == 0) {
            $this->redirect('@seleccionar_contacto');
        } elseif (count($this->getUser()->getTalentos()) == 0) {
            $this->redirect('@seleccionar_talentos');
        } else {
            //$this->getUser()->setModoCotizacion(false);
            $this->redirect('@precotizaciones_new');
        }
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->precotizaciones = $this->form->getObject();
        $this->precotizaciones->save();
        $this->redirect('precotizaciones_show',$this->precotizaciones);
    }

    private function crearFormulario(Precotizaciones $precotizacion = null) {
        if (is_null($precotizacion)) {
            $cliente = $this->getUser()->getCliente();
            $contacto = $this->getUser()->getContacto();
            $talentos = $this->getUser()->getTalentos();
            if (($cliente > 0) && ($contacto > 0)) {
                $precotizacion = new Precotizaciones();
                $precotizacion->setClienteId($cliente);
                $precotizacion->setContactoId($contacto);
                $precotizacion->setUserId($this->getUser()->getGuardUser()->getId());
                $form = new PrecotizacionesForm($precotizacion);
            } else {
                $this->getUser()->setFlash('error', 'No se ha seleccionado correctamente el cliente y contacto.');
                $this->getUser()->setModoPrecotizacion(false);
                $this->redirect('@precotizaciones');
                
            }
        } else {
            $form = new PrecotizacionesForm($precotizacion);
        }
        return $form;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = $this->crearFormulario();
        $this->precotizaciones = $this->form->getObject();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->precotizaciones = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->precotizaciones);
        $this->form=$this->crearFormulario($this->precotizaciones);
    }
    public function executeShow(sfWebRequest $request) {
        $this->precotizaciones = $this->getRoute()->getObject();
        
        if(count($this->getUser()->getTalentos())>0){
            $talentos=  Doctrine_Core::getTable('Talentos')->getTalentosPorArreglo($this->getUser()->getTalentos());
            foreach($talentos as $talento){
                $existeTalento=false;
                
                foreach($this->precotizaciones->getDetallesPrecotizacion() as $detalle){
                    if($detalle->getTalentoId()==$talento->getId()){
                        $existeTalento=true;
                    }
                }
                if(!$existeTalento){
                    $dt=new DetallesPrecotizacion();
                    $dt->setTalentos($talento);
                    $dt->setPrecotizacionId($this->precotizaciones->getId());
                    $dt->setActividad($talento->getDescripcion());
                    $dt->setMargenJerryMl($talento->getMargenJerryMl());
                    $dt->save();
                }
                if(count($this->getUser()->getEventos($talento->getId()))){
                    $this->getUser()->setEventos(array(),$talento->getId());
                }
            }
        }
        $this->getUser()->setModoPrecotizacion(false);
        $this->getUser()->setPrecotizacion(0);
        $this->detalles_precotizaciones=  Doctrine_Core::getTable('DetallesPrecotizacion')->getDetallesPrecotizacionPorPrecotizacion($this->precotizaciones->getId());
        
        
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->precotizaciones = $this->getRoute()->getObject();
        //$this->form = $this->configuration->getForm($this->precotizaciones);
        $this->form=$this->crearFormulario($this->precotizaciones);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }
  
    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $precotizaciones = $form->save();
                //$this->getUser()->setModoPrecotizacion(false);
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

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $precotizaciones)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                    $this->redirect('@precotizaciones_edit');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'precotizaciones_show', 'sf_subject' => $precotizaciones));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }
    
    public function executeAjaxRegistroOrder(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest()) {
            $precotizacion = Doctrine_Core::getTable('Precotizaciones')->find($request->getParameter("id"));
            $registro_order = $request->getParameter('detalles_precotizacion');
            foreach($registro_order as $order=>$id)
            {
                $registro=  Doctrine_Core::getTable('DetallesPrecotizacion')->find($id);
                //if($registro->getPosition()!=($order+1)){
                  try{
                        $registro->setPosition($order+1);
                        $registro->save();

                  }catch(Exception $e){
                        return $this->renderText($e->getMessage());
                  }
                //}
            }
            $this->detalles_precotizaciones=  Doctrine_Core::getTable('DetallesPrecotizacion')->getDetallesPrecotizacionPorPrecotizacion($precotizacion->getId());
            return $this->renderPartial('talentos',array('precotizaciones' => $precotizacion,"detalles_precotizaciones"=>$this->detalles_precotizaciones));
            
        }
        else {
            $this->redirect404();
        }
    }
    public function executeInactivar(sfWebRequest $request){
        if($request->hasParameter('id')){
            $precotizacion=  Doctrine_Core::getTable("Precotizaciones")->findOneBy('id',$request->getParameter('id'));
            if(!$precotizacion==null){
                $precotizacion->setIsActive(false);
                $precotizacion->save();
                if($request->isXmlHttpRequest()){
                    return $this->renderText("ok");
                }else{
                    $this->redirect("@precotizaciones");
                }
            }
        }
    }
}
