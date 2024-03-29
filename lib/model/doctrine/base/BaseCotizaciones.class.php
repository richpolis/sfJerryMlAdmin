<?php

/**
 * BaseCotizaciones
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $cliente_id
 * @property integer $contacto_id
 * @property integer $user_id
 * @property integer $manager_id
 * @property integer $empresa_id
 * @property string $descripcion
 * @property text $actividad
 * @property string $plaza
 * @property datetime $fecha_desde
 * @property datetime $fecha_hasta
 * @property boolean $mostrar_horas
 * @property string $vigencia
 * @property string $medios
 * @property text $requerimientos
 * @property string $pdf
 * @property integer $status
 * @property integer $tipo_cotizacion
 * @property boolean $add_conceptos
 * @property boolean $add_comisionistas
 * @property boolean $add_eventos
 * @property double $subtotal
 * @property double $iva
 * @property boolean $is_pay
 * @property boolean $is_active
 * @property double $monto_pagado_cliente
 * @property double $monto_pagado_talento
 * @property Clientes $Clientes
 * @property Contactos $Contactos
 * @property sfGuardUser $User
 * @property sfGuardUser $Manager
 * @property Empresas $Empresas
 * @property Doctrine_Collection $EventosUsuarios
 * @property Doctrine_Collection $DetallesCotizacion
 * @property Doctrine_Collection $DetallesPagos
 * @property Doctrine_Collection $Contratos
 * @property Doctrine_Collection $Facturas
 * @property Doctrine_Collection $CotizacionesConceptos
 * @property Doctrine_Collection $CotizacionesComisionistas
 * 
 * @method integer             getClienteId()                 Returns the current record's "cliente_id" value
 * @method integer             getContactoId()                Returns the current record's "contacto_id" value
 * @method integer             getUserId()                    Returns the current record's "user_id" value
 * @method integer             getManagerId()                 Returns the current record's "manager_id" value
 * @method integer             getEmpresaId()                 Returns the current record's "empresa_id" value
 * @method string              getDescripcion()               Returns the current record's "descripcion" value
 * @method text                getActividad()                 Returns the current record's "actividad" value
 * @method string              getPlaza()                     Returns the current record's "plaza" value
 * @method datetime            getFechaDesde()                Returns the current record's "fecha_desde" value
 * @method datetime            getFechaHasta()                Returns the current record's "fecha_hasta" value
 * @method boolean             getMostrarHoras()              Returns the current record's "mostrar_horas" value
 * @method string              getVigencia()                  Returns the current record's "vigencia" value
 * @method string              getMedios()                    Returns the current record's "medios" value
 * @method text                getRequerimientos()            Returns the current record's "requerimientos" value
 * @method string              getPdf()                       Returns the current record's "pdf" value
 * @method integer             getStatus()                    Returns the current record's "status" value
 * @method integer             getTipoCotizacion()            Returns the current record's "tipo_cotizacion" value
 * @method boolean             getAddConceptos()              Returns the current record's "add_conceptos" value
 * @method boolean             getAddComisionistas()          Returns the current record's "add_comisionistas" value
 * @method boolean             getAddEventos()                Returns the current record's "add_eventos" value
 * @method double              getSubtotal()                  Returns the current record's "subtotal" value
 * @method double              getIva()                       Returns the current record's "iva" value
 * @method boolean             getIsPay()                     Returns the current record's "is_pay" value
 * @method boolean             getIsActive()                  Returns the current record's "is_active" value
 * @method double              getMontoPagadoCliente()        Returns the current record's "monto_pagado_cliente" value
 * @method double              getMontoPagadoTalento()        Returns the current record's "monto_pagado_talento" value
 * @method Clientes            getClientes()                  Returns the current record's "Clientes" value
 * @method Contactos           getContactos()                 Returns the current record's "Contactos" value
 * @method sfGuardUser         getUser()                      Returns the current record's "User" value
 * @method sfGuardUser         getManager()                   Returns the current record's "Manager" value
 * @method Empresas            getEmpresas()                  Returns the current record's "Empresas" value
 * @method Doctrine_Collection getEventosUsuarios()           Returns the current record's "EventosUsuarios" collection
 * @method Doctrine_Collection getDetallesCotizacion()        Returns the current record's "DetallesCotizacion" collection
 * @method Doctrine_Collection getDetallesPagos()             Returns the current record's "DetallesPagos" collection
 * @method Doctrine_Collection getContratos()                 Returns the current record's "Contratos" collection
 * @method Doctrine_Collection getFacturas()                  Returns the current record's "Facturas" collection
 * @method Doctrine_Collection getCotizacionesConceptos()     Returns the current record's "CotizacionesConceptos" collection
 * @method Doctrine_Collection getCotizacionesComisionistas() Returns the current record's "CotizacionesComisionistas" collection
 * @method Cotizaciones        setClienteId()                 Sets the current record's "cliente_id" value
 * @method Cotizaciones        setContactoId()                Sets the current record's "contacto_id" value
 * @method Cotizaciones        setUserId()                    Sets the current record's "user_id" value
 * @method Cotizaciones        setManagerId()                 Sets the current record's "manager_id" value
 * @method Cotizaciones        setEmpresaId()                 Sets the current record's "empresa_id" value
 * @method Cotizaciones        setDescripcion()               Sets the current record's "descripcion" value
 * @method Cotizaciones        setActividad()                 Sets the current record's "actividad" value
 * @method Cotizaciones        setPlaza()                     Sets the current record's "plaza" value
 * @method Cotizaciones        setFechaDesde()                Sets the current record's "fecha_desde" value
 * @method Cotizaciones        setFechaHasta()                Sets the current record's "fecha_hasta" value
 * @method Cotizaciones        setMostrarHoras()              Sets the current record's "mostrar_horas" value
 * @method Cotizaciones        setVigencia()                  Sets the current record's "vigencia" value
 * @method Cotizaciones        setMedios()                    Sets the current record's "medios" value
 * @method Cotizaciones        setRequerimientos()            Sets the current record's "requerimientos" value
 * @method Cotizaciones        setPdf()                       Sets the current record's "pdf" value
 * @method Cotizaciones        setStatus()                    Sets the current record's "status" value
 * @method Cotizaciones        setTipoCotizacion()            Sets the current record's "tipo_cotizacion" value
 * @method Cotizaciones        setAddConceptos()              Sets the current record's "add_conceptos" value
 * @method Cotizaciones        setAddComisionistas()          Sets the current record's "add_comisionistas" value
 * @method Cotizaciones        setAddEventos()                Sets the current record's "add_eventos" value
 * @method Cotizaciones        setSubtotal()                  Sets the current record's "subtotal" value
 * @method Cotizaciones        setIva()                       Sets the current record's "iva" value
 * @method Cotizaciones        setIsPay()                     Sets the current record's "is_pay" value
 * @method Cotizaciones        setIsActive()                  Sets the current record's "is_active" value
 * @method Cotizaciones        setMontoPagadoCliente()        Sets the current record's "monto_pagado_cliente" value
 * @method Cotizaciones        setMontoPagadoTalento()        Sets the current record's "monto_pagado_talento" value
 * @method Cotizaciones        setClientes()                  Sets the current record's "Clientes" value
 * @method Cotizaciones        setContactos()                 Sets the current record's "Contactos" value
 * @method Cotizaciones        setUser()                      Sets the current record's "User" value
 * @method Cotizaciones        setManager()                   Sets the current record's "Manager" value
 * @method Cotizaciones        setEmpresas()                  Sets the current record's "Empresas" value
 * @method Cotizaciones        setEventosUsuarios()           Sets the current record's "EventosUsuarios" collection
 * @method Cotizaciones        setDetallesCotizacion()        Sets the current record's "DetallesCotizacion" collection
 * @method Cotizaciones        setDetallesPagos()             Sets the current record's "DetallesPagos" collection
 * @method Cotizaciones        setContratos()                 Sets the current record's "Contratos" collection
 * @method Cotizaciones        setFacturas()                  Sets the current record's "Facturas" collection
 * @method Cotizaciones        setCotizacionesConceptos()     Sets the current record's "CotizacionesConceptos" collection
 * @method Cotizaciones        setCotizacionesComisionistas() Sets the current record's "CotizacionesComisionistas" collection
 * 
 * @package    sfJerryMlAdmin
 * @subpackage model
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCotizaciones extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cotizaciones');
        $this->hasColumn('cliente_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('contacto_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('manager_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('empresa_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('descripcion', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'Descripicon evento',
             'length' => 255,
             ));
        $this->hasColumn('actividad', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('plaza', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('fecha_desde', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('fecha_hasta', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('mostrar_horas', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => false,
             ));
        $this->hasColumn('vigencia', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'default' => '1 año',
             'length' => 255,
             ));
        $this->hasColumn('medios', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('requerimientos', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('pdf', 'string', 255, array(
             'type' => 'string',
             'default' => 'sin_archivo.pdf',
             'length' => 255,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 1,
             ));
        $this->hasColumn('tipo_cotizacion', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => false,
             'default' => 1,
             'length' => 1,
             ));
        $this->hasColumn('add_conceptos', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => true,
             ));
        $this->hasColumn('add_comisionistas', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => true,
             ));
        $this->hasColumn('add_eventos', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => true,
             ));
        $this->hasColumn('subtotal', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('iva', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('is_pay', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => false,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             'default' => true,
             ));
        $this->hasColumn('monto_pagado_cliente', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
             ));
        $this->hasColumn('monto_pagado_talento', 'double', null, array(
             'type' => 'double',
             'notnull' => false,
             'default' => 0,
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

        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as Manager', array(
             'local' => 'manager_id',
             'foreign' => 'id'));

        $this->hasOne('Empresas', array(
             'local' => 'empresa_id',
             'foreign' => 'id'));

        $this->hasMany('EventosUsuarios', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $this->hasMany('DetallesCotizacion', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $this->hasMany('DetallesPagos', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $this->hasMany('Contratos', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $this->hasMany('Facturas', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $this->hasMany('CotizacionesConceptos', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $this->hasMany('CotizacionesComisionistas', array(
             'local' => 'id',
             'foreign' => 'cotizacion_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'fields' => 
             array(
              0 => 'descripcion',
             ),
             'unique' => true,
             'canUpdate' => true,
             ));
        $this->actAs($timestampable0);
        $this->actAs($sluggable0);
    }
}