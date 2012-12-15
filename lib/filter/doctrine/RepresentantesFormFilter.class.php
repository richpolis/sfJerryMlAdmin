<?php

/**
 * Representantes filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RepresentantesFormFilter extends BaseRepresentantesFormFilter
{
  public function configure()
  {
    $this->setWidget('representante_by_talentos', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('representante_by_talentos', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('representante_by_name', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('representante_by_name', new sfValidatorPass(array('required' => false)));
  }
   public function getFields()
  {
    $fields = parent::getFields();
    $fields['representante_by_talentos'] = 'custom';
    $fields['representante_by_name'] = 'custom';
    
    return $fields;
  }
  public function addRepresentanteByTalentoColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Talentos e')
            ->andWhere('(e.name LIKE ? OR e.slug LIKE ? )', array("%$text%","%$text%"));
    return $query;
  }
  public function addRepresentanteByNameColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->andWhere('('.$query->getRootAlias().'.name LIKE ? 
          OR '.$query->getRootAlias().'.apellidos LIKE ? 
              OR '.$query->getRootAlias().'.slug LIKE ? )', 
              array("%$text%","%$text%","%$text%"));
     
    return $query;
  }
}
