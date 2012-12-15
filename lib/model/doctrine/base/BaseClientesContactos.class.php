<?php

/**
 * BaseClientesContactos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $cliente_id
 * @property integer $contacto_id
 * @property Clientes $Clientes
 * @property Contactos $Contactos
 * 
 * @method integer           getClienteId()   Returns the current record's "cliente_id" value
 * @method integer           getContactoId()  Returns the current record's "contacto_id" value
 * @method Clientes          getClientes()    Returns the current record's "Clientes" value
 * @method Contactos         getContactos()   Returns the current record's "Contactos" value
 * @method ClientesContactos setClienteId()   Sets the current record's "cliente_id" value
 * @method ClientesContactos setContactoId()  Sets the current record's "contacto_id" value
 * @method ClientesContactos setClientes()    Sets the current record's "Clientes" value
 * @method ClientesContactos setContactos()   Sets the current record's "Contactos" value
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseClientesContactos extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('clientes_contactos');
        $this->hasColumn('cliente_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('contacto_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));

        $this->option('symfony', array(
             'form' => false,
             'filter' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Clientes', array(
             'local' => 'cliente_id',
             'foreign' => 'id'));

        $this->hasOne('Contactos', array(
             'local' => 'contacto_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             ));
        $this->actAs($timestampable0);
    }
}