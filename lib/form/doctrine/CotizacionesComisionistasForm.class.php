<?php

/**
 * CotizacionesComisionistas form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CotizacionesComisionistasForm extends BaseCotizacionesComisionistasForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['cotizacion_id'] = new sfWidgetFormInputHidden();
      
      if($this->getObject()->getComisionistaId()>0){
        $this->widgetSchema['comisionista_id'] = new sfWidgetFormInputHidden();
      }elseif($this->getObject()->getCotizacionId()>0){
        $this->widgetSchema['comisionista_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'Comisionistas',
                  'query' => Doctrine_Core::getTable('Comisionistas')
                            ->getCriteriaFiltrarPorCotizacion($this->getObject()->getCotizacionId()),  
                  'add_empty' => false
                  ));
      }
  }
}
