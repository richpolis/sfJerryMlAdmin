<?php

/**
 * Clientes filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseClientesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'razon_social'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
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
      'saldo'           => new sfWidgetFormFilterInput(),
      'is_active'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(),
      'contactos_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Contactos')),
    ));

    $this->setValidators(array(
      'razon_social'    => new sfValidatorPass(array('required' => false)),
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
      'saldo'           => new sfValidatorPass(array('required' => false)),
      'is_active'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'            => new sfValidatorPass(array('required' => false)),
      'contactos_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Contactos', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('clientes_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addContactosListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('ClientesContactos.cliente_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Clientes';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'razon_social'    => 'Text',
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
      'saldo'           => 'Text',
      'is_active'       => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'slug'            => 'Text',
      'contactos_list'  => 'ManyKey',
    );
  }
}
