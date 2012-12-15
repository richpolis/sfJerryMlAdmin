<?php

/**
 * Contratos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContratosForm extends BaseContratosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['cotizacion_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['file'] = new sfWidgetFormInputFileEditable(array(
            'label'     => 'Archivo',
            'file_src'  => '/uploads/contratos/'.$this->getObject()->getFile(),
            'is_image'  => false,
            'edit_mode' => !$this->isNew(),
            'template'  => '<div><br />%input%<br />%delete% %delete_label%</div>',
          ));
      $this->validatorSchema['file'] = new sfValidatorFile(array(
            'path' => sfConfig::get('sf_upload_dir')."/contratos/",
            'required' => false));
      $this->validatorSchema['file_delete'] = new sfValidatorBoolean();
  }
}
