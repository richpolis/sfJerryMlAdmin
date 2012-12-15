<?php

/**
 * Proyectos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProyectosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cliente_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Clientes'), 'add_empty' => true)),
      'contacto_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contactos'), 'add_empty' => true)),
      'evento'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'descripcion'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'actividad_general' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lugar_evento'      => new sfWidgetFormFilterInput(),
      'inicia_evento'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'termina_evento'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_active'         => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'              => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'cliente_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Clientes'), 'column' => 'id')),
      'contacto_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Contactos'), 'column' => 'id')),
      'evento'            => new sfValidatorPass(array('required' => false)),
      'descripcion'       => new sfValidatorPass(array('required' => false)),
      'actividad_general' => new sfValidatorPass(array('required' => false)),
      'lugar_evento'      => new sfValidatorPass(array('required' => false)),
      'inicia_evento'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'termina_evento'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_active'         => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'              => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('proyectos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Proyectos';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'cliente_id'        => 'ForeignKey',
      'contacto_id'       => 'ForeignKey',
      'evento'            => 'Text',
      'descripcion'       => 'Text',
      'actividad_general' => 'Text',
      'lugar_evento'      => 'Text',
      'inicia_evento'     => 'Date',
      'termina_evento'    => 'Date',
      'is_active'         => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'slug'              => 'Text',
    );
  }
}
