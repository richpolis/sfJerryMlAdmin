<?php

/**
 * Cotizaciones form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CotizacionesForm extends BaseCotizacionesForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['slug'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['cliente_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['contacto_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['subtotal'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['iva'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      
      $this->widgetSchema['is_pay'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['descripcion'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
      ));
      
      
      
  }
}
