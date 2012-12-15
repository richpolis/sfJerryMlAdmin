<?php

/**
 * CotizacionesVersion filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCotizacionesVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cliente_id'   => new sfWidgetFormFilterInput(),
      'contacto_id'  => new sfWidgetFormFilterInput(),
      'user_id'      => new sfWidgetFormFilterInput(),
      'manager_id'   => new sfWidgetFormFilterInput(),
      'evento'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'descripcion'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pdf'          => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'comisionista' => new sfWidgetFormFilterInput(),
      'subtotal'     => new sfWidgetFormFilterInput(),
      'iva'          => new sfWidgetFormFilterInput(),
      'is_pay'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_active'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'cliente_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'contacto_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'manager_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'evento'       => new sfValidatorPass(array('required' => false)),
      'descripcion'  => new sfValidatorPass(array('required' => false)),
      'pdf'          => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comisionista' => new sfValidatorPass(array('required' => false)),
      'subtotal'     => new sfValidatorPass(array('required' => false)),
      'iva'          => new sfValidatorPass(array('required' => false)),
      'is_pay'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_active'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('cotizaciones_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CotizacionesVersion';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'cliente_id'   => 'Number',
      'contacto_id'  => 'Number',
      'user_id'      => 'Number',
      'manager_id'   => 'Number',
      'evento'       => 'Text',
      'descripcion'  => 'Text',
      'pdf'          => 'Text',
      'status'       => 'Number',
      'comisionista' => 'Text',
      'subtotal'     => 'Text',
      'iva'          => 'Text',
      'is_pay'       => 'Boolean',
      'is_active'    => 'Boolean',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
      'version'      => 'Number',
    );
  }
}
