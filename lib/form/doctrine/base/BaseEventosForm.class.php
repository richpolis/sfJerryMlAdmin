<?php

/**
 * Eventos form base class.
 *
 * @method Eventos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'talento_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => false)),
      'cotizacion_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => false)),
      'inicia_evento'   => new sfWidgetFormInputText(),
      'termina_evento'  => new sfWidgetFormInputText(),
      'es_dia_completo' => new sfWidgetFormInputCheckbox(),
      'repetir_evento'  => new sfWidgetFormInputCheckbox(),
      'tipo_repetir'    => new sfWidgetFormInputText(),
      'repetir_inicia'  => new sfWidgetFormDate(),
      'repetir_termina' => new sfWidgetFormDate(),
      'status'          => new sfWidgetFormInputText(),
      'is_active'       => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'talento_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'))),
      'cotizacion_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'))),
      'inicia_evento'   => new sfValidatorPass(),
      'termina_evento'  => new sfValidatorPass(array('required' => false)),
      'es_dia_completo' => new sfValidatorBoolean(array('required' => false)),
      'repetir_evento'  => new sfValidatorBoolean(array('required' => false)),
      'tipo_repetir'    => new sfValidatorInteger(array('required' => false)),
      'repetir_inicia'  => new sfValidatorDate(array('required' => false)),
      'repetir_termina' => new sfValidatorDate(array('required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'is_active'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('eventos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Eventos';
  }

}
