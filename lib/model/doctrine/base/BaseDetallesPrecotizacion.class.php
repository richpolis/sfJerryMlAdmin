<?php

/**
 * BaseDetallesPrecotizacion
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $precotizacion_id
 * @property integer $talento_id
 * @property text $actividad
 * @property double $precio
 * @property float $margen_jerry_ml
 * @property integer $is_active
 * @property integer $position
 * @property Precotizaciones $Precotizaciones
 * @property Talentos $Talentos
 * 
 * @method integer               getPrecotizacionId()  Returns the current record's "precotizacion_id" value
 * @method integer               getTalentoId()        Returns the current record's "talento_id" value
 * @method text                  getActividad()        Returns the current record's "actividad" value
 * @method double                getPrecio()           Returns the current record's "precio" value
 * @method float                 getMargenJerryMl()    Returns the current record's "margen_jerry_ml" value
 * @method integer               getIsActive()         Returns the current record's "is_active" value
 * @method integer               getPosition()         Returns the current record's "position" value
 * @method Precotizaciones       getPrecotizaciones()  Returns the current record's "Precotizaciones" value
 * @method Talentos              getTalentos()         Returns the current record's "Talentos" value
 * @method DetallesPrecotizacion setPrecotizacionId()  Sets the current record's "precotizacion_id" value
 * @method DetallesPrecotizacion setTalentoId()        Sets the current record's "talento_id" value
 * @method DetallesPrecotizacion setActividad()        Sets the current record's "actividad" value
 * @method DetallesPrecotizacion setPrecio()           Sets the current record's "precio" value
 * @method DetallesPrecotizacion setMargenJerryMl()    Sets the current record's "margen_jerry_ml" value
 * @method DetallesPrecotizacion setIsActive()         Sets the current record's "is_active" value
 * @method DetallesPrecotizacion setPosition()         Sets the current record's "position" value
 * @method DetallesPrecotizacion setPrecotizaciones()  Sets the current record's "Precotizaciones" value
 * @method DetallesPrecotizacion setTalentos()         Sets the current record's "Talentos" value
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDetallesPrecotizacion extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('detalles_precotizacion');
        $this->hasColumn('precotizacion_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('talento_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('actividad', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('precio', 'double', null, array(
             'type' => 'double',
             'default' => 0,
             ));
        $this->hasColumn('margen_jerry_ml', 'float', null, array(
             'type' => 'float',
             'default' => 20,
             ));
        $this->hasColumn('is_active', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 1,
             ));
        $this->hasColumn('position', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Precotizaciones', array(
             'local' => 'precotizacion_id',
             'foreign' => 'id'));

        $this->hasOne('Talentos', array(
             'local' => 'talento_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}