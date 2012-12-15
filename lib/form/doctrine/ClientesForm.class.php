<?php

/**
 * Clientes form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClientesForm extends BaseClientesForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'],$this['contactos_list']);
          $this->widgetSchema['slug'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['saldo'] = new sfWidgetFormInputHidden();
          
          /*$this->widgetSchema['domicilio_fiscal'] = new sfWidgetFormTextareaTinyMCE(array(
                'width' => 500,
                'height' => 250,
          ));*/
          
          
          $this->widgetSchema['razon_social']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal required'));
          $this->validatorSchema['razon_social']=new sfValidatorString(array('required'=>true,'min_length'=>3),array('invalid'=>'Se debe ingresar la razon social del cliente'));
          $this->widgetSchema['rfc']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['calle']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['numero_exterior']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['numero_interior']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['colonia']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['codigo_postal']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['cuidad']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['municipio']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['estado']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['pais']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          
          
          
  }
  public function configureSoloRazonSocial(){
    unset($this['created_at'], 
            $this['updated_at'],
            $this['contactos_list'],
            $this['rfc'],
            $this['calle'],
            $this['numero_exterior'],
            $this['numero_interior'],
            $this['colonia'],
            $this['codigo_postal'],
            $this['cuidad'],
            $this['municipio'],
            $this['estado'],
            $this['pais']
            );
          $this->widgetSchema['slug'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['razon_social']=new sfWidgetFormInputText(array(),array('style'=>'background-color: yellow;'));
          $this->validatorSchema['razon_social']=new sfValidatorString(array('required'=>true,'min_length'=>3),array('invalid'=>'Se debe ingresar la razon social del cliente'));  
          
          
  }
  
}
