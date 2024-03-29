<?php

/**
 * BaseTalentos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property text $descripcion
 * @property string $rfc
 * @property string $calle
 * @property string $numero_exterior
 * @property string $numero_interior
 * @property string $colonia
 * @property string $codigo_postal
 * @property string $cuidad
 * @property string $municipio
 * @property string $estado
 * @property string $pais
 * @property string $telefono
 * @property string $celular
 * @property string $email
 * @property string $imagen
 * @property float $margen_jerry_ml
 * @property double $saldo
 * @property boolean $is_active
 * @property Doctrine_Collection $Eventos
 * @property Doctrine_Collection $DetallesPrecotizacion
 * @property Doctrine_Collection $DetallesCotizacion
 * @property Doctrine_Collection $PagosTalentos
 * 
 * @method string              getName()                  Returns the current record's "name" value
 * @method text                getDescripcion()           Returns the current record's "descripcion" value
 * @method string              getRfc()                   Returns the current record's "rfc" value
 * @method string              getCalle()                 Returns the current record's "calle" value
 * @method string              getNumeroExterior()        Returns the current record's "numero_exterior" value
 * @method string              getNumeroInterior()        Returns the current record's "numero_interior" value
 * @method string              getColonia()               Returns the current record's "colonia" value
 * @method string              getCodigoPostal()          Returns the current record's "codigo_postal" value
 * @method string              getCuidad()                Returns the current record's "cuidad" value
 * @method string              getMunicipio()             Returns the current record's "municipio" value
 * @method string              getEstado()                Returns the current record's "estado" value
 * @method string              getPais()                  Returns the current record's "pais" value
 * @method string              getTelefono()              Returns the current record's "telefono" value
 * @method string              getCelular()               Returns the current record's "celular" value
 * @method string              getEmail()                 Returns the current record's "email" value
 * @method string              getImagen()                Returns the current record's "imagen" value
 * @method float               getMargenJerryMl()         Returns the current record's "margen_jerry_ml" value
 * @method double              getSaldo()                 Returns the current record's "saldo" value
 * @method boolean             getIsActive()              Returns the current record's "is_active" value
 * @method Doctrine_Collection getEventos()               Returns the current record's "Eventos" collection
 * @method Doctrine_Collection getDetallesPrecotizacion() Returns the current record's "DetallesPrecotizacion" collection
 * @method Doctrine_Collection getDetallesCotizacion()    Returns the current record's "DetallesCotizacion" collection
 * @method Doctrine_Collection getPagosTalentos()         Returns the current record's "PagosTalentos" collection
 * @method Talentos            setName()                  Sets the current record's "name" value
 * @method Talentos            setDescripcion()           Sets the current record's "descripcion" value
 * @method Talentos            setRfc()                   Sets the current record's "rfc" value
 * @method Talentos            setCalle()                 Sets the current record's "calle" value
 * @method Talentos            setNumeroExterior()        Sets the current record's "numero_exterior" value
 * @method Talentos            setNumeroInterior()        Sets the current record's "numero_interior" value
 * @method Talentos            setColonia()               Sets the current record's "colonia" value
 * @method Talentos            setCodigoPostal()          Sets the current record's "codigo_postal" value
 * @method Talentos            setCuidad()                Sets the current record's "cuidad" value
 * @method Talentos            setMunicipio()             Sets the current record's "municipio" value
 * @method Talentos            setEstado()                Sets the current record's "estado" value
 * @method Talentos            setPais()                  Sets the current record's "pais" value
 * @method Talentos            setTelefono()              Sets the current record's "telefono" value
 * @method Talentos            setCelular()               Sets the current record's "celular" value
 * @method Talentos            setEmail()                 Sets the current record's "email" value
 * @method Talentos            setImagen()                Sets the current record's "imagen" value
 * @method Talentos            setMargenJerryMl()         Sets the current record's "margen_jerry_ml" value
 * @method Talentos            setSaldo()                 Sets the current record's "saldo" value
 * @method Talentos            setIsActive()              Sets the current record's "is_active" value
 * @method Talentos            setEventos()               Sets the current record's "Eventos" collection
 * @method Talentos            setDetallesPrecotizacion() Sets the current record's "DetallesPrecotizacion" collection
 * @method Talentos            setDetallesCotizacion()    Sets the current record's "DetallesCotizacion" collection
 * @method Talentos            setPagosTalentos()         Sets the current record's "PagosTalentos" collection
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTalentos extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('talentos');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('descripcion', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('rfc', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 100,
             ));
        $this->hasColumn('calle', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('numero_exterior', 'string', 20, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 20,
             ));
        $this->hasColumn('numero_interior', 'string', 20, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 20,
             ));
        $this->hasColumn('colonia', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('codigo_postal', 'string', 10, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 10,
             ));
        $this->hasColumn('cuidad', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('municipio', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('estado', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('pais', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('telefono', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('celular', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('email', 'string', 150, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 150,
             ));
        $this->hasColumn('imagen', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'sin_imagen.jpg',
             'length' => 255,
             ));
        $this->hasColumn('margen_jerry_ml', 'float', null, array(
             'type' => 'float',
             'notnull' => false,
             'default' => 20,
             ));
        $this->hasColumn('saldo', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
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
        $this->hasMany('KsWCEvent as Eventos', array(
             'local' => 'id',
             'foreign' => 'talento_id'));

        $this->hasMany('DetallesPrecotizacion', array(
             'local' => 'id',
             'foreign' => 'talento_id'));

        $this->hasMany('DetallesCotizacion', array(
             'local' => 'id',
             'foreign' => 'talento_id'));

        $this->hasMany('PagosTalentos', array(
             'local' => 'id',
             'foreign' => 'talento_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'fields' => 
             array(
              0 => 'name',
             ),
             'unique' => true,
             'canUpdate' => true,
             ));
        $this->actAs($timestampable0);
        $this->actAs($sluggable0);
    }
}