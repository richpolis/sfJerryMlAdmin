<?php

/**
 * KsWCEvent form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KsWCEventForm extends PluginKsWCEventForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'],$this['cotizaciones_list']);
      
      if($this->isNew()){
        $user= sfContext::getInstance()->getUser();
        if($user->getCalendarTalentoId()!=0){
            $this->widgetSchema['talento_id'] = new sfWidgetFormInputHidden();
        }
      }else{
        $this->widgetSchema['talento_id'] = new sfWidgetFormInputHidden();
      }
      
      
      
      $this->widgetSchema['recurring_rule'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['color'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['status'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['start_time'] = new sfWidgetFormJQueryDateTime();
      $this->widgetSchema['end_time'] = new sfWidgetFormJQueryDateTime();
      
      $this->validatorSchema['start_time'] = new sfValidatorDateTime();
      $this->validatorSchema['end_time']= new sfValidatorDateTime();
      $this->widgetSchema->setLabels(array(
              'subject'=>'Evento',
              'description'=>'Descripcion',
              'start_time'=>'Inicia',
              'end_time'=>'Termina',
              'is_all_day_event'=>'Todo el dia',
              'recurring_rule'=>'Regla recurrencia'
      ));
          
  }
}
