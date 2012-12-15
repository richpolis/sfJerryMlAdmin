<?php

/**
 * PagosTalentos filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePagosTalentosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'talento_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'referencia'      => new sfWidgetFormFilterInput(),
      'cuenta_deposito' => new sfWidgetFormFilterInput(),
      'importe'         => new sfWidgetFormFilterInput(),
      'iva'             => new sfWidgetFormFilterInput(),
      'isr'             => new sfWidgetFormFilterInput(),
      'adeudo'          => new sfWidgetFormFilterInput(),
      'is_cerrado'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'talento_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Talentos'), 'column' => 'id')),
      'referencia'      => new sfValidatorPass(array('required' => false)),
      'cuenta_deposito' => new sfValidatorPass(array('required' => false)),
      'importe'         => new sfValidatorPass(array('required' => false)),
      'iva'             => new sfValidatorPass(array('required' => false)),
      'isr'             => new sfValidatorPass(array('required' => false)),
      'adeudo'          => new sfValidatorPass(array('required' => false)),
      'is_cerrado'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('pagos_talentos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PagosTalentos';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'talento_id'      => 'ForeignKey',
      'referencia'      => 'Text',
      'cuenta_deposito' => 'Text',
      'importe'         => 'Text',
      'iva'             => 'Text',
      'isr'             => 'Text',
      'adeudo'          => 'Text',
      'is_cerrado'      => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
