<?php

/**
 * DetallesPrecotizacion form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetallesPrecotizacionForm extends BaseDetallesPrecotizacionForm
{
  public function configure()
  {
          unset($this['created_at'], $this['updated_at']);
          $this->widgetSchema['position'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['precotizacion_id'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['talento_id'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['actividad'] = new sfWidgetFormTextareaTinyMCE();
          $this->validatorSchema['precio']=new sfValidatorNumber();
          
  }
}
