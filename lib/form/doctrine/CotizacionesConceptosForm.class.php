<?php

/**
 * CotizacionesConceptos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CotizacionesConceptosForm extends BaseCotizacionesConceptosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['cotizacion_id'] = new sfWidgetFormInputHidden();
      
      if($this->getObject()->getConceptoId()>0){
        $this->widgetSchema['concepto_id'] = new sfWidgetFormInputHidden();
      }elseif($this->getObject()->getCotizacionId()>0){
        $this->widgetSchema['concepto_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'Conceptos',
                  'query' => Doctrine_Core::getTable('Conceptos')
                            ->getCriteriaFiltrarPorCotizacion($this->getObject()->getCotizacionId()),  
                  'add_empty' => false
                  ));
      }
  }
}
