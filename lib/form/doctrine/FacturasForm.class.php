<?php

/**
 * Facturas form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FacturasForm extends BaseFacturasForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['file'] = new sfWidgetFormInputFileEditable(array(
            'label'     => 'Archivo',
            'file_src'  => '/uploads/facturas/'.$this->getObject()->getFile(),
            'is_image'  => false,
            'edit_mode' => !$this->isNew(),
            'template'  => '<div><br />%input%<br />%delete% %delete_label%</div>',
          ));
      $this->validatorSchema['file'] = new sfValidatorFile(array(
            'path' => sfConfig::get('sf_upload_dir')."/facturas/",
            'required' => false));
      $this->validatorSchema['file_delete'] = new sfValidatorBoolean();
      
      if($this->getObject()->getCotizacionId()>0){
        $this->widgetSchema['cotizacion_id'] = new sfWidgetFormInputHidden();
      }else{
        $this->widgetSchema['cotizacion_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'Cotizaciones',
                  'table_method' => 'getCotizacionesSinFacturaAsociada',  
                  'add_empty' => true
                  ));
      }
      
  }
}
