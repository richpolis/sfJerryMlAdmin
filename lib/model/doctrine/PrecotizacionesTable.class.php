<?php

/**
 * PrecotizacionesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PrecotizacionesTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CotizacionesTable
     */
    static public $status = array(
        '0' => 'incompleto',
        '1' => 'Enviado a Cliente',
        '2' => 'Aprobado',
        '-1' => 'Cancelado',
    );
    
    static public $CANCELADO = -1;      
    static public $INCOMPLETO = 0;      
    static public $MEDIACION = 1;      
    static public $APROBADA = 2;      


    public function getTypesStatus()
    {
      return self::$status;
    }
    
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Precotizaciones');
    }
    public function retrieveBackendPreCotizacionesList(Doctrine_Query $q)
    {
      $user=  sfContext::getInstance()->getUser()->getGuardUser();  
      $rootAlias = $q->getRootAlias();
      $q->leftJoin($rootAlias . '.Clientes cli');
      $q->leftJoin($rootAlias . '.Contactos co');
      if(!$user->getIsSuperAdmin()){
        $q->addWhere($rootAlias.'.user_id=?',$user->getId());
      }
      $q->orderBy($rootAlias.'.inicia_evento desc');
      return $q;
    }
    public function getPrecotizacionConClienteTalentoDetallesForId($id){
      $q=$this->createQuery('pre');  
      $rootAlias = $q->getRootAlias();
      $q->leftJoin($rootAlias . '.Clientes cli');
      $q->leftJoin($rootAlias . '.Contactos co');
      $q->leftJoin($rootAlias . '.DetallesPrecotizacion dp');
      $q->leftJoin('dp.Talentos t');
      $q->addWhere($rootAlias.'.id=?',$id);
      $q->orderBy('dp.position asc');
      return $q->fetchOne();
    }
    public function getCriteriaUltimasPrecotizaciones($activas = true){
        $q=$this->createQuery('pre');  
        $rootAlias = $q->getRootAlias();
        $user=  sfContext::getInstance()->getUser()->getGuardUser(); 
        $q->addWhere($rootAlias.'.user_id=?',$user->getId());
        $q->leftJoin($rootAlias . '.Clientes cli');
        $q->leftJoin($rootAlias . '.Contactos co');
        $q->leftJoin($rootAlias . '.DetallesPrecotizacion dp');
        $q->leftJoin('dp.Talentos t');
        $q->orderBy('dp.position asc');
        $q->andWhere($rootAlias.'.is_active=?',$activas);
        return $q;
    }
    public function getUltimasPrecotizaciones($activas=true){
        $q=$this->getCriteriaUltimasPrecotizaciones($activas);
        return $q->execute();
    }
}