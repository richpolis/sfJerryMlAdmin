<?php

/**
 * PrecotizacionesVersion filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePrecotizacionesVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cliente_id'        => new sfWidgetFormFilterInput(),
      'contacto_id'       => new sfWidgetFormFilterInput(),
      'user_id'           => new sfWidgetFormFilterInput(),
      'evento'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'descripcion'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'actividad_general' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lugar_evento'      => new sfWidgetFormFilterInput(),
      'inicia_evento'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'termina_evento'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pdf'               => new sfWidgetFormFilterInput(),
      'status'            => new sfWidgetFormFilterInput(),
      'is_active'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'cliente_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'contacto_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'evento'            => new sfValidatorPass(array('required' => false)),
      'descripcion'       => new sfValidatorPass(array('required' => false)),
      'actividad_general' => new sfValidatorPass(array('required' => false)),
      'lugar_evento'      => new sfValidatorPass(array('required' => false)),
      'inicia_evento'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'termina_evento'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pdf'               => new sfValidatorPass(array('required' => false)),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_active'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('precotizaciones_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PrecotizacionesVersion';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'cliente_id'        => 'Number',
      'contacto_id'       => 'Number',
      'user_id'           => 'Number',
      'evento'            => 'Text',
      'descripcion'       => 'Text',
      'actividad_general' => 'Text',
      'lugar_evento'      => 'Text',
      'inicia_evento'     => 'Date',
      'termina_evento'    => 'Date',
      'pdf'               => 'Text',
      'status'            => 'Number',
      'is_active'         => 'Boolean',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'version'           => 'Number',
    );
  }
}
