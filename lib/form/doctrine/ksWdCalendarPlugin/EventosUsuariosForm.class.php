<?php

/**
 * UsuariosEventos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventosUsuariosForm extends PluginEventosUsuariosForm
{
  public function configure()
  {
      if($this->isNew()){
        $user= sfContext::getInstance()->getUser();
        if($user->getCalendarUsuarioId()>0){
            $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
            $this->setDefault("user_id", $user->getCalendarUsuarioId());
        }elseif($this->getObject()->getUserId()>0){
            $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
            $this->setDefault("user_id", $this->getObject()->getUserId());
        }
        
      }else{
        $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      }
      
      $this->widgetSchema['color'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['recurring_rule'] = new sfWidgetFormInputHidden();
      
      
      $this->widgetSchema['start_time'] = new sfWidgetFormJQueryDateTime();
      $this->widgetSchema['end_time'] = new sfWidgetFormJQueryDateTime();
      
      $this->validatorSchema['start_time'] = new sfValidatorDateTime();
      $this->validatorSchema['end_time']= new sfValidatorDateTime();
      
      $this->widgetSchema->setLabels(array(
              'subject'=>'Evento o Actividad',
              'description'=>'Descripcion',
              'start_time'=>'Inicia',
              'end_time'=>'Termina',
              'is_all_day_event'=>'Todo el dia',
              'recurring_rule'=>'Regla recurrencia'
      ));
  }
}
