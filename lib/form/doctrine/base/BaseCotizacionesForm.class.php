<?php

/**
 * Cotizaciones form base class.
 *
 * @method Cotizaciones getObject() Returns the current form's model object
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'cliente_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'contacto_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'add_empty' => true)),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'manager_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'add_empty' => true)),
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
      'version'      => new sfWidgetFormInputText(),
      'slug'         => new sfWidgetFormInputText(),
      'eventos_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'KsWCEvent')),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cliente_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'required' => false)),
      'contacto_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'required' => false)),
      'user_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'manager_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'required' => false)),
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
      'version'      => new sfValidatorInteger(array('required' => false)),
      'slug'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'eventos_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'KsWCEvent', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Cotizaciones', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('cotizaciones[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cotizaciones';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['eventos_list']))
    {
      $this->setDefault('eventos_list', $this->object->Eventos->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveEventosList($con);

    parent::doSave($con);
  }

  public function saveEventosList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['eventos_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Eventos->getPrimaryKeys();
    $values = $this->getValue('eventos_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Eventos', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Eventos', array_values($link));
    }
  }

}
