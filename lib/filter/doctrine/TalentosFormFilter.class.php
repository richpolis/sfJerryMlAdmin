<?php

/**
 * Talentos filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TalentosFormFilter extends BaseTalentosFormFilter
{
  public function configure()
  {
    $this->setWidget('talento_by_representante', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('talento_by_representante', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('talento_by_name', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('talento_by_name', new sfValidatorPass(array('required' => false)));
  }
   public function getFields()
  {
    $fields = parent::getFields();
    $fields['talento_by_representante'] = 'custom';
    $fields['talento_by_name'] = 'custom';
    
    return $fields;
  }
  public function addTalentoByRepresentanteColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Representantes e')
            ->andWhere('(e.name LIKE ? OR e.apellidos LIKE ? OR e.slug LIKE ? )', array("%$text%","%$text%","%$text%"));
    return $query;
  }
  public function addTalentoByNameColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->andWhere('('.$query->getRootAlias().'.name LIKE ? OR '.$query->getRootAlias().'.slug LIKE ? )', array("%$text%","%$text%"));
     
    return $query;
  }
}
