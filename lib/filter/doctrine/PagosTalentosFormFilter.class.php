<?php

/**
 * PagosTalentos filter form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage filter
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PagosTalentosFormFilter extends BasePagosTalentosFormFilter
{
  public function configure()
  {
    
    $this->setWidget('pagos_by_talento', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('pagos_by_talento', new sfValidatorPass(array('required' => false)));
    
    $this->setWidget('pagos_by_cuenta_deposito', new sfWidgetFormFilterInput(array('with_empty' => false)));
 
    $this->setValidator('pagos_by_cuenta_deposito', new sfValidatorPass(array('required' => false)));
    
  }
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['pagos_by_talento'] = 'custom';
    $fields['pagos_by_cuenta_deposito'] = 'custom';
    
    return $fields;
  }
  public function addPagosByTalentoColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->leftJoin($query->getRootAlias().'.Talentos t')
            ->andWhere('(t.name LIKE ? OR t.slug LIKE ? OR t.id LIKE ?)', 
                    array("%$text%", "%$text%","%$text%"));
     
    return $query;
  }
  public function addPagosByCuentaDepositoColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text)
      $query->andWhere($query->getRootAlias().'.cuenta_deposito LIKE ?', "%$text%");
     
    return $query;
  }
}
