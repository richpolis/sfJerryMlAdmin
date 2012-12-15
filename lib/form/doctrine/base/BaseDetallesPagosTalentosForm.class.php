<?php

/**
 * DetallesPagosTalentos form base class.
 *
 * @method DetallesPagosTalentos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDetallesPagosTalentosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'fecha_pago'             => new sfWidgetFormDate(),
      'user_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'pagos_talentos_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PagosTalentos'), 'add_empty' => true)),
      'detalles_cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'add_empty' => true)),
      'metodo_recibo'          => new sfWidgetFormInputText(),
      'importe'                => new sfWidgetFormInputText(),
      'iva'                    => new sfWidgetFormInputText(),
      'isr'                    => new sfWidgetFormInputText(),
      'descuento'              => new sfWidgetFormInputText(),
      'status'                 => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fecha_pago'             => new sfValidatorDate(array('required' => false)),
      'user_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'pagos_talentos_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PagosTalentos'), 'required' => false)),
      'detalles_cotizacion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'required' => false)),
      'metodo_recibo'          => new sfValidatorInteger(array('required' => false)),
      'importe'                => new sfValidatorPass(array('required' => false)),
      'iva'                    => new sfValidatorPass(array('required' => false)),
      'isr'                    => new sfValidatorPass(array('required' => false)),
      'descuento'              => new sfValidatorPass(array('required' => false)),
      'status'                 => new sfValidatorInteger(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('detalles_pagos_talentos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesPagosTalentos';
  }

}
