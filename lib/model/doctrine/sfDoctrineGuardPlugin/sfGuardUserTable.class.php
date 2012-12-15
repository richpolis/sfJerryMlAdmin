<?php

/**
 * sfGuardUserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardUserTable extends PluginsfGuardUserTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object sfGuardUserTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardUser');
    }
    
    static $colores=array(
    "1"=>       "Color 1",
    "2"=>       "Color 2",
    "3"=>       "Color 3",
    "4"=>	"Color 4",
    "5"=>	"Color 5",
    "6"=>	"Color 6",
    "7"=>	"Color 7",
    "8"=>	"Color 8",
    "9"=>	"Color 9",
    "10"=>	"Color 10",
    "11"=>	"Color 11",
    "12"=>	"Color 12",
    "13"=>	"Color 13",
    "14"=>	"Color 14",
    "15"=>	"Color 15",
    "16"=>	"Color 16",
    "17"=>	"Color 17",
    "18"=>	"Color 18",
    "19"=>	"Color 19",
    "20"=>	"Color 20",
    );
    public function getColores()
    {
      return self::$colores;
    }
    
    public function retrieveBackendUsuariosList(Doctrine_Query $q)
    {
      $user=  sfContext::getInstance()->getUser();   
      $rootAlias = $q->getRootAlias();
      if(!$user->getGuardUser()->getIsSuperAdmin()){
        $q->addWhere($rootAlias.'.id=?',$user->getGuardUser()->getId());
      }
           
      return $q;
    }
}