<?php

/**
 * KsWCEvent filter form base class.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKsWCEventFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'talento_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Talentos'), 'add_empty' => true)),
      'subject'           => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'start_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_all_day_event'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'color'             => new sfWidgetFormFilterInput(),
      'recurring_rule'    => new sfWidgetFormFilterInput(),
      'lugar_evento'      => new sfWidgetFormFilterInput(),
      'calle'             => new sfWidgetFormFilterInput(),
      'numero_exterior'   => new sfWidgetFormFilterInput(),
      'numero_interior'   => new sfWidgetFormFilterInput(),
      'colonia'           => new sfWidgetFormFilterInput(),
      'codigo_postal'     => new sfWidgetFormFilterInput(),
      'cuidad'            => new sfWidgetFormFilterInput(),
      'municipio'         => new sfWidgetFormFilterInput(),
      'estado'            => new sfWidgetFormFilterInput(),
      'pais'              => new sfWidgetFormFilterInput(),
      'status'            => new sfWidgetFormFilterInput(),
      'cotizaciones_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Cotizaciones')),
    ));

    $this->setValidators(array(
      'talento_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Talentos'), 'column' => 'id')),
      'subject'           => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'start_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_all_day_event'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'color'             => new sfValidatorPass(array('required' => false)),
      'recurring_rule'    => new sfValidatorPass(array('required' => false)),
      'lugar_evento'      => new sfValidatorPass(array('required' => false)),
      'calle'             => new sfValidatorPass(array('required' => false)),
      'numero_exterior'   => new sfValidatorPass(array('required' => false)),
      'numero_interior'   => new sfValidatorPass(array('required' => false)),
      'colonia'           => new sfValidatorPass(array('required' => false)),
      'codigo_postal'     => new sfValidatorPass(array('required' => false)),
      'cuidad'            => new sfValidatorPass(array('required' => false)),
      'municipio'         => new sfValidatorPass(array('required' => false)),
      'estado'            => new sfValidatorPass(array('required' => false)),
      'pais'              => new sfValidatorPass(array('required' => false)),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cotizaciones_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Cotizaciones', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ks_wc_event_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addCotizacionesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.CotizacionesEventos CotizacionesEventos')
      ->andWhereIn('CotizacionesEventos.evento_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'KsWCEvent';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'talento_id'        => 'ForeignKey',
      'subject'           => 'Text',
      'description'       => 'Text',
      'start_time'        => 'Date',
      'end_time'          => 'Date',
      'is_all_day_event'  => 'Boolean',
      'color'             => 'Text',
      'recurring_rule'    => 'Text',
      'lugar_evento'      => 'Text',
      'calle'             => 'Text',
      'numero_exterior'   => 'Text',
      'numero_interior'   => 'Text',
      'colonia'           => 'Text',
      'codigo_postal'     => 'Text',
      'cuidad'            => 'Text',
      'municipio'         => 'Text',
      'estado'            => 'Text',
      'pais'              => 'Text',
      'status'            => 'Number',
      'cotizaciones_list' => 'ManyKey',
    );
  }
}
