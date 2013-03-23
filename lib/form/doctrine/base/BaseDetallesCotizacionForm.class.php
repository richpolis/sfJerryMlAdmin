<?php

/**
 * DetallesCotizacion form base class.
 *
 * @method DetallesCotizacion getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDetallesCotizacionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'cotizacion_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'talento_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'actividad'              => new sfWidgetFormInputText(),
      'ganancia_jerry_ml'      => new sfWidgetFormInputText(),
      'ganancia_talento'       => new sfWidgetFormInputText(),
      'ganancia_comisionistas' => new sfWidgetFormInputText(),
      'margen_jerry_ml'        => new sfWidgetFormInputText(),
      'margen_comisionistas'   => new sfWidgetFormInputText(),
      'precio'                 => new sfWidgetFormInputText(),
      'iva'                    => new sfWidgetFormInputText(),
      'is_pay_talento'         => new sfWidgetFormInputCheckbox(),
      'is_active'              => new sfWidgetFormInputCheckbox(),
      'monto_pagado_talento'   => new sfWidgetFormInputText(),
      'position'               => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cotizacion_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'required' => false)),
      'talento_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'required' => false)),
      'actividad'              => new sfValidatorPass(),
      'ganancia_jerry_ml'      => new sfValidatorPass(array('required' => false)),
      'ganancia_talento'       => new sfValidatorPass(array('required' => false)),
      'ganancia_comisionistas' => new sfValidatorPass(array('required' => false)),
      'margen_jerry_ml'        => new sfValidatorNumber(array('required' => false)),
      'margen_comisionistas'   => new sfValidatorNumber(array('required' => false)),
      'precio'                 => new sfValidatorPass(array('required' => false)),
      'iva'                    => new sfValidatorPass(array('required' => false)),
      'is_pay_talento'         => new sfValidatorBoolean(array('required' => false)),
      'is_active'              => new sfValidatorBoolean(array('required' => false)),
      'monto_pagado_talento'   => new sfValidatorPass(array('required' => false)),
      'position'               => new sfValidatorInteger(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('detalles_cotizacion[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesCotizacion';
  }

}
