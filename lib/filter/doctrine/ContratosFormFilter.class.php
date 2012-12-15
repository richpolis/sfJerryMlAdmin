<?php

/**
 * Contratos filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContratosFormFilter extends BaseContratosFormFilter
{
  public function configure()
  {
    
    $this->setWidget('contratos_by_cliente', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('contratos_by_cliente', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('contratos_by_cotizacion', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('contratos_by_cotizacion', new sfValidatorPass(array('required' => false)));
    
  }
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['contratos_by_cliente'] = 'custom';
    $fields['contratos_by_cotizacion'] = 'custom';
    
    return $fields;
  }
  public function addContratosByClienteColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Cotizaciones z')
            ->leftJoin('z.Clientes y')
            ->andWhere('(y.razon_social LIKE ? OR y.rfc LIKE ? OR y.slug LIKE ?)', 
                    array("%$text%", "%$text%","%$text%"));
     
    return $query;
  }
  public function addContratosByCotizacionesColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Cotizaciones z')
            ->andWhere('(z.evento LIKE ? OR z.descripcion LIKE ? )', 
                    array("%$text%", "%$text%"));
     
    return $query;
  }
}
