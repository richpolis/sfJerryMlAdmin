<?php

/**
 * EventosUsuarios form base class.
 *
 * @method EventosUsuarios getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventosUsuariosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'user_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'subject'          => new sfWidgetFormInputText(),
      'description'      => new sfWidgetFormTextarea(),
      'start_time'       => new sfWidgetFormDateTime(),
      'end_time'         => new sfWidgetFormDateTime(),
      'is_all_day_event' => new sfWidgetFormInputCheckbox(),
      'color'            => new sfWidgetFormInputText(),
      'recurring_rule'   => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'subject'          => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'start_time'       => new sfValidatorDateTime(array('required' => false)),
      'end_time'         => new sfValidatorDateTime(array('required' => false)),
      'is_all_day_event' => new sfValidatorBoolean(array('required' => false)),
      'color'            => new sfValidatorPass(array('required' => false)),
      'recurring_rule'   => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('eventos_usuarios[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventosUsuarios';
  }

}
