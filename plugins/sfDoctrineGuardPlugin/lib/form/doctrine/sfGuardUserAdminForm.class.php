<?php

/**
 * sfGuardUserAdminForm for admin generators
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {
      if(!sfContext::getInstance()->getUser()->hasCredential("admin")){
         unset($this['groups_list'],$this['permissions_list']);
      }
      
      $this->widgetSchema['color']=new sfWidgetFormJQueryColorSelect();
      
      $this->widgetSchema['firma'] = new sfWidgetFormInputFileEditable(array(
            'label'     => 'Firma',
            'file_src'  => '/uploads/usuarios/'.$this->getObject()->getFirma(),
            'is_image'  => true,
            'edit_mode' => !$this->isNew(),
            'template'  => '<div>%file%<br />%input%<br />%delete% %delete_label%</div>',
          ));
        $this->validatorSchema['firma'] = new sfValidatorFile(array(
            'mime_types' => 'web_images',
            'path' => sfConfig::get('sf_upload_dir')."/usuarios/",
            'required' => false));
      
      /*$choices=Doctrine_Core::getTable('sfGuardUser')->getColores();
      
      $this->widgetSchema['color']= new sfWidgetFormChoice(array(
        'expanded' => false,
        'multiple' => false,
        'choices'  => $choices,
      ));
      
      $this->validatorSchema['color'] = new sfValidatorChoice(array('choices' => array_keys($choices),'required' => false));*/
  }
}
