<?php

/**
 * DetallesCotizacionConceptos form base class.
 *
 * @method DetallesCotizacionConceptos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDetallesCotizacionConceptosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'detalles_cotizacion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'add_empty' => true)),
      'concepto_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Conceptos'), 'add_empty' => true)),
      'precio'                 => new sfWidgetFormInputText(),
      'nivel'                  => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'detalles_cotizacion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DetallesCotizacion'), 'required' => false)),
      'concepto_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Conceptos'), 'required' => false)),
      'precio'                 => new sfValidatorPass(array('required' => false)),
      'nivel'                  => new sfValidatorInteger(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('detalles_cotizacion_conceptos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DetallesCotizacionConceptos';
  }

}
