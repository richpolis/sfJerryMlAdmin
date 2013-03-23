<?php

/**
 * talentos module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage talentos
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTalentosGeneratorConfiguration extends sfModelGeneratorConfiguration
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
    return array(  'show' =>   array(    'label' => 'Mostrar',    'action' => 'show',  ),  '_edit' =>   array(    'label' => 'Editar',  ),  'inactive' =>   array(    'label' => 'Inactivar',    'action' => 'inactive',  ),  'active' =>   array(    'label' => 'Activar',    'action' => 'active',  ),  'select' =>   array(    'label' => 'Seleccionar',  ),  'remove' =>   array(    'label' => 'Quitar Seleccion',  ),  'calendar' =>   array(    'label' => 'Calendario',  ),);
  }

  public function getListActions()
  {
    return array(  '_new' =>   array(    'label' => 'Nuevo Talento',  ),  'cancelar' =>   array(    'label' => 'Cancelar modo seleccion',    'action' => 'cancelarSeleccion',  ),);
  }

  public function getListBatchActions()
  {
    return array(  'select' =>   array(    'label' => 'Seleccionar',  ),);
  }

  public function getListParams()
  {
    return '%%id%% - %%_imagen%% - %%name%% - %%_descripcion%% - %%is_active%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Lista de Talentos';
  }

  public function getEditTitle()
  {
    return 'Editar talento %%name%%';
  }

  public function getNewTitle()
  {
    return 'Nuevo talento';
  }

  public function getFilterDisplay()
  {
    return array(  0 => 'talento_by_name',);
  }

  public function getFormDisplay()
  {
    return array();
  }

  public function getEditDisplay()
  {
    return array(  'Talento' =>   array(    0 => 'name',    1 => 'descripcion',    2 => 'rfc',    3 => 'telefono',    4 => 'celular',    5 => 'email',    6 => 'imagen',    7 => 'margen_jerry_ml',  ),  'Direccion' =>   array(    0 => 'calle',    1 => 'numero_interior',    2 => 'numero_exterior',    3 => 'colonia',    4 => 'codigo_postal',    5 => 'cuidad',    6 => 'municipio',    7 => 'estado',    8 => 'pais',  ),);
  }

  public function getNewDisplay()
  {
    return array(  'Talento' =>   array(    0 => 'name',    1 => 'descripcion',    2 => 'rfc',    3 => 'telefono',    4 => 'celular',    5 => 'email',    6 => 'imagen',    7 => 'margen_jerry_ml',  ),  'Direccion' =>   array(    0 => 'calle',    1 => 'numero_interior',    2 => 'numero_exterior',    3 => 'colonia',    4 => 'codigo_postal',    5 => 'cuidad',    6 => 'municipio',    7 => 'estado',    8 => 'pais',  ),);
  }

  public function getListDisplay()
  {
    return array(  0 => 'id',  1 => '_imagen',  2 => 'name',  3 => '_descripcion',  4 => 'is_active',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'name' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Nombre',),
      'descripcion' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Descripcion',),
      'rfc' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'calle' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'numero_exterior' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'numero_interior' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'colonia' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'codigo_postal' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'cuidad' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'municipio' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'estado' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'pais' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'telefono' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Teléfono',),
      'celular' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Celular',),
      'email' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Email',),
      'imagen' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'margen_jerry_ml' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'saldo' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'is_active' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Activo?',  'help' => 'Para activar o desactivar registro',),
      'created_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'updated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'slug' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'direccion' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Dirección',),
      'talento_by_name' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Talento',  'help' => 'Nombre',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'descripcion' => array(),
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
      'telefono' => array(),
      'celular' => array(),
      'email' => array(),
      'imagen' => array(),
      'margen_jerry_ml' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'descripcion' => array(),
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
      'telefono' => array(),
      'celular' => array(),
      'email' => array(),
      'imagen' => array(),
      'margen_jerry_ml' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'descripcion' => array(),
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
      'telefono' => array(),
      'celular' => array(),
      'email' => array(),
      'imagen' => array(),
      'margen_jerry_ml' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'descripcion' => array(),
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
      'telefono' => array(),
      'celular' => array(),
      'email' => array(),
      'imagen' => array(),
      'margen_jerry_ml' => array(),
      'saldo' => array(),
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'descripcion' => array(),
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
      'telefono' => array(),
      'celular' => array(),
      'email' => array(),
      'imagen' => array(),
      'margen_jerry_ml' => array(),
      'saldo' => array(),
      'is_active' => array(),
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
    return 'TalentosForm';
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
    return 'TalentosFormFilter';
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
    return array('name', 'asc');
  }

  public function getTableMethod()
  {
    return 'retrieveBackendTalentosList';
  }

  public function getTableCountMethod()
  {
    return '';
  }
}
