<?php

/**
 * Cotizaciones
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Cotizaciones extends BaseCotizaciones
{
    public function __toString() {
        return sprintf("ID: %s .- %s",$this->getId(),$this->getEvento());
    }
    public function getStringStatus(){
        switch($this->getStatus()){
            case 3: 
                return "Pagos Liberados";
            case 2:
                return "Aprobada - Liberar Pagos a Talentos";
            case 1:
                return "Enviada a cliente";
            case 0:
                return "En captura";
            case -1:
                return "Cancelado";    
        }
    }
    public function statusAprobada(){
        if($this->getStatus()>=CotizacionesTable::$APROBADA)
            return true;
        else
            return false;
    }
    public function statusEnMediacion(){
        if($this->getStatus()<=CotizacionesTable::$MEDIACION)
            return true;
        else
            return false;
    }
        
    public function statusPagosLiberados(){
        if($this->getStatus()>=CotizacionesTable::$PAGOS_LIBERADOS)
            return true;
        else
            return false;
    }
    
    public function getRenderMensaje(){
        $contacto = $this->getContactos();
        $user= sfContext::getInstance()->getUser()->getGuardUser();
        $usuario=$user->getFirstName()." ".$user->getLastName();
        $email=$user->getEmailAddress();
        $detalles_cotizacion=$this->getDetallesCotizacion();
        $eventos=$this->getCotizacionesEventos();
        $cadena="";
        foreach($detalles_cotizacion as $detalle){
            $cadena.=$detalle->getTalentos();
            foreach($eventos as $evento){
                if($detalle->getTalentoId()==$evento->getEventos()->getTalentoId()){
                    $cadena.="<br/>";
                    $cadena.=$evento->getEventos();
                }
            }
            $cadena.="<br/><br/>";
        }
        
$body = <<<EOD
        <p>
        Estimado {$contacto}<br/>
        
        Por medio de la presente le envio la cotización para:<br/><br/>
        
        {$cadena}

        <br/>
        Cotización adjunta.
        <br/>
        <br/>
        Atentamente:
        <br/>
        $usuario Ejecutivo de Cuenta.<br/>
        Email: $email.<br/>
        Teléfonos JerryML a especificar.<br/>
        </p>
EOD;

    return $body;
    
    }
    public function getCadenaTalentos(){
        $detalles=$this->getDetallesCotizacion();
        $talentos=array();
        $cadena="";
        foreach($detalles as $detalle){
            $talentos[]=$detalle->getTalentos()->getName();
        }
        $largo=count($talentos);
        foreach($talentos as $key=>$name){
            $cadena.=$name;
            if($key+1<$largo){
                $cadena.=", ";
            }
        }
        return $cadena;
    }
    public function getTotal(){
        return $this->getSubtotal()+$this->getIva();
    }
    
    public function getTotalSinIva(){
        return $this->getSubtotal();
    }
    public function aprobarCotizacion(){
        //aprobar cotizacion, crea un registro en el cliente para el pago
        //y una entrada en contratos para el contrato que se sube despues. 
        if($this->getTotal()>0 && $this->validarAprobar()){
            $clientes=$this->getClientes();
            $clientes->setSaldo($clientes->getSaldo()+$this->getTotal());
            $clientes->save();

            $contrato=Doctrine_Core::getTable('Contratos')->findOneBy('cotizacion_id', $this->getId());
            if(!$contrato){
                $contrato=new Contratos();
                $contrato->setCotizacionId($this->getId());
                //$contrato->setUserId($this->getUser()->getGuardUser()->getId());
                $contrato->save();
            }
            $pago_cliente=Doctrine_Core::getTable('Pagos')->getLoteDePagoForClienteId($clientes->getId());
            if(!$pago_cliente){
                $pago_cliente=new Pagos();
                $pago_cliente->setClienteId($clientes->getId());
                $pago_cliente->save();
            }
            $existePagoCotizacion=false;
            foreach($pago_cliente->getDetallesPagos() as $detalle){
                if($detalle->getCotizacionId()==$this->getId()){
                    //significa que existe registro para pagar de esta cotizacion
                    $existePagoCotizacion=true;
                }
            }
            if(!$existePagoCotizacion){
                $detallePago=new DetallesPagos();
                $detallePago->setPagosId($pago_cliente->getId());
                //$detallePago->setUserId($this->getUser()->getGuardUser()->getId());
                $detallePago->setCotizacionId($this->getId());
                $detallePago->setFechaPago(date('Y-m-d'));
                $detallePago->setImporte($this->getTotal()/2);
                $detallePago->setTipoPago(1);
                $detallePago->save();
                
                /*$pago_cliente=Doctrine_Core::getTable('Pagos')->getLoteDePagoForClienteId($clientes->getId());*/
                $pago_cliente->setAdeudo($pago_cliente->getAdeudo()+$this->getTotal());
                $pago_cliente->save();
            }
            $this->aprobarEventos();
            
            return true;
        }else{
            return false;
        }
    }
    public function cancelarCotizacion(){
        //cancela la cotizacion y libera la deuda del cliente.
        if($this->validarCancelarAprobacion()){
            $pago=null;
            foreach($this->getDetallesPagos() as $dp){
                $pago=$dp->getPagos();
                $dp->delete();
            }
            if(!$pago==null)
                $pago->calcular();
            $clientes=$this->getClientes();
            $clientes->setSaldo($clientes->getSaldo()-$this->getTotal());
            $clientes->save();
        }
    }
    public function liberarPagosCotizacion(){
        $precio=0;
        $margen_jerryml=0;
        foreach($this->getDetallesCotizacion() as $dc){
                $talento=$dc->getTalentos();
                $pagoTalento=Doctrine_Core::getTable('PagosTalentos')->getLoteDePagoForTalentoId($talento->getId());
                if(!$pagoTalento){
                    $pagoTalento=new PagosTalentos();
                    $pagoTalento->setTalentoId($talento->getId());
                    $pagoTalento->save();
                }
                $existeDetalleCotizacion=false;
                foreach($pagoTalento->getDetallesPagosTalentos() as $dpt){
                    if($dpt->getDetallesCotizacionId()==$dc->getId()){
                        $existeDetalleCotizacion=true;
                    }
                }
                if(!$existeDetalleCotizacion){
                    $detallePagoTalento=new DetallesPagosTalentos();
                    $detallePagoTalento->setPagosTalentosId($pagoTalento->getId());
                    //$detallePagoTalento->setUserId($this->getUser()->getGuardUser()->getId());
                    $detallePagoTalento->setDetallesCotizacionId($dc->getId());
                    $detallePagoTalento->setImporte($dc->getGananciaTalento());
                    //$detallePagoTalento->setIva($dc->getGananciaTalento()*.16);
                    $detallePagoTalento->save();
                    
                    $talento->setSaldo($dc->getGananciaTalento());
                    $talento->save();
                    
                    
                    /*$pagoTalento=Doctrine_Core::getTable('PagosTalentos')->getLoteDePagoForTalentoId($talento->getId());*/
                    $pagoTalento->setAdeudo($pagoTalento->getAdeudo()+$dc->getGananciaTalento());
                    $pagoTalento->save();
                }
                
        }
        
        $this->congelarEventos();
    }
    
    public function calcular(){
        $detalles_cotizacion=$this->getDetallesCotizacion();
        $subtotal=0;
        $iva=0;
        foreach ($detalles_cotizacion as $detalle){
            $subtotal+=$detalle->getSubtotal();
            $iva+=$detalle->getIva();
        }
        $this->setSubtotal($subtotal);
        $this->setIva($iva);
        $this->save();
    }
    
    public function calcularPagos(){
        $detalles_pagos=$this->getDetallesPagos();
        $importe=0.0;
        foreach ($detalles_pagos as $detalle){
            if($detalle->getStatus()==PagosTable::$PAGOS_CALCULADOS){
                $importe+=$detalle->getImporte();
            }
        }
        if($importe==$this->getTotal()){
            $this->setIsPay(true);
            $this->save();
        }
    }
    
    public function validarAprobar(){
        $detalles_cotizacion=$this->getDetallesCotizacion();
        $valido=true;
        foreach ($detalles_cotizacion as $detalle){
            if($detalle->getSubtotal()>0){
                $cont=0;
                foreach($this->getCotizacionesEventos() as $ce){
                    if($ce->getEventos()->getTalentoId()==$detalle->getTalentoId()){
                        $cont++;
                    }
                }
                $valido=($cont>0?true:false);
            }else{
                $valido=false;
            }
            if(!$valido) break;
        }
       return $valido;
    }
    
    public function validarCancelarAprobacion(){
        $importe=0;
        foreach($this->getDetallesPagos() as $dp){
            if($dp->getStatus()==PagosTable::$PAGOS_CALCULADOS){
                $importe+= $dp->getImporte();
            }
        }
        return ($importe>0?false:true);
    }
    
    public function getErroresAprobar(){
        $detalles_cotizacion=$this->getDetallesCotizacion();
        $mensaje="";
        foreach ($detalles_cotizacion as $detalle){
            if($detalle->getSubtotal()<=0){
                if(strlen($mensaje)>0) $mensaje.=", ";
                $mensaje.="El talento: ".$detalle->getTalentos()." sin importe";
            }else{
                $cont=0;
                foreach($this->getEventos() as $evento){
                    if($evento->getTalentoId()==$detalle->getTalentoId()){
                        $cont++;
                    }
                }
                if($cont==0){
                    if(strlen($mensaje)>0) $mensaje.=", ";
                    $mensaje.="El talento: ".$detalle->getTalentos()." sin eventos";
                }
            }
        }
        return $mensaje;
    }
    
    public function aprobarEventos(){
        $eventos=$this->getCotizacionesEventos();
        foreach($eventos as $evento){
            $evento->getEventos()->mediacionEvento();
        }
    }
    public function congelarEventos(){
        $eventos=$this->getCotizacionesEventos();
        foreach($eventos as $evento){
            $evento->getEventos()->apartarEvento();
        }
    }
    public function delete(\Doctrine_Connection $conn = null) {
        if(!$this->statusAprobada() && $this->validarCancelarAprobacion()){
            foreach($this->getCotizacionesEventos() as $ce){
                $evento=$ce->getEventos();
                $evento->setSubject("Evento disponible");
                $evento->setDescription("Evento disponible o eliminar");
                $evento->setStatus(KsWCEventTable::$DISPONIBLE);
                $evento->save();
                $ce->delete();
            }
            foreach($this->getDetallesPagos() as $dp){
                $dp->delete();
            }
            
            foreach($this->getDetallesCotizacion() as $dc){
                $dc->delete();
            }
            
            parent::delete($conn);
            return true;
        }else{
            sfContext::getInstance()->getUser()->setFlash("error", "No es posible de eliminar");
        }
    }
    
}