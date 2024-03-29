<?php

/**
 * EventosUsuariosTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EventosUsuariosTable extends PluginEventosUsuariosTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object EventosUsuariosTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventosUsuarios');
    }
    
    public function getCriteriaOrdenada(){
    	$q=Doctrine_Query::create()
    	->from('EventosUsuarios eu')
    	->orderBy('eu.start_time');
    	return $q;
    }
    
    
    public function getEventosPorFecha($fecha_inicial,$fecha_final){
    	$q=$this->getCriteriaOrdenada();
    	$q->addWhere('eu.start_time BETWEEN ? to ?',array($fecha_inicial,$fecha_final));
    	$rootAlias = $q->getRootAlias();
    	$q->leftJoin($rootAlias . '.User u');
        $user=  sfContext::getInstance()->getUser()->getGuardUser();
        //if(!$user->getIsSuperAdmin()){
            $q->andWhere('u.id=?',$user->getId());    
        //}
    	return $q->execute();
    }
    
    public function getProximosEventosUsuarios(){
        $fecha_inicial= new DateTime("now");
        $fecha_final = new DateTime("now");
        //$user=  sfContext::getInstance()->getUser()->getGuardUser();
        
        //if(!$user->getIsSuperAdmin()){
            $fecha_final->add(new DateInterval('P30D'));
        //}else{
        //    $fecha_final->add(new DateInterval('P15D'));
        //}
        
        return $this->getEventosPorFecha($fecha_inicial->format('Y-m-d'), $fecha_final->format('Y-m-d'));
        
    }
    
    public function getEventosPorFechaYUsuario($fecha_inicial,$fecha_final,$usuarioId){
    	$q=$this->getCriteriaOrdenada();
    	$rootAlias = $q->getRootAlias();
    	$q->addWhere($rootAlias.'.start_time >=?',$fecha_inicial)
          ->addWhere($rootAlias.'.end_time<=?',$fecha_final)
          ->addwhere($rootAlias.'.user_id=?',$usuarioId);    
        return $q->execute();
    }
}