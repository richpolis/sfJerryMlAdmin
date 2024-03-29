<?php

/**
 * ConfiguracionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ConfiguracionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ConfiguracionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Configuracion');
    }
    public function getSeccion($seccion){
        $q=Doctrine_Query::create()
                ->from('Configuracion conf')
                ->where('conf.slug=?',$seccion);
        return $q->fetchOne();
    }
}