<?php

/**
 * CotizacionesComisionistas form base class.
 *
 * @method CotizacionesComisionistas getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesComisionistasForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'cotizacion_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'comisionista_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Comisionistas'), 'add_empty' => true)),
      'margen'          => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cotizacion_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'required' => false)),
      'comisionista_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Comisionistas'), 'required' => false)),
      'margen'          => new sfValidatorNumber(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('cotizaciones_comisionistas[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CotizacionesComisionistas';
  }

}
