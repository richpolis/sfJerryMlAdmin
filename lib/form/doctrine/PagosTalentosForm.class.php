<?php

/**
 * PagosTalentos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PagosTalentosForm extends BasePagosTalentosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['talento_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['importe'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['iva'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['isr'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['adeudo'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['is_cerrado'] = new sfWidgetFormInputHidden();
      
  }
}
