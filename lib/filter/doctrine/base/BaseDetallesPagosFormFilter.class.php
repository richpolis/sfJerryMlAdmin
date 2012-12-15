<?php

/**
 * DetallesPagos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDetallesPagosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'fecha_pago'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'pagos_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Pagos'), 'add_empty' => true)),
      'cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'tipo_pago'     => new sfWidgetFormFilterInput(),
      'importe'       => new sfWidgetFormFilterInput(),
      'iva'           => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'fecha_pago'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'pagos_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Pagos'), 'column' => 'id')),
      'cotizacion_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Cotizaciones'), 'column' => 'id')),
      'tipo_pago'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'importe'       => new sfValidatorPass(array('required' => false)),
      'iva'           => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('detalles_pagos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesPagos';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'fecha_pago'    => 'Date',
      'user_id'       => 'ForeignKey',
      'pagos_id'      => 'ForeignKey',
      'cotizacion_id' => 'ForeignKey',
      'tipo_pago'     => 'Number',
      'importe'       => 'Text',
      'iva'           => 'Text',
      'status'        => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
