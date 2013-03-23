<?php

/**
 * DetallesCotizacion filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDetallesCotizacionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cotizacion_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'talento_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'actividad'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ganancia_jerry_ml'      => new sfWidgetFormFilterInput(),
      'ganancia_talento'       => new sfWidgetFormFilterInput(),
      'ganancia_comisionistas' => new sfWidgetFormFilterInput(),
      'margen_jerry_ml'        => new sfWidgetFormFilterInput(),
      'margen_comisionistas'   => new sfWidgetFormFilterInput(),
      'precio'                 => new sfWidgetFormFilterInput(),
      'iva'                    => new sfWidgetFormFilterInput(),
      'is_pay_talento'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_active'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'monto_pagado_talento'   => new sfWidgetFormFilterInput(),
      'position'               => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'cotizacion_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Cotizaciones'), 'column' => 'id')),
      'talento_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Talentos'), 'column' => 'id')),
      'actividad'              => new sfValidatorPass(array('required' => false)),
      'ganancia_jerry_ml'      => new sfValidatorPass(array('required' => false)),
      'ganancia_talento'       => new sfValidatorPass(array('required' => false)),
      'ganancia_comisionistas' => new sfValidatorPass(array('required' => false)),
      'margen_jerry_ml'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'margen_comisionistas'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'precio'                 => new sfValidatorPass(array('required' => false)),
      'iva'                    => new sfValidatorPass(array('required' => false)),
      'is_pay_talento'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_active'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'monto_pagado_talento'   => new sfValidatorPass(array('required' => false)),
      'position'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('detalles_cotizacion_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesCotizacion';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'cotizacion_id'          => 'ForeignKey',
      'talento_id'             => 'ForeignKey',
      'actividad'              => 'Text',
      'ganancia_jerry_ml'      => 'Text',
      'ganancia_talento'       => 'Text',
      'ganancia_comisionistas' => 'Text',
      'margen_jerry_ml'        => 'Number',
      'margen_comisionistas'   => 'Number',
      'precio'                 => 'Text',
      'iva'                    => 'Text',
      'is_pay_talento'         => 'Boolean',
      'is_active'              => 'Boolean',
      'monto_pagado_talento'   => 'Text',
      'position'               => 'Number',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
