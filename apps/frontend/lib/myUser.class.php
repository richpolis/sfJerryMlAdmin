<?php

class myUser extends sfGuardSecurityUser
{
    function setModoCotizacion($modo=false){
        $this->setAttribute('modoCotizacion', $modo);
        $this->setSeleccionarCliente($modo);
        $this->setSeleccionarContacto($modo);
        $this->setSeleccionarTalentos($modo);
        if(!$modo){
            $this->setRegresarATalentos(!$modo);
            $this->setRegresarA("");
        }
        
    }
    
    function getModoCotizacion(){
        return $this->getAttribute('modoCotizacion', false);
    }
    
    function setModoPrecotizacion($modo=false){
        $this->setAttribute('modoPrecotizacion', $modo);
        $this->setSeleccionarCliente($modo);
        $this->setSeleccionarContacto($modo);
        $this->setSeleccionarTalentos($modo);
        if(!$modo){
            $this->setRegresarA("");
        }
        
    }
    
    function getModoPrecotizacion(){
        return $this->getAttribute('modoPrecotizacion', false);
    }
    
    function setModoContrato($modo=false){
        $this->setAttribute('modoContrato', $modo);
        $this->setSeleccionarCotizaciones($modo);
        
    }
    
    function getModoContrato(){
        return $this->getAttribute('modoContrato', false);
    }
    
    function setModoPago($modo=false){
        $this->setAttribute('modoPago', $modo);
        $this->setSeleccionarCotizaciones($modo);
        $this->setSeleccionarCliente($modo);
        if(!$modo) $this->setPago(0);
    }
    
    function getModoPago(){
        return $this->getAttribute('modoPago', false);
    }
    
    function setModoPagoTalento($modo=false){
        $this->setAttribute('modoPagoTalento', $modo);
        $this->setSeleccionarCotizaciones($modo);
        $this->setSeleccionarTalentos($modo);
        if(!$modo) $this->setPagoTalento(0);
    }
    
    function getModoPagoTalento(){
        return $this->getAttribute('modoPagoTalento', false);
    }
    
    function activarModo($modo){
        switch($modo){
            case "precotizaciones":
                $this->setModoPrecotizacion(true);
                break;
            case "cotizaciones":
                $this->setModoCotizacion(true);
                break;
            case "pagos":
                $this->setModoPago(true);
                break;
            case "contratos":
                $this->setModoContrato(true);
                break;
            case "pagos_talentos":
                $this->setModoPagoTalento(true);
                break;    
        }
    }
    
    function hayModoActivo(){
       if($this->getModoPrecotizacion() ||
               $this->getModoCotizacion() || 
               $this->getModoContrato() || 
               $this->getModoPago() || 
               $this->getModoPagoTalento()){
            return true;
        }else{
            return false;
        }
    }
    
    function getModoActivo(){
        $resp="";
        if($this->getModoPrecotizacion()){
            $resp="precotizaciones";
        }elseif($this->getModoCotizacion()){
            $resp="cotizaciones";
        }elseif($this->getModoContrato()){
            $resp="contratos";
        }elseif($this->getModoPago()){
            $resp="pagos";
        }elseif($this->getModoPagoTalento()){
            $resp="pagos_talentos";
        }
        return $resp;
    }
    
    function getStatusDeModo(){
        $resp="";
        if($this->getModoCotizacion() || $this->getModoPrecotizacion()){
            if($this->getCliente() && $this->getContacto()){
                $resp=  "Empresa seleccionada  >> ";
                $resp.=  " Contacto seleccionado >> ";
                $resp.=  "<strong> Seleccionar talentos </strong>";
            }elseif($this->getCliente()){
                $resp=  "Empresa seleccionada  >> ";
                $resp.=  "<strong> Seleccionar contacto </strong>";
            }else{
                $resp.=  "<strong> Seleccionar empresa </strong>";
            }
        }elseif($this->getModoContrato()){
            $resp=  " Cotizacion Seleccionada: ".(count($this->getCotizaciones())>0?'Si':'No');
        }elseif($this->getModoPago()){
            $resp=  "Cliente Seleccionado: ".($this->getCliente()?'Si':'No');
            $resp=  " Cotizaciones Seleccionadas: ".(count($this->getCotizaciones())>0?'Si':'No');
        }elseif($this->getModoPagoTalento()){
            $resp=  "Talento Seleccionado: ".(count($this->getTalentos())>0?'Si':'No');
            $resp=  " Cotizaciones Seleccionadas: ".(count($this->getCotizaciones())>0?'Si':'No');
        }
        return $resp;
    }
    
