<?php

/**
 * KsWCEvent form base class.
 *
 * @method KsWCEvent getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKsWCEventForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'talento_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'detalles_cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'add_empty' => true)),
      'nivel'                  => new sfWidgetFormInputText(),
      'subject'                => new sfWidgetFormInputText(),
      'description'            => new sfWidgetFormTextarea(),
      'start_time'             => new sfWidgetFormDateTime(),
      'end_time'               => new sfWidgetFormDateTime(),
      'is_all_day_event'       => new sfWidgetFormInputCheckbox(),
      'color'                  => new sfWidgetFormInputText(),
      'recurring_rule'         => new sfWidgetFormInputText(),
      'lugar_evento'           => new sfWidgetFormInputText(),
      'calle'                  => new sfWidgetFormInputText(),
      'numero_exterior'        => new sfWidgetFormInputText(),
      'numero_interior'        => new sfWidgetFormInputText(),
      'colonia'                => new sfWidgetFormInputText(),
      'codigo_postal'          => new sfWidgetFormInputText(),
      'cuidad'                 => new sfWidgetFormInputText(),
      'municipio'              => new sfWidgetFormInputText(),
      'estado'                 => new sfWidgetFormInputText(),
      'pais'                   => new sfWidgetFormInputText(),
      'status'                 => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'talento_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'required' => false)),
      'detalles_cotizacion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'required' => false)),
      'nivel'                  => new sfValidatorInteger(array('required' => false)),
      'subject'                => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'start_time'             => new sfValidatorDateTime(array('required' => false)),
      'end_time'               => new sfValidatorDateTime(array('required' => false)),
      'is_all_day_event'       => new sfValidatorBoolean(array('required' => false)),
      'color'                  => new sfValidatorPass(array('required' => false)),
      'recurring_rule'         => new sfValidatorPass(array('required' => false)),
      'lugar_evento'           => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'calle'                  => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'numero_exterior'        => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'numero_interior'        => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'colonia'                => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'codigo_postal'          => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'cuidad'                 => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'municipio'              => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'estado'                 => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'pais'                   => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'status'                 => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ks_wc_event[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KsWCEvent';
  }

}
