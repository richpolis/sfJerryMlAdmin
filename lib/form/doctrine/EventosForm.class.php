<?php

/**
 * Eventos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventosForm extends BaseEventosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['detalles_cotizacion_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['status'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['tipo_repetir'] = new sfWidgetFormChoice(array(
        'choices'  => Doctrine_Core::getTable('Eventos')->getTypes(),
        'expanded' => false,
        'multiple' => false,  
      ));
      $this->validatorSchema['tipo_repetir'] = new sfValidatorChoice(array(
        'choices' => array_keys(Doctrine_Core::getTable('Eventos')->getTypes()),
      ));
      /*$this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => Doctrine_Core::getTable('Eventos')->getTypesStatus(),
        'expanded' => false,
        'multiple' => false,  
      ));
      $this->validatorSchema['status'] = new sfValidatorChoice(array(
        'choices' => array_keys(Doctrine_Core::getTable('Eventos')->getTypesStatus()),
      ));*/
      $this->widgetSchema['inicia_evento'] = new  sfWidgetFormJQueryDateTime();
      $this->widgetSchema['termina_evento'] = new  sfWidgetFormJQueryDateTime();
      
      $this->validatorSchema['inicia_evento'] = new sfValidatorDateTime();
      $this->validatorSchema['termina_evento']= new sfValidatorDateTime();
      
      $this->widgetSchema['repetir_inicia'] = new sfWidgetFormJQueryDate(array(
        'culture' => 'es',
      ));
      $this->widgetSchema['repetir_termina'] = new sfWidgetFormJQueryDate(array(
        'culture' => 'es',
      ));
      
  }
  
}