    /*function getStatusDeModo(){
        $resp="";
        if($this->getModoCotizacion()){
            $resp=  Doctrine_Core::getTable('Cotizaciones')->getStatusModoCotizacion($this->getCliente(),$this->getContacto(),$this->getTalentos());
        }elseif($this->getModoContrato()){
            $resp=  Doctrine_Core::getTable('Contratos')->getStatusModoContrato($this->getCotizaciones());
        }elseif($this->getModoPago()){
            $resp=  Doctrine_Core::getTable('Pagos')->getStatusModoPago($this->Cliente(),$this->getCotizaciones());
        }elseif($this->getModoPagoTalento()){
            $resp=  Doctrine_Core::getTable('Pagos')->getStatusModoPagoTalento($this->Talentos(),$this->getCotizaciones());
        }
        return $resp;
    }*/
    
    function setSeleccionarCliente($modo=false){
        if($this->hasAttribute('cliente')){
            if(!$modo)
                $this->setCliente(0);
        }else{
            $this->setCliente(0);
        }
        $this->setAttribute('seleccionarCliente', $modo);
    }
    
    function getSeleccionarCliente(){
        return $this->getAttribute('seleccionarCliente', false);
    }
    
    function getCliente(){
        return $this->getAttribute('cliente', 0);
    }
    
    function setCliente($idCliente){
        $this->setAttribute('cliente', $idCliente);
    }
    
    function getPago(){
        return $this->getAttribute('pago', 0);
    }
    
    function setPago($id){
        $this->setAttribute('pago', $id);
    }
    
    function getPagoTalento(){
        return $this->getAttribute('pagoTalento', 0);
    }
    
    function setPagoTalento($id){
        $this->setAttribute('pagoTalento', $id);
    }
    
    function setSeleccionarContacto($modo=false){
        if($this->hasAttribute('contacto')){
            if(!$modo)
                $this->setContacto(0);
        }else{
            $this->setContacto(0);
        }
        $this->setAttribute('seleccionarContacto', $modo);
    }
    
    function getSeleccionarContacto(){
        return $this->getAttribute('seleccionarContacto', false);
    }
    
    function getContacto(){
        return $this->getAttribute('contacto', 0);
    }
    function setContacto($idContacto){
        $this->setAttribute('contacto', $idContacto);
    }
    
    function setSeleccionarTalentos($modo=false){
        if($this->hasAttribute('talentos')){
            if(!$modo)
                $this->setTalentos(array());
        }else{
            $this->setTalentos(array());
        }
        $this->setAttribute('seleccionarTalentos', $modo);
    }
    
    function getSeleccionarTalentos(){
        return $this->getAttribute('seleccionarTalentos', false);
    }
    
    function getTalentos(){
        return $this->getAttribute('talentos', array());
    }
    function setTalentos($idsTalentos=array()){
        $this->setAttribute('talentos', $idsTalentos);
    }
    
    function addTalentos($ids=array()){
      if(!is_array($ids)){
          $ids = array($ids);
      }
      $talentos=$this->getTalentos();
      $result=  array_merge($ids, $talentos);
      $this->setTalentos(array_unique($result));
    }
    function removeTalentos($id){
      $talentos=$this->getTalentos();
      foreach($talentos as $key=>$value){
          if($value==$id){
              unset($talentos[$key]);
          }
      }
      $this->setTalentos($talentos);
    }
    
    function setTalento($talento){
    	$this->setAttribute('talento', $talento);
    }
    function getTalento(){
    	return $this->getAttribute('talento', 0);
    }
    
    function setTalentosCotizacion($ids){
        $this->setAttribute('talentos_cotizacion', $ids);
    }
    
    function getTalentosCotizacion(){
        return $this->getAttribute('talentos_cotizacion', array());
    }
    
    function setTalentosPrecotizacion($ids){
        $this->setAttribute('talentos_precotizacion', $ids);
    }
    
    function getTalentosPrecotizacion(){
        return $this->getAttribute('talentos_precotizacion', array());
    }
    
    
    //eventos
    function setSeleccionarEventos($modo=false){
        if($this->hasAttribute('eventos')){
            if(!$modo)
                $this->setEventos(array());
        }else{
            $this->setEventos(array());
        }
        $this->setAttribute('seleccionarEventos', $modo);
    }
    
    function getSeleccionarEventos(){
        return $this->getAttribute('seleccionarEventos', false);
    }
    
    function addEventos($ids=array(),$idTalento=0){
      if(!is_array($ids)){
          $ids = array($ids);
      }
      $eventos=$this->getEventos($idTalento);
      $result=  array_merge($ids, $eventos);
      $this->setEventos(array_unique($result),$idTalento);
    }
    function removeEventos($id,$idTalento=0){
      $eventos=$this->getEventos($idTalento);
      foreach($eventos as $key=>$value){
          if($value==$id){
              unset($eventos[$key]);
          }
      }
      $this->setEventos($eventos,$idTalento);
    }
    
    function setEventos($ids,$idTalento=0){
    	$this->setAttribute('eventos_'.$idTalento, $ids);
    }
    function getEventos($idTalento=0){
    	return $this->getAttribute('eventos_'.$idTalento, array());
    }
    
