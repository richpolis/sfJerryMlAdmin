<?php

/**
 * CotizacionesVersion form base class.
 *
 * @method CotizacionesVersion getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'cliente_id'   => new sfWidgetFormInputText(),
      'contacto_id'  => new sfWidgetFormInputText(),
      'user_id'      => new sfWidgetFormInputText(),
      'manager_id'   => new sfWidgetFormInputText(),
      'evento'       => new sfWidgetFormInputText(),
      'descripcion'  => new sfWidgetFormInputText(),
      'pdf'          => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'comisionista' => new sfWidgetFormInputText(),
      'subtotal'     => new sfWidgetFormInputText(),
      'iva'          => new sfWidgetFormInputText(),
      'is_pay'       => new sfWidgetFormInputCheckbox(),
      'is_active'    => new sfWidgetFormInputCheckbox(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'version'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cliente_id'   => new sfValidatorInteger(array('required' => false)),
      'contacto_id'  => new sfValidatorInteger(array('required' => false)),
      'user_id'      => new sfValidatorInteger(array('required' => false)),
      'manager_id'   => new sfValidatorInteger(array('required' => false)),
      'evento'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'descripcion'  => new sfValidatorPass(),
      'pdf'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
      'comisionista' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'subtotal'     => new sfValidatorPass(array('required' => false)),
      'iva'          => new sfValidatorPass(array('required' => false)),
      'is_pay'       => new sfValidatorBoolean(array('required' => false)),
      'is_active'    => new sfValidatorBoolean(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'version'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('version')), 'empty_value' => $this->getObject()->get('version'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cotizaciones_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CotizacionesVersion';
  }

}
