<?php

/**
 * Contactos filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContactosFormFilter extends BaseContactosFormFilter
{
  public function configure()
  {
    $this->setWidget('contacto_by_cliente', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('contacto_by_cliente', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('contacto_by_name', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('contacto_by_name', new sfValidatorPass(array('required' => false)));
  }
   public function getFields()
  {
    $fields = parent::getFields();
    $fields['contacto_by_cliente'] = 'custom';
    $fields['contacto_by_name'] = 'custom';
    
    return $fields;
  }
  public function addContactoByClienteColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Clientes e')
            ->andWhere('(e.razon_social LIKE ? OR e.rfc LIKE ? OR e.slug LIKE ? )', array("%$text%","%$text%","%$text%"));
    return $query;
  }
  public function addContactoByNameColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->andWhere('('.$query->getRootAlias().'.name LIKE ? OR '.$query->getRootAlias().'.apellidos LIKE ? OR '.$query->getRootAlias(). '.slug LIKE ? )', array("%$text%","%$text%","%$text%"));
     
    return $query;
  }
}
