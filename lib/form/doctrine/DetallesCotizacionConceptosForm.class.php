<?php

/**
 * DetallesCotizacionConceptos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetallesCotizacionConceptosForm extends BaseDetallesCotizacionConceptosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['detalles_cotizacion_id'] = new sfWidgetFormInputHidden();
  }
}
