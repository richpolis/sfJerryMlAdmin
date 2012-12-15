<?php

/**
 * BaseDetallesCotizacionConceptos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $detalles_cotizacion_id
 * @property integer $concepto_id
 * @property double $precio
 * @property DetallesCotizacion $DetallesCotizacion
 * @property Conceptos $Conceptos
 * 
 * @method integer                     getDetallesCotizacionId()   Returns the current record's "detalles_cotizacion_id" value
 * @method integer                     getConceptoId()             Returns the current record's "concepto_id" value
 * @method double                      getPrecio()                 Returns the current record's "precio" value
 * @method DetallesCotizacion          getDetallesCotizacion()     Returns the current record's "DetallesCotizacion" value
 * @method Conceptos                   getConceptos()              Returns the current record's "Conceptos" value
 * @method DetallesCotizacionConceptos setDetallesCotizacionId()   Sets the current record's "detalles_cotizacion_id" value
 * @method DetallesCotizacionConceptos setConceptoId()             Sets the current record's "concepto_id" value
 * @method DetallesCotizacionConceptos setPrecio()                 Sets the current record's "precio" value
 * @method DetallesCotizacionConceptos setDetallesCotizacion()     Sets the current record's "DetallesCotizacion" value
 * @method DetallesCotizacionConceptos setConceptos()              Sets the current record's "Conceptos" value
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDetallesCotizacionConceptos extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('detalles_cotizacion_conceptos');
        $this->hasColumn('detalles_cotizacion_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('concepto_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('precio', 'double', null, array(
             'type' => 'double',
             'default' => 0,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DetallesCotizacion', array(
             'local' => 'detalles_cotizacion_id',
             'foreign' => 'id'));

        $this->hasOne('Conceptos', array(
             'local' => 'concepto_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}