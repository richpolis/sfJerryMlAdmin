<?php

/**
 * DetallesPrecotizacion form base class.
 *
 * @method DetallesPrecotizacion getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDetallesPrecotizacionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'precotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Precotizaciones'), 'add_empty' => true)),
      'talento_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'actividad'        => new sfWidgetFormInputText(),
      'precio'           => new sfWidgetFormInputText(),
      'margen_jerry_ml'  => new sfWidgetFormInputText(),
      'is_active'        => new sfWidgetFormInputText(),
      'position'         => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'precotizacion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Precotizaciones'), 'required' => false)),
      'talento_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'required' => false)),
      'actividad'        => new sfValidatorPass(),
      'precio'           => new sfValidatorPass(array('required' => false)),
      'margen_jerry_ml'  => new sfValidatorNumber(array('required' => false)),
      'is_active'        => new sfValidatorInteger(array('required' => false)),
      'position'         => new sfValidatorInteger(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('detalles_precotizacion[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesPrecotizacion';
  }

}
