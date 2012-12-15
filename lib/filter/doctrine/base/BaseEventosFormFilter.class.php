<?php

/**
 * Eventos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEventosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'talento_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'cotizacion_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'inicia_evento'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'termina_evento'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'es_dia_completo' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'repetir_evento'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'tipo_repetir'    => new sfWidgetFormFilterInput(),
      'repetir_inicia'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'repetir_termina' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status'          => new sfWidgetFormFilterInput(),
      'is_active'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'talento_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Talentos'), 'column' => 'id')),
      'cotizacion_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Cotizaciones'), 'column' => 'id')),
      'inicia_evento'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'termina_evento'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'es_dia_completo' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'repetir_evento'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'tipo_repetir'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'repetir_inicia'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'repetir_termina' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_active'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('eventos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Eventos';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'talento_id'      => 'ForeignKey',
      'cotizacion_id'   => 'ForeignKey',
      'inicia_evento'   => 'Date',
      'termina_evento'  => 'Date',
      'es_dia_completo' => 'Boolean',
      'repetir_evento'  => 'Boolean',
      'tipo_repetir'    => 'Number',
      'repetir_inicia'  => 'Date',
      'repetir_termina' => 'Date',
      'status'          => 'Number',
      'is_active'       => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
