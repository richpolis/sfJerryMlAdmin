<?php

/**
 * EventosUsuarios filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEventosUsuariosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'cotizacion_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cotizaciones'), 'add_empty' => true)),
      'nivel'            => new sfWidgetFormFilterInput(),
      'subject'          => new sfWidgetFormFilterInput(),
      'description'      => new sfWidgetFormFilterInput(),
      'start_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_all_day_event' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'color'            => new sfWidgetFormFilterInput(),
      'recurring_rule'   => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'cotizacion_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Cotizaciones'), 'column' => 'id')),
      'nivel'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'subject'          => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorPass(array('required' => false)),
      'start_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_all_day_event' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'color'            => new sfValidatorPass(array('required' => false)),
      'recurring_rule'   => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('eventos_usuarios_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventosUsuarios';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'user_id'          => 'ForeignKey',
      'cotizacion_id'    => 'ForeignKey',
      'nivel'            => 'Number',
      'subject'          => 'Text',
      'description'      => 'Text',
      'start_time'       => 'Date',
      'end_time'         => 'Date',
      'is_all_day_event' => 'Boolean',
      'color'            => 'Text',
      'recurring_rule'   => 'Text',
      'status'           => 'Number',
    );
  }
}