    function setEventosCotizacion($ids){
    	$this->setAttribute('eventos_cotizacion', $ids);
    }
    function getEventosCotizacion(){
    	return $this->getAttribute('evento_cotizacion', array());
    }
    
    function setSeleccionarCotizaciones($modo=false){
        if($this->hasAttribute('cotizacion')){
            if(!$modo)
                $this->setCotizaciones(array());
        }else{
            $this->setCotizaciones(array());
        }
        $this->setAttribute('seleccionarCotizacion', $modo);
    }
    
    function getSeleccionarCotizaciones(){
        return $this->getAttribute('seleccionarCotizacion', false);
    }
    
    function getCotizaciones(){
        return $this->getAttribute('cotizaciones', array());
    }
    function setCotizaciones($ids=array()){
        $this->setAttribute('cotizaciones', $ids);
    }
    
    function addCotizaciones($ids=array()){
      if(!is_array($ids)){
          $ids = array($ids);
      }
      $cotizaciones=$this->getCotizaciones();
      $result=  array_merge($ids, $cotizaciones);
      $this->setCotizaciones(array_unique($result));
    }
    function removeCotizaciones($id){
      $cotizaciones=$this->getCotizaciones();
      foreach($cotizaciones as $key=>$value){
          if($value==$id){
              unset($cotizaciones[$key]);
          }
      }
      $this->setCotizaciones($cotizaciones);
    }
    
    function setCotizacion($cotizacion){
        $this->setAttribute('cotizacion', $cotizacion);
    }
    
    function getCotizacion(){
        return $this->getAttribute('cotizacion', 0);
    }
    
    function setClienteCotizaciones($cliente){
        $this->setAttribute('cliente_cotizaciones', $cliente);
    }
    
    function getClienteCotizaciones(){
        return $this->getAttribute('cliente_cotizaciones', 0);
    }
    function setContactoCotizaciones($cliente){
        $this->setAttribute('contacto_cotizaciones', $cliente);
    }
    
    function getContactoCotizaciones(){
        return $this->getAttribute('contacto_cotizaciones', 0);
    }
    function setClientePagos($cliente){
        $this->setAttribute('cliente_pagos', $cliente);
    }
    
    function getClientePagos(){
        return $this->getAttribute('cliente_pagos', 0);
    }
    
    
    
    function setSeleccionarRepresentante($modo=false){
        if($this->hasAttribute('represetante')){
            if(!$modo)
                $this->setAttribute ('represetante', 0);
        }else{
            $this->setAttribute ('represetante', 0);
        }
        $this->setAttribute('seleccionarRepresentante', $modo);
    }
    
    function getSeleccionarRepresentante(){
        return $this->getAttribute('seleccionarRepresentante', false);
    }
    
    function getRepresentante(){
        return $this->getAttribute('representante', 0);
    }
    
    function setRepresentante($id){
        $this->setAttribute('representante', $id);
    }
    
    function setCalendarTalento($id,$name){
        $this->setAttribute ('calendar_talento_id',$id);
        $this->setAttribute('calendar_talento_name', $name);
    }
    
    function getCalendarTalentoId(){
        return $this->getAttribute('calendar_talento_id',0);
    }
    
    function getCalendarTalentoName(){
        return $this->getAttribute('calendar_talento_name','');
    }
    
    
    function setCalendarUsuario($id,$name,$color){
        $this->setAttribute ('calendar_usuario_id',$id);
        $this->setAttribute('calendar_usuario_name', $name);
        if($color=='')$color=3;
        $this->setAttribute('calendar_usuario_color', $color);
        $this->setRegresarATalentos(false);
    }
    
    function getCalendarUsuarioId(){
        return $this->getAttribute('calendar_usuario_id',0);
    }
    
    function getCalendarUsuarioName(){
        return $this->getAttribute('calendar_usuario_name','');
    }
    
    function getCalendarUsuarioColor(){
        return $this->getAttribute('calendar_usuario_color','#99CC32');
    }
    
    
    
    
    function setRegresarA($ruta){
        $this->setAttribute('regresar_a', $ruta);
    }
    function getRegresarA(){
        return $this->getAttribute('regresar_a', '@homepage');
    }
    
    function setRegresarATalentos($valor){
        $this->setAttribute('regresar_a_talentos', $valor);
    }
    function getRegresarATalentos(){
        return $this->getAttribute('regresar_a_talentos', '@homepage');
    }
    
    function setCancelarRegresarA($ruta){
        $this->setAttribute('cancelar_regresar_a', $ruta);
    }
    function getCancelarRegresarA(){
        return $this->getAttribute('cancelar_regresar_a', '@homepage');
    }
    
    function setPrecotizacion($precotizacion){
        $this->setAttribute('precotizacion', $precotizacion);
    }
    function getPrecotizacion(){
        return $this->getAttribute('precotizacion', 0);
    }
    
         
    
    
}
