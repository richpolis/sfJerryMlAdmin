<?php

/**
 * Proyectos form base class.
 *
 * @method Proyectos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProyectosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'cliente_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'contacto_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'add_empty' => true)),
      'evento'            => new sfWidgetFormInputText(),
      'descripcion'       => new sfWidgetFormInputText(),
      'actividad_general' => new sfWidgetFormInputText(),
      'lugar_evento'      => new sfWidgetFormInputText(),
      'inicia_evento'     => new sfWidgetFormInputText(),
      'termina_evento'    => new sfWidgetFormInputText(),
      'is_active'         => new sfWidgetFormInputText(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'slug'              => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cliente_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'required' => false)),
      'contacto_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'required' => false)),
      'evento'            => new sfValidatorString(array('max_length' => 255)),
      'descripcion'       => new sfValidatorPass(),
      'actividad_general' => new sfValidatorPass(),
      'lugar_evento'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'inicia_evento'     => new sfValidatorPass(array('required' => false)),
      'termina_evento'    => new sfValidatorPass(array('required' => false)),
      'is_active'         => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
      'slug'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Proyectos', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('proyectos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Proyectos';
  }

}
