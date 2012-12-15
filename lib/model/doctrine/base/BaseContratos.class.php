<?php

/**
 * BaseContratos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $cotizacion_id
 * @property integer $user_id
 * @property string $file
 * @property boolean $esta_firmado
 * @property boolean $is_active
 * @property Cotizaciones $Cotizaciones
 * @property sfGuardUser $User
 * 
 * @method integer      getCotizacionId()  Returns the current record's "cotizacion_id" value
 * @method integer      getUserId()        Returns the current record's "user_id" value
 * @method string       getFile()          Returns the current record's "file" value
 * @method boolean      getEstaFirmado()   Returns the current record's "esta_firmado" value
 * @method boolean      getIsActive()      Returns the current record's "is_active" value
 * @method Cotizaciones getCotizaciones()  Returns the current record's "Cotizaciones" value
 * @method sfGuardUser  getUser()          Returns the current record's "User" value
 * @method Contratos    setCotizacionId()  Sets the current record's "cotizacion_id" value
 * @method Contratos    setUserId()        Sets the current record's "user_id" value
 * @method Contratos    setFile()          Sets the current record's "file" value
 * @method Contratos    setEstaFirmado()   Sets the current record's "esta_firmado" value
 * @method Contratos    setIsActive()      Sets the current record's "is_active" value
 * @method Contratos    setCotizaciones()  Sets the current record's "Cotizaciones" value
 * @method Contratos    setUser()          Sets the current record's "User" value
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseContratos extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('contratos');
        $this->hasColumn('cotizacion_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('file', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'default' => 'no_file.pdf',
             'length' => 255,
             ));
        $this->hasColumn('esta_firmado', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => false,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Cotizaciones', array(
             'local' => 'cotizacion_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}