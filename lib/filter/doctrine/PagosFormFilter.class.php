<?php

/**
 * Pagos filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PagosFormFilter extends BasePagosFormFilter
{
  public function configure()
  {
    
    $this->setWidget('pagos_by_cliente', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('pagos_by_cliente', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('pagos_by_referencia', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('pagos_by_referencia', new sfValidatorPass(array('required' => false)));
    
  }
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['pagos_by_cliente'] = 'custom';
    $fields['pagos_by_referencia'] = 'custom';
    
    return $fields;
  }
  public function addPagosByClienteColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Clientes c')
            ->andWhere('(c.razon_social LIKE ? OR c.rfc LIKE ? OR c.slug LIKE ?)', 
                    array("%$text%", "%$text%","%$text%"));
     
    return $query;
  }
  public function addPagosByReferenciaColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->andWhere($query->getRootAlias().'.referencia LIKE ?', "%$text%");
     
    return $query;
  }
}
