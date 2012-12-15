<?php

/**
 * Contactos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseContactosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'apellidos'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'calle'           => new sfWidgetFormFilterInput(),
      'numero_exterior' => new sfWidgetFormFilterInput(),
      'numero_interior' => new sfWidgetFormFilterInput(),
      'colonia'         => new sfWidgetFormFilterInput(),
      'codigo_postal'   => new sfWidgetFormFilterInput(),
      'cuidad'          => new sfWidgetFormFilterInput(),
      'municipio'       => new sfWidgetFormFilterInput(),
      'estado'          => new sfWidgetFormFilterInput(),
      'pais'            => new sfWidgetFormFilterInput(),
      'telefono'        => new sfWidgetFormFilterInput(),
      'celular'         => new sfWidgetFormFilterInput(),
      'email'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_active'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(),
      'clientes_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Clientes')),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'apellidos'       => new sfValidatorPass(array('required' => false)),
      'calle'           => new sfValidatorPass(array('required' => false)),
      'numero_exterior' => new sfValidatorPass(array('required' => false)),
      'numero_interior' => new sfValidatorPass(array('required' => false)),
      'colonia'         => new sfValidatorPass(array('required' => false)),
      'codigo_postal'   => new sfValidatorPass(array('required' => false)),
      'cuidad'          => new sfValidatorPass(array('required' => false)),
      'municipio'       => new sfValidatorPass(array('required' => false)),
      'estado'          => new sfValidatorPass(array('required' => false)),
      'pais'            => new sfValidatorPass(array('required' => false)),
      'telefono'        => new sfValidatorPass(array('required' => false)),
      'celular'         => new sfValidatorPass(array('required' => false)),
      'email'           => new sfValidatorPass(array('required' => false)),
      'is_active'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'            => new sfValidatorPass(array('required' => false)),
      'clientes_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Clientes', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('contactos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addClientesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.ClientesContactos ClientesContactos')
      ->andWhereIn('ClientesContactos.contacto_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Contactos';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'apellidos'       => 'Text',
      'calle'           => 'Text',
      'numero_exterior' => 'Text',
      'numero_interior' => 'Text',
      'colonia'         => 'Text',
      'codigo_postal'   => 'Text',
      'cuidad'          => 'Text',
      'municipio'       => 'Text',
      'estado'          => 'Text',
      'pais'            => 'Text',
      'telefono'        => 'Text',
      'celular'         => 'Text',
      'email'           => 'Text',
      'is_active'       => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'slug'            => 'Text',
      'clientes_list'   => 'ManyKey',
    );
  }
}
