<?php

/**
 * DetallesCotizacionConceptos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDetallesCotizacionConceptosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'detalles_cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'add_empty' => true)),
      'concepto_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Conceptos'), 'add_empty' => true)),
      'precio'                 => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'detalles_cotizacion_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DetallesCotizacion'), 'column' => 'id')),
      'concepto_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Conceptos'), 'column' => 'id')),
      'precio'                 => new sfValidatorPass(array('required' => false)),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('detalles_cotizacion_conceptos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesCotizacionConceptos';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'detalles_cotizacion_id' => 'ForeignKey',
      'concepto_id'            => 'ForeignKey',
      'precio'                 => 'Text',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
