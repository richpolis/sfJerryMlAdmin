<?php

/**
 * cotizaciones module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage cotizaciones
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  public function getActionsDefault()
  {
    return array();
  }

  public function getFormActions()
  {
    return array(  '_list' =>   array(    'label' => 'Regresar a Lista',  ),  '_save' =>   array(    'label' => 'Guardar',  ),  'cancelEdit' =>   array(    'label' => 'Cancelar',    'action' => 'show',  ),);
  }

  public function getNewActions()
  {
    return array();
  }

  public function getEditActions()
  {
    return array();
  }

  public function getListObjectActions()
  {
    return array(  'show' =>   array(    'label' => 'Mostrar',    'action' => 'show',  ),  '_delete' =>   array(    'label' => 'Desactivar',  ),  'select' =>   array(    'label' => 'Seleccionar',  ),  'remove' =>   array(    'label' => 'Quitar Seleccion',  ),);
  }

  public function getListActions()
  {
    return array(  'crear' =>   array(    'label' => 'Nueva Cotizacion',    'action' => 'crearCotizacion',  ),  'cancelar' =>   array(    'label' => 'Cancelar modo seleccion',    'action' => 'cancelarSeleccion',  ),);
  }

  public function getListBatchActions()
  {
    return array();
  }

  public function getListParams()
  {
    return '%%id%% - %%clientes%% - %%contactos%% - %%manager%% - %%descripcion%% - %%_importe_sin_iva%% - %%is_pay%% - %%string_status%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Cotizaciones';
  }

  public function getEditTitle()
  {
    return 'Editar Cotizacion %%descripcion%%';
  }

  public function getNewTitle()
  {
    return 'Crear Cotizacion';
  }

  public function getFilterDisplay()
  {
    return array(  0 => 'cotizaciones_by_cliente',  1 => 'cotizaciones_by_contacto',);
  }

  public function getFormDisplay()
  {
    return array();
  }

  public function getEditDisplay()
  {
    return array(  'Empresa' =>   array(    0 => 'empresa_id',  ),  'Cliente' =>   array(    0 => '_cliente_contacto',  ),  'Manager' =>   array(    0 => 'manager_id',  ),  'Comisionistas' =>   array(    0 => 'add_comisionistas',    1 => '_comisionistas',  ),  'Evento' =>   array(    0 => 'tipo_cotizacion',    1 => 'descripcion',    2 => 'plaza',    3 => 'vigencia',    4 => 'medios',    5 => 'fecha_desde',    6 => 'fecha_hasta',    7 => 'add_eventos',    8 => 'actividad',  ),  'Conceptos' =>   array(    0 => 'add_conceptos',  ),  'Pie Cotizacion' =>   array(    0 => 'requerimientos',    1 => 'is_active',  ),);
  }

  public function getNewDisplay()
  {
    return array(  'Empresa' =>   array(    0 => 'empresa_id',  ),  'Cliente' =>   array(    0 => '_cliente_contacto',  ),  'Manager' =>   array(    0 => 'manager_id',  ),  'Comisionistas' =>   array(    0 => 'add_comisionistas',    1 => '_add_comisionistas',  ),  'Evento' =>   array(    0 => 'tipo_cotizacion',    1 => 'descripcion',    2 => 'plaza',    3 => 'vigencia',    4 => 'medios',    5 => 'fecha_hasta',    6 => 'fecha_desde',    7 => 'actividad',    8 => 'add_eventos',  ),  'Conceptos' =>   array(    0 => 'add_conceptos',  ),  'Pie Cotizacion' =>   array(    0 => 'requerimientos',    1 => 'is_active',  ),);
  }

  public function getListDisplay()
  {
    return array(  0 => 'id',  1 => 'clientes',  2 => 'contactos',  3 => 'manager',  4 => 'descripcion',  5 => '_importe_sin_iva',  6 => 'is_pay',  7 => 'string_status',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'cliente_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'contacto_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'user_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'manager_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'empresa_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'descripcion' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Descripcion',),
      'actividad' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Actividad/Dias de trabajo',),
      'plaza' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Plaza/Territorio',),
      'fecha_desde' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'fecha_hasta' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'mostrar_horas' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',),
      'vigencia' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'medios' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'requerimientos' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'pdf' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Archivo',),
      'status' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'tipo_cotizacion' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'add_conceptos' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Conceptos',  'help' => 'Permitir agregar conceptos a nivel detalle de cotizacion',),
      'add_comisionistas' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Comisionistas',  'help' => 'Permitir agregar comisionistas a nivel detalle de cotizacion',),
      'add_eventos' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Eventos',  'help' => 'Permitir agregar eventos a nivel detalle de cotizacion',),
      'subtotal' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'iva' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'is_pay' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Pagado',),
      'is_active' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Activa',  'help' => 'Mostrar en Dashboard',),
      'monto_pagado_cliente' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'monto_pagado_talento' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'created_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'updated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'slug' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'clientes' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Empresa',),
      'contactos' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Contacto',),
      'manager' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Personal Manager',),
      'total' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Total',),
      '_comisionistas' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Listado',),
      '_conceptos' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Listado',),
      'cotizaciones_by_cliente' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Cliente',  'help' => 'Nombre Empresa o RFC',),
      'cotizaciones_by_contacto' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Contacto',  'help' => 'Nombre o apellidos',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id' => array(),
      'cliente_id' => array(),
      'contacto_id' => array(),
      'user_id' => array(),
      'manager_id' => array(),
      'empresa_id' => array(),
      'descripcion' => array(),
      'actividad' => array(),
      'plaza' => array(),
      'fecha_desde' => array(),
      'fecha_hasta' => array(),
      'mostrar_horas' => array(),
      'vigencia' => array(),
      'medios' => array(),
      'requerimientos' => array(),
      'pdf' => array(),
      'status' => array(),
      'tipo_cotizacion' => array(),
      'add_conceptos' => array(),
      'add_comisionistas' => array(),
      'add_eventos' => array(),
      'subtotal' => array(),
      'iva' => array(),
      'is_pay' => array(),
      'is_active' => array(),
      'monto_pagado_cliente' => array(),
      'monto_pagado_talento' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id' => array(),
      'cliente_id' => array(),
      'contacto_id' => array(),
      'user_id' => array(),
      'manager_id' => array(),
      'empresa_id' => array(),
      'descripcion' => array(),
      'actividad' => array(),
      'plaza' => array(),
      'fecha_desde' => array(),
      'fecha_hasta' => array(),
      'mostrar_horas' => array(),
      'vigencia' => array(),
      'medios' => array(),
      'requerimientos' => array(),
      'pdf' => array(),
      'status' => array(),
      'tipo_cotizacion' => array(),
      'add_conceptos' => array(),
      'add_comisionistas' => array(),
      'add_eventos' => array(),
      'subtotal' => array(),
      'iva' => array(),
      'is_pay' => array(),
      'is_active' => array(),
      'monto_pagado_cliente' => array(),
      'monto_pagado_talento' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id' => array(),
      'cliente_id' => array(),
      'contacto_id' => array(),
      'user_id' => array(),
      'manager_id' => array(),
      'empresa_id' => array(),
      'descripcion' => array(),
      'actividad' => array(),
      'plaza' => array(),
      'fecha_desde' => array(),
      'fecha_hasta' => array(),
      'mostrar_horas' => array(),
      'vigencia' => array(),
      'medios' => array(),
      'requerimientos' => array(),
      'pdf' => array(),
      'status' => array(),
      'tipo_cotizacion' => array(),
      'add_conceptos' => array(),
      'add_comisionistas' => array(),
      'add_eventos' => array(),
      'subtotal' => array(),
      'iva' => array(),
      'is_pay' => array(),
      'is_active' => array(),
      'monto_pagado_cliente' => array(),
      'monto_pagado_talento' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id' => array(),
      'cliente_id' => array(),
      'contacto_id' => array(),
      'user_id' => array(),
      'manager_id' => array(),
      'empresa_id' => array(),
      'descripcion' => array(),
      'actividad' => array(),
      'plaza' => array(),
      'fecha_desde' => array(),
      'fecha_hasta' => array(),
      'mostrar_horas' => array(),
      'vigencia' => array(),
      'medios' => array(),
      'requerimientos' => array(),
      'pdf' => array(),
      'status' => array(),
      'tipo_cotizacion' => array(),
      'add_conceptos' => array(),
      'add_comisionistas' => array(),
      'add_eventos' => array(),
      'subtotal' => array(),
      'iva' => array(),
      'is_pay' => array(),
      'is_active' => array(),
      'monto_pagado_cliente' => array(),
      'monto_pagado_talento' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id' => array(),
      'cliente_id' => array(),
      'contacto_id' => array(),
      'user_id' => array(),
      'manager_id' => array(),
      'empresa_id' => array(),
      'descripcion' => array(),
      'actividad' => array(),
      'plaza' => array(),
      'fecha_desde' => array(),
      'fecha_hasta' => array(),
      'mostrar_horas' => array(),
      'vigencia' => array(),
      'medios' => array(),
      'requerimientos' => array(),
      'pdf' => array(),
      'status' => array(),
      'tipo_cotizacion' => array(),
      'add_conceptos' => array(),
      'add_comisionistas' => array(),
      'add_eventos' => array(),
      'subtotal' => array(),
      'iva' => array(),
      'is_pay' => array(),
      'is_active' => array(),
      'monto_pagado_cliente' => array(),
      'monto_pagado_talento' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }


  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'CotizacionesForm';
  }

  public function hasFilterForm()
  {
    return true;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return 'CotizacionesFormFilter';
  }

  public function getPagerClass()
  {
    return 'sfDoctrinePager';
  }

  public function getPagerMaxPerPage()
  {
    return 20;
  }

  public function getDefaultSort()
  {
    return array('created_at', 'asc');
  }

  public function getTableMethod()
  {
    return 'retrieveBackendCotizacionesList';
  }

  public function getTableCountMethod()
  {
    return '';
  }
}
