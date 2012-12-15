<?php

/**
 * Clientes form base class.
 *
 * @method Clientes getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClientesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'razon_social'    => new sfWidgetFormInputText(),
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
      'saldo'           => new sfWidgetFormInputText(),
      'is_active'       => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'slug'            => new sfWidgetFormInputText(),
      'contactos_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Contactos')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'razon_social'    => new sfValidatorString(array('max_length' => 255)),
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
      'saldo'           => new sfValidatorPass(array('required' => false)),
      'is_active'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contactos_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Contactos', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Clientes', 'column' => array('razon_social'))),
        new sfValidatorDoctrineUnique(array('model' => 'Clientes', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('clientes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Clientes';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['contactos_list']))
    {
      $this->setDefault('contactos_list', $this->object->Contactos->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveContactosList($con);

    parent::doSave($con);
  }

  public function saveContactosList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['contactos_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Contactos->getPrimaryKeys();
    $values = $this->getValue('contactos_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Contactos', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Contactos', array_values($link));
    }
  }

}
