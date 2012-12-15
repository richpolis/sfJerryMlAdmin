<?php

/**
 * Contactos form base class.
 *
 * @method Contactos getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseContactosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'apellidos'       => new sfWidgetFormInputText(),
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
      'is_active'       => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'slug'            => new sfWidgetFormInputText(),
      'clientes_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Clientes')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 100)),
      'apellidos'       => new sfValidatorString(array('max_length' => 150)),
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
      'email'           => new sfValidatorString(array('max_length' => 150)),
      'is_active'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'clientes_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Clientes', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Contactos', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('contactos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Contactos';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['clientes_list']))
    {
      $this->setDefault('clientes_list', $this->object->Clientes->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveClientesList($con);

    parent::doSave($con);
  }

  public function saveClientesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['clientes_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Clientes->getPrimaryKeys();
    $values = $this->getValue('clientes_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Clientes', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Clientes', array_values($link));
    }
  }

}
