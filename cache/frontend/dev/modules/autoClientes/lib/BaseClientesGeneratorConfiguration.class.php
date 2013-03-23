<?php

/**
 * clientes module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage clientes
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseClientesGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  public function getActionsDefault()
  {
    return array();
  }

  public function getFormActions()
  {
    return array(  '_list' =>   array(    'label' => 'Regresar a Lista',  ),  '_save' =>   array(    'label' => 'Guardar',  ),);
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
    return array(  'show' =>   array(    'label' => 'Mostrar',    'action' => 'show',  ),  '_edit' =>   array(    'label' => 'Editar',  ),  'inactive' =>   array(    'label' => 'Inactivar',    'action' => 'inactive',  ),  'active' =>   array(    'label' => 'Activar',    'action' => 'active',  ),  'select' =>   array(    'label' => 'Seleccionar',  ),);
  }

  public function getListActions()
  {
    return array(  '_new' =>   array(    'label' => 'Nueva Empresa',  ),  'cancelar' =>   array(    'label' => 'Cancelar modo seleccion',    'action' => 'cancelarSeleccion',  ),);
  }

  public function getListBatchActions()
  {
    return array(  '_delete' => NULL,);
  }

  public function getListParams()
  {
    return '%%id%% - %%razon_social%% - %%rfc%% - %%is_active%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Lista de Empresas';
  }

  public function getEditTitle()
  {
    return 'Editar Empresa %%razon_social%%';
  }

  public function getNewTitle()
  {
    return 'Nueva Empresa';
  }

  public function getFilterDisplay()
  {
    return array(  0 => 'cliente_by_razon_social',  1 => 'cliente_by_contactos',);
  }

  public function getFormDisplay()
  {
    return array();
  }

  public function getEditDisplay()
  {
    return array();
  }

  public function getNewDisplay()
  {
    return array();
  }

  public function getListDisplay()
  {
    return array(  0 => 'id',  1 => 'razon_social',  2 => 'rfc',  3 => 'is_active',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'razon_social' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Nombre empresa',),
      'rfc' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'RFC',),
      'calle' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'numero_exterior' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'numero_interior' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'colonia' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'codigo_postal' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'cuidad' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'municipio' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'estado' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'pais' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'saldo' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'is_active' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Activo?',  'help' => 'Para activar o desactivar registro',),
      'created_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'updated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'slug' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'contactos_list' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'cliente_by_razon_social' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Empresa',  'help' => 'Nombre empresa o RFC',),
      'cliente_by_contactos' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Contacto',  'help' => 'Nombre o apellidos',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id' => array(),
      'razon_social' => array(),
      'rfc' => array(),
      'calle' => array(),
      'numero_exterior' => array(),
      'numero_interior' => array(),
      'colonia' => array(),
      'codigo_postal' => array(),
      'cuidad' => array(),
      'municipio' => array(),
      'estado' => array(),
      'pais' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'contactos_list' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id' => array(),
      'razon_social' => array(),
      'rfc' => array(),
      'calle' => array(),
      'numero_exterior' => array(),
      'numero_interior' => array(),
      'colonia' => array(),
      'codigo_postal' => array(),
      'cuidad' => array(),
      'municipio' => array(),
      'estado' => array(),
      'pais' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'contactos_list' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id' => array(),
      'razon_social' => array(),
      'rfc' => array(),
      'calle' => array(),
      'numero_exterior' => array(),
      'numero_interior' => array(),
      'colonia' => array(),
      'codigo_postal' => array(),
      'cuidad' => array(),
      'municipio' => array(),
      'estado' => array(),
      'pais' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'contactos_list' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id' => array(),
      'razon_social' => array(),
      'rfc' => array(),
      'calle' => array(),
      'numero_exterior' => array(),
      'numero_interior' => array(),
      'colonia' => array(),
      'codigo_postal' => array(),
      'cuidad' => array(),
      'municipio' => array(),
      'estado' => array(),
      'pais' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'contactos_list' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id' => array(),
      'razon_social' => array(),
      'rfc' => array(),
      'calle' => array(),
      'numero_exterior' => array(),
      'numero_interior' => array(),
      'colonia' => array(),
      'codigo_postal' => array(),
      'cuidad' => array(),
      'municipio' => array(),
      'estado' => array(),
      'pais' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'contactos_list' => array(),
    );
  }


  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'ClientesForm';
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
    return 'ClientesFormFilter';
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
    return array('razon_social', 'asc');
  }

  public function getTableMethod()
  {
    return 'retrieveBackendClientesList';
  }

  public function getTableCountMethod()
  {
    return '';
  }
}
