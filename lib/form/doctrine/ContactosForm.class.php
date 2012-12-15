<?php

/**
 * Contactos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContactosForm extends BaseContactosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'],$this['clientes_list']);
          $this->widgetSchema['slug'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
          $this->widgetSchema['email']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal required'));
          $this->validatorSchema['email'] = new sfValidatorEmail(array('required'=>false),array('invalid'=>'La direccion de correo no es valida'));
          $this->widgetSchema['name']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal required'));
          $this->validatorSchema['name']=new sfValidatorString(array('required'=>true,'min_length'=>3),array('invalid'=>'Se debe ingresar la razon social del cliente'));  
          $this->widgetSchema['apellidos']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal required'));
          $this->validatorSchema['apellidos']=new sfValidatorString(array('required'=>true,'min_length'=>3),array('invalid'=>'Ingresar apellidos del contacto'));  
          
          $this->widgetSchema['calle']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['numero_exterior']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['numero_interior']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['colonia']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['codigo_postal']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['cuidad']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['municipio']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['estado']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['pais']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['telefono']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          $this->widgetSchema['celular']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
          
          
  }
  public function configureSoloDatosNecesarios(){
    unset($this['created_at'], 
            $this['updated_at'],
            $this['clientes_list'],
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
  }
}
