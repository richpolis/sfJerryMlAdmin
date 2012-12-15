<?php

/**
 * Contactos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Contactos extends BaseContactos
{
    public function __toString(){
        return $this->getNombreCompleto();
    }
    public function getNombreCompleto(){
        return sprintf("%s %s", $this->getName(), $this->getApellidos());
    }
    
}
