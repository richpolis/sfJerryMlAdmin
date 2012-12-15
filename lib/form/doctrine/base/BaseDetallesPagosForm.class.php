<?php

/**
 * DetallesPagos form base class.
 *
 * @method DetallesPagos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDetallesPagosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'fecha_pago'    => new sfWidgetFormDate(),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'pagos_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Pagos'), 'add_empty' => true)),
      'cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'tipo_pago'     => new sfWidgetFormInputText(),
      'importe'       => new sfWidgetFormInputText(),
      'iva'           => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fecha_pago'    => new sfValidatorDate(array('required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'pagos_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Pagos'), 'required' => false)),
      'cotizacion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'required' => false)),
      'tipo_pago'     => new sfValidatorInteger(array('required' => false)),
      'importe'       => new sfValidatorPass(array('required' => false)),
      'iva'           => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('detalles_pagos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesPagos';
  }

}
