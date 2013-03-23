<?php

/**
 * EmpresasTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EmpresasTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EmpresasTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Empresas');
    }
    
    public function getEmpresas() 
    {
      return Doctrine_Query::create()
        ->select('*')
        ->from('Empresas')
        ->where('inactivar IS NULL')
        ->orWhere('inactivar >= ? ', date("Y-m-d"))
        ->execute();
    }
}