<?php

/**
 * DetallesPagosTalentos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDetallesPagosTalentosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'fecha_pago'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'user_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'pagos_talentos_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PagosTalentos'), 'add_empty' => true)),
      'detalles_cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'add_empty' => true)),
      'metodo_recibo'          => new sfWidgetFormFilterInput(),
      'importe'                => new sfWidgetFormFilterInput(),
      'iva'                    => new sfWidgetFormFilterInput(),
      'isr'                    => new sfWidgetFormFilterInput(),
      'descuento'              => new sfWidgetFormFilterInput(),
      'status'                 => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'fecha_pago'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'user_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'pagos_talentos_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PagosTalentos'), 'column' => 'id')),
      'detalles_cotizacion_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DetallesCotizacion'), 'column' => 'id')),
      'metodo_recibo'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'importe'                => new sfValidatorPass(array('required' => false)),
      'iva'                    => new sfValidatorPass(array('required' => false)),
      'isr'                    => new sfValidatorPass(array('required' => false)),
      'descuento'              => new sfValidatorPass(array('required' => false)),
      'status'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('detalles_pagos_talentos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesPagosTalentos';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'fecha_pago'             => 'Date',
      'user_id'                => 'ForeignKey',
      'pagos_talentos_id'      => 'ForeignKey',
      'detalles_cotizacion_id' => 'ForeignKey',
      'metodo_recibo'          => 'Number',
      'importe'                => 'Text',
      'iva'                    => 'Text',
      'isr'                    => 'Text',
      'descuento'              => 'Text',
      'status'                 => 'Number',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
