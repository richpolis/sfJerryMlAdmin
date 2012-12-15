<?php

/**
 * UsuariosEventos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UsuariosEventosForm extends PluginUsuariosEventosForm
{
  public function configure()
  {
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['start_time'] = new sfWidgetFormJQueryDateTime();
      $this->widgetSchema['end_time'] = new sfWidgetFormJQueryDateTime();
      
      $this->validatorSchema['start_time'] = new sfValidatorDateTime();
      $this->validatorSchema['end_time']= new sfValidatorDateTime();
  }
}
