<?php

/**
 * Cotizaciones filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cliente_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'contacto_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'add_empty' => true)),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'manager_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'add_empty' => true)),
      'evento'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'descripcion'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pdf'          => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'comisionista' => new sfWidgetFormFilterInput(),
      'subtotal'     => new sfWidgetFormFilterInput(),
      'iva'          => new sfWidgetFormFilterInput(),
      'is_pay'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_active'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'version'      => new sfWidgetFormFilterInput(),
      'slug'         => new sfWidgetFormFilterInput(),
      'eventos_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'KsWCEvent')),
    ));

    $this->setValidators(array(
      'cliente_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Clientes'), 'column' => 'id')),
      'contacto_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Contactos'), 'column' => 'id')),
      'user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'manager_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Manager'), 'column' => 'id')),
      'evento'       => new sfValidatorPass(array('required' => false)),
      'descripcion'  => new sfValidatorPass(array('required' => false)),
      'pdf'          => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comisionista' => new sfValidatorPass(array('required' => false)),
      'subtotal'     => new sfValidatorPass(array('required' => false)),
      'iva'          => new sfValidatorPass(array('required' => false)),
      'is_pay'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_active'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'version'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'         => new sfValidatorPass(array('required' => false)),
      'eventos_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'KsWCEvent', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cotizaciones_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addEventosListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.CotizacionesEventos CotizacionesEventos')
      ->andWhereIn('CotizacionesEventos.cotizacion_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Cotizaciones';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'cliente_id'   => 'ForeignKey',
      'contacto_id'  => 'ForeignKey',
      'user_id'      => 'ForeignKey',
      'manager_id'   => 'ForeignKey',
      'evento'       => 'Text',
      'descripcion'  => 'Text',
      'pdf'          => 'Text',
      'status'       => 'Number',
      'comisionista' => 'Text',
      'subtotal'     => 'Text',
      'iva'          => 'Text',
      'is_pay'       => 'Boolean',
      'is_active'    => 'Boolean',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
      'version'      => 'Number',
      'slug'         => 'Text',
      'eventos_list' => 'ManyKey',
    );
  }
}
