<?php

/**
 * Clientes filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClientesFormFilter extends BaseClientesFormFilter
{
  public function configure()
  {
    
    
    $this->setWidget('cliente_by_razon_social', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('cliente_by_razon_social', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('cliente_by_contactos', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('cliente_by_contactos', new sfValidatorPass(array('required' => false)));
    
  }
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['cliente_by_razon_social'] = 'custom';
    $fields['cliente_by_contactos'] = 'custom';
    
    return $fields;
  }
  public function addClienteByRazonSocialColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->addWhere('( '. $query->getRootAlias().'.razon_social LIKE ? OR '.$query->getRootAlias().'.rfc LIKE ? )'
     , array("%$text%","%$text%"));
     
    return $query;
  }
  public function addClienteByContactosColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Contactos o')->andWhere('(o.name LIKE ?
      OR o.apellidos LIKE ? OR o.slug LIKE ?)', array("%$text%", "%$text%", "%$text%"));
     
    return $query;
  }
}
