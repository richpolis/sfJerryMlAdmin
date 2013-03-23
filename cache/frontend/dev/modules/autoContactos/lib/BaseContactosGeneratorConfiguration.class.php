<?php

/**
 * contactos module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage contactos
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseContactosGeneratorConfiguration extends sfModelGeneratorConfiguration
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
    return array(  'show' =>   array(    'label' => 'Mostrar',  ),  '_edit' =>   array(    'label' => 'Editar',  ),  'inactive' =>   array(    'label' => 'Inactivar',    'action' => 'inactive',  ),  'active' =>   array(    'label' => 'Activar',    'action' => 'active',  ),  'select' =>   array(    'label' => 'Seleccionar',  ),);
  }

  public function getListActions()
  {
    return array(  '_new' =>   array(    'label' => 'Nuevo Contacto',  ),  'cancelar' =>   array(    'label' => 'Cancelar modo seleccion',    'action' => 'cancelarSeleccion',  ),);
  }

  public function getListBatchActions()
  {
    return array(  '_delete' => NULL,);
  }

  public function getListParams()
  {
    return '%%id%% - %%nombre_completo%% - %%telefono%% - %%celular%% - %%email%% - %%is_active%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Lista de Contactos';
  }

  public function getEditTitle()
  {
    return 'Editar contacto %%name%% %%apellidos%%';
  }

  public function getNewTitle()
  {
    return 'Nuevo contacto';
  }

  public function getFilterDisplay()
  {
    return array(  0 => 'contacto_by_cliente',  1 => 'contacto_by_name',);
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
    return array(  0 => 'id',  1 => 'nombre_completo',  2 => 'telefono',  3 => 'celular',  4 => 'email',  5 => 'is_active',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'name' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Nombre',),
      'apellidos' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Apellidos',),
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
      'is_active' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Activo?',  'help' => 'Para activar o desactivar registro',),
      'created_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'updated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'slug' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'clientes_list' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Empresa',),
      'nombre_completo' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Nombre',),
      'direccion' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Dirección',),
      'clientes_contactos' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Empresa',),
      'contacto_by_cliente' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Empresa',  'help' => 'Nombre Empresa o RFC',),
      'contacto_by_name' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Contacto',  'help' => 'Nombre o apellidos',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'apellidos' => array(),
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
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'clientes_list' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'apellidos' => array(),
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
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'clientes_list' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'apellidos' => array(),
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
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'clientes_list' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'apellidos' => array(),
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
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'clientes_list' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id' => array(),
      'name' => array(),
      'apellidos' => array(),
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
      'is_active' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'slug' => array(),
      'clientes_list' => array(),
    );
  }


  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'ContactosForm';
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
    return 'ContactosFormFilter';
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
    return array('apellidos', 'asc');
  }

  public function getTableMethod()
  {
    return '';
  }

  public function getTableCountMethod()
  {
    return '';
  }
}
