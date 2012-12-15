<?php

/**
 * DetallesPrecotizacion filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDetallesPrecotizacionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'precotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Precotizaciones'), 'add_empty' => true)),
      'talento_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'actividad'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'precio'           => new sfWidgetFormFilterInput(),
      'margen_jerry_ml'  => new sfWidgetFormFilterInput(),
      'is_active'        => new sfWidgetFormFilterInput(),
      'position'         => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'precotizacion_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Precotizaciones'), 'column' => 'id')),
      'talento_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Talentos'), 'column' => 'id')),
      'actividad'        => new sfValidatorPass(array('required' => false)),
      'precio'           => new sfValidatorPass(array('required' => false)),
      'margen_jerry_ml'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'is_active'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('detalles_precotizacion_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesPrecotizacion';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'precotizacion_id' => 'ForeignKey',
      'talento_id'       => 'ForeignKey',
      'actividad'        => 'Text',
      'precio'           => 'Text',
      'margen_jerry_ml'  => 'Number',
      'is_active'        => 'Number',
      'position'         => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
