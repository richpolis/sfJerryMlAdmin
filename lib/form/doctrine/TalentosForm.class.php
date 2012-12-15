<?php

/**
 * Talentos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TalentosForm extends BaseTalentosForm
{
  public function configure()
  {
        unset($this['created_at'], $this['updated_at']);
        $this->widgetSchema['slug'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['saldo'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['descripcion'] = new sfWidgetFormTextareaTinyMCE(array(
                    'width' => 500,
                    'height' => 250,
                ));
        $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => false), array('invalid' => 'La direccion de correo no es valida'));
        $this->widgetSchema['imagen'] = new sfWidgetFormInputFileEditable(array(
            'label'     => 'Imagen',
            'file_src'  => '/uploads/talentos/'.$this->getObject()->getImagen(),
            'is_image'  => true,
            'edit_mode' => !$this->isNew(),
            'template'  => '<div>%file%<br />%input%<br />%delete% %delete_label%</div>',
          ));
        $this->validatorSchema['imagen'] = new sfValidatorFile(array(
            'mime_types' => 'web_images',
            'path' => sfConfig::get('sf_upload_dir')."/talentos/",
            'required' => false));
        $this->validatorSchema['imagen_delete'] = new sfValidatorBoolean();
        
        $this->widgetSchema['name']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
        $this->widgetSchema['rfc']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
        $this->widgetSchema['email']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
        $this->widgetSchema['margen_jerry_ml']=new sfWidgetFormInputText(array(),array('class'=>'fields_normal'));
        
        
        
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
}
