<?php

/**
 * Precotizaciones filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PrecotizacionesFormFilter extends BasePrecotizacionesFormFilter
{
  public function configure()
  {
    
    $this->setWidget('precotizaciones_by_cliente', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('precotizaciones_by_cliente', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('precotizaciones_by_contacto', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('precotizaciones_by_contacto', new sfValidatorPass(array('required' => false)));
    
  }
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['precotizaciones_by_cliente'] = 'custom';
    $fields['precotizaciones_by_contacto'] = 'custom';
    
    return $fields;
  }
  public function addPrecotizacionesByClienteColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Clientes n')
            ->andWhere('(n.razon_social LIKE ? OR n.rfc LIKE ? OR n.slug LIKE ?)', 
                    array("%$text%", "%$text%","%$text%"));
     
    return $query;
  }
  public function addPrecotizacionesByContactoColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Contactos o')
            ->andWhere('(o.name LIKE ? OR o.apellidos LIKE ? OR o.slug LIKE ?)', 
                    array("%$text%", "%$text%", "%$text%"));
     
    return $query;
  }
}
