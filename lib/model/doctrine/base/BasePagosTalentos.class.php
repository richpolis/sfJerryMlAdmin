<?php

/**
 * BasePagosTalentos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $talento_id
 * @property string $referencia
 * @property string $cuenta_deposito
 * @property double $importe
 * @property double $iva
 * @property double $isr
 * @property double $adeudo
 * @property boolean $is_cerrado
 * @property Talentos $Talentos
 * @property Doctrine_Collection $DetallesPagosTalentos
 * 
 * @method integer             getTalentoId()             Returns the current record's "talento_id" value
 * @method string              getReferencia()            Returns the current record's "referencia" value
 * @method string              getCuentaDeposito()        Returns the current record's "cuenta_deposito" value
 * @method double              getImporte()               Returns the current record's "importe" value
 * @method double              getIva()                   Returns the current record's "iva" value
 * @method double              getIsr()                   Returns the current record's "isr" value
 * @method double              getAdeudo()                Returns the current record's "adeudo" value
 * @method boolean             getIsCerrado()             Returns the current record's "is_cerrado" value
 * @method Talentos            getTalentos()              Returns the current record's "Talentos" value
 * @method Doctrine_Collection getDetallesPagosTalentos() Returns the current record's "DetallesPagosTalentos" collection
 * @method PagosTalentos       setTalentoId()             Sets the current record's "talento_id" value
 * @method PagosTalentos       setReferencia()            Sets the current record's "referencia" value
 * @method PagosTalentos       setCuentaDeposito()        Sets the current record's "cuenta_deposito" value
 * @method PagosTalentos       setImporte()               Sets the current record's "importe" value
 * @method PagosTalentos       setIva()                   Sets the current record's "iva" value
 * @method PagosTalentos       setIsr()                   Sets the current record's "isr" value
 * @method PagosTalentos       setAdeudo()                Sets the current record's "adeudo" value
 * @method PagosTalentos       setIsCerrado()             Sets the current record's "is_cerrado" value
 * @method PagosTalentos       setTalentos()              Sets the current record's "Talentos" value
 * @method PagosTalentos       setDetallesPagosTalentos() Sets the current record's "DetallesPagosTalentos" collection
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePagosTalentos extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('pagos_talentos');
        $this->hasColumn('talento_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('referencia', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
        $this->hasColumn('cuenta_deposito', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('importe', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('iva', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('isr', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('adeudo', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('is_cerrado', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => 0,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Talentos', array(
             'local' => 'talento_id',
             'foreign' => 'id'));

        $this->hasMany('DetallesPagosTalentos', array(
             'local' => 'id',
             'foreign' => 'pagos_talentos_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}