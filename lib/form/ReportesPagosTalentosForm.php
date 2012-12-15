<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContactForm
 *
 * @author Richpolis
 */
class ReportesPagosTalentosForm extends sfForm
{
  public function configure()
  {
    $choices=Doctrine_Core::getTable('DetallesPagosTalentos')->getTypesStatus();  
      
    $this->setWidgets(array(
      'talento'    => new sfWidgetFormDoctrineChoice(array('model' => 'Talentos', 'add_empty' => true)),
      'desde'   => new sfWidgetFormJQueryDate(array('culture' => 'en',)),
      'hasta'   => new sfWidgetFormJQueryDate(array('culture' => 'en',)),  
      'status' => new sfWidgetFormChoice(array('expanded' => false,'multiple' => false,'choices'  => $choices,)),
    ));
    $this->widgetSchema->setLabels(array(
      'talento'    => 'Talento',
      'desde'   => 'Fecha desde',
      'hasta'   => 'Fecha hasta',
      'status' => 'Status pago'
    ));
    
    $this->setValidators(array(
      'talento'=> new sfValidatorDoctrineChoice(array('model' => 'Talentos', 'required' => false)),
      'desde'   => new sfValidatorDate(array('required' => true)),
      'hasta'   => new sfValidatorDate(array('required' => false)),
      'status' => new sfValidatorChoice(array('choices' => array_keys($choices),'required' => false)),
    ));
    
    $this->validatorSchema->setPostValidator(
    new sfValidatorSchemaCompare('desde', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'hasta',
        array(),
        array('invalid' => 'Hay una incongruencia en las fechas, favor de revisar')
    )
    );
    
    $this->widgetSchema->setNameFormat('reportes_pago[%s]');
    
    
  }
  
}
