<?php

require_once dirname(__FILE__).'/../lib/talentosGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/talentosGeneratorHelper.class.php';

/**
 * talentos actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage talentos
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class talentosActions extends autoTalentosActions
{
    public function executeSeleccionar(sfWebRequest $request)
    {
      $this->setPage(1);  
      if($request->hasParameter('representantes_slug')){
        $this->setFilters($this->configuration->getFilterDefaults());
        $this->filters=new RepresentantesFormFilter();
        $this->filters->setDefault('talento_by_representante', $request->getParameter('representantes_slug'));
        $this->setFilters($this->filters->getValues());
      }else{
        $this->setFilters($this->configuration->getFilterDefaults());
      }
      
      if($request->hasParameter('modo')){
         $this->getUser()->activarModo($request->getParameter('modo')); 
         $this->getUser()->setRegresarA($request->getParameter('goto'));
         $this->getUser()->setCancelarRegresarA($request->getParameter('goto'));
      }
      
      if($request->hasParameter('cotizacion')){
        $this->getUser()->setCotizacion($request->getParameter('cotizacion'));
        $cotizacion=  Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($request->getParameter('cotizacion'));
        $talentos=array();
        $registros=$cotizacion->getDetallesCotizacion();
        foreach ($registros as $registro){
            $talentos[]=$registro->getTalentos()->getId();
        }
        $this->getUser()->setTalentosCotizacion($talentos);
        $this->getUser()->setCliente($cotizacion->getClienteId());
        $this->getUser()->setContacto($cotizacion->getContactoId());
      }
      
      if($request->hasParameter('precotizacion')){
        $this->getUser()->setPrecotizacion($request->getParameter('precotizacion'));
        $precotizacion=  Doctrine_Core::getTable('Precotizaciones')->getPrecotizacionConClienteTalentoDetallesForId($request->getParameter('precotizacion'));
        $talentos=array();
        $registros=$precotizacion->getDetallesPrecotizacion();
        foreach ($registros as $registro){
            $talentos[]=$registro->getTalentos()->getId();
        }
        $this->getUser()->setTalentosPrecotizacion($talentos);
        $this->getUser()->setCliente($precotizacion->getClienteId());
        $this->getUser()->setContacto($precotizacion->getContactoId());
      }
      
      /*
      $this->pager = $this->getPager();
      $this->sort = $this->getSort();

      $this->setTemplate('index');*/
      
      $this->redirect('@talentos');
    }
    public function executeBatchSelect(sfWebRequest $request)
    {
      $ids = $request->getParameter('ids');
      $this->getUser()->addTalentos($ids);
      $this->redirect('@talentos');
    }
    public function executeListSelect(sfWebRequest $request)
    {
      $talento = $this->getRoute()->getObject();
      $this->getUser()->addTalentos($talento->getId());
      $this->redirect('@talentos');
    }
    public function executeListCalendar(sfWebRequest $request)
    {
      $talento = $this->getRoute()->getObject();
      $this->getUser()->setCalendarTalento($talento->getId(),$talento->getName());
      
      if($request->hasParameter('modo')){
         $this->getUser()->activarModo($request->getParameter('modo'));
         $this->getUser()->setRegresarA($request->getParameter('goto'));
         $this->getUser()->setCancelarRegresarA($request->getParameter('goto'));
      }
      
     if($request->hasParameter('return_talentos')){
        if($request->getParameter('return_talentos')=="false"){ 
           $this->getUser()->setRegresarATalentos(false);
        }
     }else{
       $this->getUser()->setRegresarATalentos(true);
     }
      
      $this->redirect('@calendario');
    }
    
    public function executeListRemove(sfWebRequest $request)
    {
      $talento = $this->getRoute()->getObject();
      $this->getUser()->removeTalentos($talento->getId());
      if(count($this->getUser()->getEventos($talento->getId()))){
        $talento->removeEventos($this->getUser()->getEventos($talento->getId()));
        $this->getUser()->setEventos(array(),$talento->getId());
      }
      
      $this->redirect('@talentos');
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
          $this->getUser()->setSeleccionarTalentos(false);
          $this->getUser()->setTalentosCotizacion(array());
          $this->getUser()->setTalentosPrecotizacion(array());
          $this->getUser()->setTalentos(array());
          $this->getUser()->setCotizacion(0);
          $this->getUser()->setPrecotizacion(0);
      }
      
      if(count($this->getUser()->getTalentos())>0){
        $talentos=  Doctrine_Core::getTable('Talentos')->getTalentosPorArreglo($this->getUser()->getTalentos());
        foreach($talentos as $talento){
            if(count($this->getUser()->getEventos($talento->getId()))){
                $eventos=  Doctrine_Core::getTable('KsWCEvent')->getEventosPorArreglo($this->getUser()->getEventos($talento->getId()));
                foreach($eventos as $evento){
                   $evento->delete(); 
                }
                $this->getUser()->setEventos(array(),$talento->getId());
            }
        }
      }
      
      $ruta=$this->getUser()->getCancelarRegresarA();
      $this->redirect($ruta);
    }
    
    public function executeFinalizarSeleccion(sfWebRequest $request)
    {
      $ruta=$this->getUser()->getRegresarA();
      $this->redirect($ruta);
    }
      
    public function executeIndex(sfWebRequest $request)
    {
      if($this->getUser()->getCalendarTalentoId()>0){
            $this->getUser()->setCalendarTalento(0,"");
      }  
       
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
      
      if(count($this->getUser()->getTalentos())){
          $this->talentosSeleccionados=  Doctrine_Core::getTable('Talentos')->getTalentosPorArreglo($this->getUser()->getTalentos());
      }else{
          $this->talentosSeleccionados=null;
      }
      
      
    }
    
    public function executeShow(sfWebRequest $request)
    {
      $this->talentos = $this->getRoute()->getObject();
      $this->pagos_talentos=$this->talentos->getPagosTalentos();
      $this->detalles_cotizacion=$this->talentos->getDetallesCotizacion();
      $this->eventos=$this->talentos->getEventos();
      
    }
    
    public function executeInactive(sfWebRequest $request)
    {
      $this->talentos = $this->getRoute()->getObject();
      $this->talentos->setIsActive(false);
      $this->talentos->save();
      $this->redirect("@talentos");
    }
    
    public function executeActive(sfWebRequest $request)
    {
      $this->talentos = $this->getRoute()->getObject();
      $this->talentos->setIsActive(true);
      $this->talentos->save();
      $this->redirect("@talentos");
     
    }
    
}
