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
      $this->widgetSchema['empresa_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'Empresas',
                  'table_method' => 'getEmpresas',  
                  'add_empty' => true
                  ));
      $this->widgetSchema['subtotal'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['iva'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();

      $this->widgetSchema['monto_pagado_cliente'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['monto_pagado_talento'] = new sfWidgetFormInputHidden();
      
      $this->widgetSchema['is_pay'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['actividad'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
      ));

      $this->widgetSchema['requerimientos'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
      ));      

      $this->widgetSchema['fecha_desde'] = new sfWidgetFormJQueryDateTime();
      $this->widgetSchema['fecha_hasta'] = new sfWidgetFormJQueryDateTime();
      
      $this->validatorSchema['fecha_desde'] = new sfValidatorDateTime();
      $this->validatorSchema['fecha_hasta']= new sfValidatorDateTime();
      
      $this->validatorSchema->setPostValidator(
              new sfValidatorSchemaCompare(
                      'fecha_hasta', 
                      sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 
                      'fecha_desde',
                      array(),
                      array('invalid' => 'La fecha final debe ser mayor o igual a la inicial'))
      );
      
      $choices=Doctrine_Core::getTable('Cotizaciones')->getTiposCotizacion();
      
      $this->widgetSchema['tipo_cotizacion']= new sfWidgetFormChoice(array(
        'expanded' => true,
        'multiple' => false,
        'choices'  => $choices,
      ));
      
      $this->validatorSchema['tipo_cotizacion'] = new sfValidatorChoice(array('choices' => array_keys($choices),'required' => false));
      
      /*if(!$this->isNew()){
          if($this->getObject()->getTipoCotizacion()==CotizacionesTable::$TIPO_COTIZACION_FORANEA){
              $this->widgetSchema->setLabel('actividad', 'Dias de trabajo');
              $this->widgetSchema->setLabel('plaza', 'Territorio');
          }else{
              $this->widgetSchema['medios'] = new sfWidgetFormInputHidden();
              $this->widgetSchema['vigencia'] = new sfWidgetFormInputHidden();
              
          }
      }*/
      
  }
}
