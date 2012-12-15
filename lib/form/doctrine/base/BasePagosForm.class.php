<?php

/**
 * Pagos form base class.
 *
 * @method Pagos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePagosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'referencia' => new sfWidgetFormInputText(),
      'cliente_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'importe'    => new sfWidgetFormInputText(),
      'iva'        => new sfWidgetFormInputText(),
      'adeudo'     => new sfWidgetFormInputText(),
      'is_cerrado' => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'referencia' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'cliente_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'required' => false)),
      'importe'    => new sfValidatorPass(array('required' => false)),
      'iva'        => new sfValidatorPass(array('required' => false)),
      'adeudo'     => new sfValidatorPass(array('required' => false)),
      'is_cerrado' => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('pagos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Pagos';
  }

}
