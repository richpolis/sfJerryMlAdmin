<?php

/**
 * ContratosTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ContratosTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ContratosTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Contratos');
    }
    public function retrieveBackendContratosList(Doctrine_Query $q)
    {
      $rootAlias = $q->getRootAlias();
      $q->leftJoin($rootAlias . '.Cotizaciones cot');
      $q->leftJoin('cot.Clientes cli');
      $user=  sfContext::getInstance()->getUser()->getGuardUser();
      if(!$user->getIsSuperAdmin()){
            //evitar que todos vean todo. solo el super usuario.
            $q->andWhere('cot.user_id=?',$user->getId());    
      }
      return $q;
    }
    public function getContratoConContizacionesClientesForId($id){
      $q=$this->createQuery('contrato');  
      $rootAlias = $q->getRootAlias();
      $q->leftJoin($rootAlias . '.Cotizaciones cot');
      $q->leftJoin('cot.Clientes cli');
      $q->addWhere($rootAlias.'.id=?',$id);
      return $q->fetchOne();
    }
    
    public function getCriteriaUltimosContratos($activos=true){
        $q=$this->createQuery('contrato');  
        $rootAlias = $q->getRootAlias();
        $q->leftJoin($rootAlias . '.Cotizaciones cot');
        $q->leftJoin('cot.Clientes cli');
        $q->leftJoin('cot.Contactos co');
        $q->andWhere($rootAlias.'.is_active=?',$activos);
        $user=  sfContext::getInstance()->getUser()->getGuardUser();
        if(!$user->getIsSuperAdmin()){
            //evitar que todos vean todo. solo el super usuario.
            $q->andWhere('cot.user_id=?',$user->getId());    
        }
        
        return $q;
    }
    public function getUltimosContratos($activos=true){
        $q=$this->getCriteriaUltimosContratos($activos);
        return $q->execute();
    }
    
}