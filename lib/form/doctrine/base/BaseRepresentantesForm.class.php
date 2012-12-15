<?php

/**
 * Representantes form base class.
 *
 * @method Representantes getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRepresentantesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'apellidos'  => new sfWidgetFormInputText(),
      'direccion'  => new sfWidgetFormInputText(),
      'telefono'   => new sfWidgetFormInputText(),
      'celular'    => new sfWidgetFormInputText(),
      'email'      => new sfWidgetFormInputText(),
      'is_active'  => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'slug'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 100)),
      'apellidos'  => new sfValidatorString(array('max_length' => 150)),
      'direccion'  => new sfValidatorPass(array('required' => false)),
      'telefono'   => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'celular'    => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'email'      => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'is_active'  => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'slug'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Representantes', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('representantes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Representantes';
  }

}
