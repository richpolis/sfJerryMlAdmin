<?php

/**
 * PagosTalentos form base class.
 *
 * @method PagosTalentos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePagosTalentosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'talento_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'referencia'      => new sfWidgetFormInputText(),
      'cuenta_deposito' => new sfWidgetFormInputText(),
      'importe'         => new sfWidgetFormInputText(),
      'iva'             => new sfWidgetFormInputText(),
      'isr'             => new sfWidgetFormInputText(),
      'adeudo'          => new sfWidgetFormInputText(),
      'is_cerrado'      => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'talento_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'required' => false)),
      'referencia'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'cuenta_deposito' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'importe'         => new sfValidatorPass(array('required' => false)),
      'iva'             => new sfValidatorPass(array('required' => false)),
      'isr'             => new sfValidatorPass(array('required' => false)),
      'adeudo'          => new sfValidatorPass(array('required' => false)),
      'is_cerrado'      => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('pagos_talentos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PagosTalentos';
  }

}
