<?php

/**
 * DetallesCotizacionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class DetallesCotizacionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object DetallesCotizacionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('DetallesCotizacion');
    }
    public function getCriteriaOrdenada(){
        $q=Doctrine_Query::create()
           ->from('DetallesCotizacion dc')
           ->orderBy('dc.position asc');
        return $q;
    }
    public function getCriteriaOrdenadaPorActualizacion(){
        $q=Doctrine_Query::create()
                ->from('DetallesCotizacion dc')
                ->orderBy('dc.updated_at DESC');
        return $q;
    }
    public function getCriteriaUltimosRegistros(){
        $q=$this->getCriteriaOrdenadaPorActualizacion();
        $rootAlias = $q->getRootAlias();
        $q->leftJoin($rootAlias . '.Talentos t');
        return $q;
    }
    public function getCriteriaPorBusqueda($valores){
        $q=$this->getCriteriaOrdenada();
        
        foreach($valores as $field=>$valor){
            if(($field=="busqueda_talentos") && ($strlen($valor)>0)){
                $q->orWhere('i.name like %?%', $valor);
                $q->orWhere('i.apellidos like %?%', $valor);
            }elseif(($field=="busqueda_representante") && ($strlen($valor)>0)){
                $rootAlias = $q->getRootAlias();
                $q->leftJoin($rootAlias . '.Representantes c');
                $q->orWhere('c.name like %?%',$valor);
                $q->orWhere('c.apellidos like %?%',$valor);
            }elseif(($field=="busqueda_direccion") && ($strlen($valor)>0)){
                $q->orWhere('i.direccion like %?%', $valor);
            }elseif(($field=="busqueda_actividad_general") && ($strlen($valor)>0)){
                $q->orWhere('i.actividad_general like %?%', $valor);
            }
        }
        
        return $q;
    }
    public function getDetallesCotizacionConTalentos($cotizacion){
        $q=$this->getCriteriaOrdenada();
        $rootAlias = $q->getRootAlias();
        $q->addWhere($rootAlias.'.cotizacion_id=?',$cotizacion);
        $q->leftJoin($rootAlias . '.Talentos t');
        return $q->execute();
    }
    
    public function getDetallesCotizacionPorFecha($fecha_inicial,$fecha_final){
        $q=$this->getCriteriaOrdenada();
        $rootAlias = $q->getRootAlias();
        $q->addWhere($rootAlias.'.updated_at BETWEEN ? to ?',array($fecha_inicial,$fecha_final));
        $q->leftJoin($rootAlias . '.Talentos g');
        $q->addOrderBy($rootAlias . '.created_at asc');
        return $q->execute();
    }
    
    public function getDetallesCotizacionPorCotizacion($cotizacion){
        $q=$this->getCriteriaOrdenada();
        $rootAlias = $q->getRootAlias();
        $q->addWhere($rootAlias.'.cotizacion_id=?',$cotizacion);
        return $q->execute();
    }
    
   public function addCriteriaPublicacionesActivas($q){
        $rootAlias = $q->getRootAlias();
        $q->addWhere($rootAlias.'.is_active=?',true);
        return $q;
    }
    public function getMaximo(){
        $q=$this->getCriteriaOrdenada();
        $cmd=$q->execute();
        return $cmd->count()+1;
    }
    public function retrieveBackendClientesList(Doctrine_Query $q)
    {
        $q=$this->getCriteriaOrdenada();   
        $rootAlias = $q->getRootAlias();
        $q->leftJoin($rootAlias . '.Contactos co');
        return $q;
    }
    
    public function getDetallesCotizacionPorPagosTalentos($pago,$pagadas=false){
        $q=$this->createQuery('dc');  
        $rootAlias = $q->getRootAlias();
        $q->leftJoin($rootAlias . '.DetallesPagosTalentos dpt');
        $q->leftJoin('dpt.PagosTalentos pt');
        //$q->where($rootAlias . '.is_pay_talento=?',$pagadas);
        $q->addWhere('pt.id=?',$pago);
        //$q->addWhere('dp.status<=1');// 0 en captura, 1 aprobada, 2 pagado
        $q->addOrderBy('dpt.detalles_cotizacion_id asc');
        $q->addOrderBy('dpt.created_at asc');
        return $q->execute();
    }
    
    public function getDetallesCotizacionPendientesDePago($pagadas=false){
        $q=$this->createQuery('dc');  
        $rootAlias = $q->getRootAlias();
        $q->leftJoin($rootAlias . '.DetallesPagosTalentos dpt');
        $q->leftJoin($rootAlias . '.Talentos t');
        $q->leftJoin($rootAlias . '.Cotizaciones cot');
        $q->leftJoin('dpt.PagosTalentos pt');
        $q->where($rootAlias . '.is_pay_talento=?',$pagadas);
        $user=  sfContext::getInstance()->getUser()->getGuardUser();
        if(!$user->getIsSuperAdmin()){
           $q->addWhere('cot.user_id=?',$user->getId());
        }
        
        $q->addOrderBy('dpt.detalles_cotizacion_id asc');
        $q->addOrderBy('dpt.created_at asc');
        return $q->execute();
    }
    
    
    
}