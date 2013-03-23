<?php

/**
 * Precotizaciones form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PrecotizacionesForm extends BasePrecotizacionesForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['slug'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['cliente_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['contacto_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
       $this->widgetSchema['empresa_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'Empresas',
                  'table_method' => 'getEmpresas',  
                  'add_empty' => true
                  ));
      
      $this->widgetSchema['descripcion'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
      ));
      $this->widgetSchema['lugar_evento'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
      ));
      $this->widgetSchema['actividad_general'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
      ));
      
      $this->widgetSchema['inicia_evento'] = new sfWidgetFormJQueryDateTime();
      $this->widgetSchema['termina_evento'] = new sfWidgetFormJQueryDateTime();
      
      $this->validatorSchema['inicia_evento'] = new sfValidatorDateTime();
      $this->validatorSchema['termina_evento']= new sfValidatorDateTime();
      
      $this->validatorSchema->setPostValidator(
              new sfValidatorSchemaCompare(
                      'termina_evento', 
                      sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 
                      'inicia_evento',
                      array(),
                      array('invalid' => 'La fecha final debe ser mayor o igual a la inicial'))
      );
      
  }
}
