<?php

/**
 * DetallesPagos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetallesPagosForm extends BaseDetallesPagosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['pagos_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['cotizacion_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['iva'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['fecha_pago'] = new sfWidgetFormInputHidden();
      
      $choices=Doctrine_Core::getTable('DetallesPagos')->getTypes();
      
      $this->widgetSchema['tipo_pago']= new sfWidgetFormChoice(array(
        'expanded' => true,
        'multiple' => false,
        'choices'  => $choices,
      ));
      
      $this->validatorSchema['tipo_pago'] = new sfValidatorChoice(array('choices' => array_keys($choices),'required' => false));
      
      /*$this->widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array(
            'culture' => 'en',
      ));*/
      
      
      
      $this->validatorSchema->setPostValidator(
        new sfValidatorCallback(array(
            'callback' => array($this, 'validar_detalles_pagos_callback'))
        ));
      
  }
  public function validar_detalles_pagos_callback($validator, $values)
  {
      $detalles_pagos = Doctrine_Core::getTable('DetallesPagos')->getDetallesPagosPorCotizacion($values['cotizacion_id']);
      $importe=0;
      foreach($detalles_pagos as $detalle){
          if($detalle->getId()==intval($values['id'])){
              continue;
          }else{
              $importe+=$detalle->getImporte();
          }
          
      }
      $importe+=$values['importe'];
      if(!$detalles_pagos==null){
          $cotizacion=Doctrine_Core::getTable('Cotizaciones')->find($values['cotizacion_id']);
      }
      $subtotal=$cotizacion->getSubtotal();
      if ($importe>$subtotal)
      {
        setlocale(LC_MONETARY, 'en_US');
        $sTotal=money_format('%i', $subtotal);
        $sImporte=money_format('%i', $importe);  
        throw new sfValidatorError($validator, "El importe: $sImporte es mayor al Total por pagar: $sTotal");
      }

      return $values;
  }
}
