<?php

/**
 * Talentos form base class.
 *
 * @method Talentos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTalentosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'descripcion'     => new sfWidgetFormInputText(),
      'rfc'             => new sfWidgetFormInputText(),
      'calle'           => new sfWidgetFormInputText(),
      'numero_exterior' => new sfWidgetFormInputText(),
      'numero_interior' => new sfWidgetFormInputText(),
      'colonia'         => new sfWidgetFormInputText(),
      'codigo_postal'   => new sfWidgetFormInputText(),
      'cuidad'          => new sfWidgetFormInputText(),
      'municipio'       => new sfWidgetFormInputText(),
      'estado'          => new sfWidgetFormInputText(),
      'pais'            => new sfWidgetFormInputText(),
      'telefono'        => new sfWidgetFormInputText(),
      'celular'         => new sfWidgetFormInputText(),
      'email'           => new sfWidgetFormInputText(),
      'imagen'          => new sfWidgetFormInputText(),
      'margen_jerry_ml' => new sfWidgetFormInputText(),
      'saldo'           => new sfWidgetFormInputText(),
      'is_active'       => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'slug'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 255)),
      'descripcion'     => new sfValidatorPass(),
      'rfc'             => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'calle'           => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'numero_exterior' => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'numero_interior' => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'colonia'         => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'codigo_postal'   => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'cuidad'          => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'municipio'       => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'estado'          => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'pais'            => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'telefono'        => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'celular'         => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'email'           => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'imagen'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'margen_jerry_ml' => new sfValidatorNumber(array('required' => false)),
      'saldo'           => new sfValidatorPass(array('required' => false)),
      'is_active'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Talentos', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Talentos', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('talentos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Talentos';
  }

}
