<?php

/**
 * DetallesCotizacion form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetallesCotizacionForm extends BaseDetallesCotizacionForm
{
  public function configure()
  {
          unset($this['created_at'], $this['updated_at']);
          $this->widgetSchema['position'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['iva'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['cotizacion_id'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['talento_id'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['actividad'] = new sfWidgetFormInputHidden();
          $this->validatorSchema['precio']=new sfValidatorNumber();
          $this->widgetSchema['is_pay_talento'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['monto_pagado_talento'] = new sfWidgetFormInputHidden();
  }
}
