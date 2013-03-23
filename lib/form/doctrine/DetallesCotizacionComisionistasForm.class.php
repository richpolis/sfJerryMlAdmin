<?php

/**
 * DetallesCotizacionComisionistas form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetallesCotizacionComisionistasForm extends BaseDetallesCotizacionComisionistasForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['detalles_cotizacion_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['ganancia']   = new sfWidgetFormInputHidden();
      $this->widgetSchema['nivel']      = new sfWidgetFormInputHidden();
      
      if($this->getObject()->getComisionistaId()>0){
        $this->widgetSchema['comisionista_id'] = new sfWidgetFormInputHidden();
      }elseif($this->getObject()->getDetallesCotizacionId()>0){
        $this->widgetSchema['comisionista_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'Comisionistas',
                  'query' => Doctrine_Core::getTable('Comisionistas')
                            ->getCriteriaFiltrarPorDetalleCotizacion($this->getObject()->getDetallesCotizacionId()),  
                  'add_empty' => false
                  ));
      }
      
  }
}
