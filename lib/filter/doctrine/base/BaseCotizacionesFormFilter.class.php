<?php

/**
 * Cotizaciones filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cliente_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'contacto_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'add_empty' => true)),
      'user_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'manager_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'add_empty' => true)),
      'empresa_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Empresas'), 'add_empty' => true)),
      'descripcion'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'actividad'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'plaza'                => new sfWidgetFormFilterInput(),
      'fecha_desde'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'fecha_hasta'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'mostrar_horas'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'vigencia'             => new sfWidgetFormFilterInput(),
      'medios'               => new sfWidgetFormFilterInput(),
      'requerimientos'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pdf'                  => new sfWidgetFormFilterInput(),
      'status'               => new sfWidgetFormFilterInput(),
      'tipo_cotizacion'      => new sfWidgetFormFilterInput(),
      'add_conceptos'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'add_comisionistas'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'add_eventos'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'subtotal'             => new sfWidgetFormFilterInput(),
      'iva'                  => new sfWidgetFormFilterInput(),
      'is_pay'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_active'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'monto_pagado_cliente' => new sfWidgetFormFilterInput(),
      'monto_pagado_talento' => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'                 => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'cliente_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Clientes'), 'column' => 'id')),
      'contacto_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Contactos'), 'column' => 'id')),
      'user_id'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'manager_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Manager'), 'column' => 'id')),
      'empresa_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Empresas'), 'column' => 'id')),
      'descripcion'          => new sfValidatorPass(array('required' => false)),
      'actividad'            => new sfValidatorPass(array('required' => false)),
      'plaza'                => new sfValidatorPass(array('required' => false)),
      'fecha_desde'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'fecha_hasta'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'mostrar_horas'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'vigencia'             => new sfValidatorPass(array('required' => false)),
      'medios'               => new sfValidatorPass(array('required' => false)),
      'requerimientos'       => new sfValidatorPass(array('required' => false)),
      'pdf'                  => new sfValidatorPass(array('required' => false)),
      'status'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tipo_cotizacion'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'add_conceptos'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'add_comisionistas'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'add_eventos'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'subtotal'             => new sfValidatorPass(array('required' => false)),
      'iva'                  => new sfValidatorPass(array('required' => false)),
      'is_pay'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_active'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'monto_pagado_cliente' => new sfValidatorPass(array('required' => false)),
      'monto_pagado_talento' => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'                 => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cotizaciones_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cotizaciones';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'cliente_id'           => 'ForeignKey',
      'contacto_id'          => 'ForeignKey',
      'user_id'              => 'ForeignKey',
      'manager_id'           => 'ForeignKey',
      'empresa_id'           => 'ForeignKey',
      'descripcion'          => 'Text',
      'actividad'            => 'Text',
      'plaza'                => 'Text',
      'fecha_desde'          => 'Date',
      'fecha_hasta'          => 'Date',
      'mostrar_horas'        => 'Boolean',
      'vigencia'             => 'Text',
      'medios'               => 'Text',
      'requerimientos'       => 'Text',
      'pdf'                  => 'Text',
      'status'               => 'Number',
      'tipo_cotizacion'      => 'Number',
      'add_conceptos'        => 'Boolean',
      'add_comisionistas'    => 'Boolean',
      'add_eventos'          => 'Boolean',
      'subtotal'             => 'Text',
      'iva'                  => 'Text',
      'is_pay'               => 'Boolean',
      'is_active'            => 'Boolean',
      'monto_pagado_cliente' => 'Text',
      'monto_pagado_talento' => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'slug'                 => 'Text',
    );
  }
}
