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
      'id'                   => new sfWidgetFormInputHidden(),
      'cliente_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'contacto_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'add_empty' => true)),
      'user_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'manager_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'add_empty' => true)),
      'empresa_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Empresas'), 'add_empty' => true)),
      'descripcion'          => new sfWidgetFormInputText(),
      'actividad'            => new sfWidgetFormInputText(),
      'plaza'                => new sfWidgetFormInputText(),
      'fecha_desde'          => new sfWidgetFormInputText(),
      'fecha_hasta'          => new sfWidgetFormInputText(),
      'mostrar_horas'        => new sfWidgetFormInputCheckbox(),
      'vigencia'             => new sfWidgetFormInputText(),
      'medios'               => new sfWidgetFormInputText(),
      'requerimientos'       => new sfWidgetFormInputText(),
      'pdf'                  => new sfWidgetFormInputText(),
      'status'               => new sfWidgetFormInputText(),
      'tipo_cotizacion'      => new sfWidgetFormInputText(),
      'add_conceptos'        => new sfWidgetFormInputCheckbox(),
      'add_comisionistas'    => new sfWidgetFormInputCheckbox(),
      'add_eventos'          => new sfWidgetFormInputCheckbox(),
      'subtotal'             => new sfWidgetFormInputText(),
      'iva'                  => new sfWidgetFormInputText(),
      'is_pay'               => new sfWidgetFormInputCheckbox(),
      'is_active'            => new sfWidgetFormInputCheckbox(),
      'monto_pagado_cliente' => new sfWidgetFormInputText(),
      'monto_pagado_talento' => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'slug'                 => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cliente_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'required' => false)),
      'contacto_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'required' => false)),
      'user_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'manager_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'required' => false)),
      'empresa_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Empresas'), 'required' => false)),
      'descripcion'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'actividad'            => new sfValidatorPass(),
      'plaza'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fecha_desde'          => new sfValidatorPass(),
      'fecha_hasta'          => new sfValidatorPass(),
      'mostrar_horas'        => new sfValidatorBoolean(array('required' => false)),
      'vigencia'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'medios'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'requerimientos'       => new sfValidatorPass(),
      'pdf'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'               => new sfValidatorInteger(array('required' => false)),
      'tipo_cotizacion'      => new sfValidatorInteger(array('required' => false)),
      'add_conceptos'        => new sfValidatorBoolean(array('required' => false)),
      'add_comisionistas'    => new sfValidatorBoolean(array('required' => false)),
      'add_eventos'          => new sfValidatorBoolean(array('required' => false)),
      'subtotal'             => new sfValidatorPass(array('required' => false)),
      'iva'                  => new sfValidatorPass(array('required' => false)),
      'is_pay'               => new sfValidatorBoolean(array('required' => false)),
      'is_active'            => new sfValidatorBoolean(array('required' => false)),
      'monto_pagado_cliente' => new sfValidatorPass(array('required' => false)),
      'monto_pagado_talento' => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'slug'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
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

}
