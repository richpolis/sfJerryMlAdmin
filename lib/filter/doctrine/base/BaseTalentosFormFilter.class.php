<?php

/**
 * Talentos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTalentosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'descripcion'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rfc'             => new sfWidgetFormFilterInput(),
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
      'email'           => new sfWidgetFormFilterInput(),
      'imagen'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'margen_jerry_ml' => new sfWidgetFormFilterInput(),
      'saldo'           => new sfWidgetFormFilterInput(),
      'is_active'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'descripcion'     => new sfValidatorPass(array('required' => false)),
      'rfc'             => new sfValidatorPass(array('required' => false)),
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
      'imagen'          => new sfValidatorPass(array('required' => false)),
      'margen_jerry_ml' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'saldo'           => new sfValidatorPass(array('required' => false)),
      'is_active'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('talentos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Talentos';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'descripcion'     => 'Text',
      'rfc'             => 'Text',
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
      'imagen'          => 'Text',
      'margen_jerry_ml' => 'Number',
      'saldo'           => 'Text',
      'is_active'       => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'slug'            => 'Text',
    );
  }
}
