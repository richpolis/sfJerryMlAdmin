<?php

/**
 * DetallesPagosTalentos form.
 *
 * @package    sfJerryMlAdmin
 * @subpackage form
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetallesPagosTalentosForm extends BaseDetallesPagosTalentosForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['pagos_talentos_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['detalles_cotizacion_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array(
            'culture' => 'en',
      ));
      
      $choices=Doctrine_Core::getTable('DetallesPagosTalentos')->getTypes();
      
      $this->widgetSchema['metodo_recibo']= new sfWidgetFormChoice(array(
        'expanded' => true,
        'multiple' => false,
        'choices'  => $choices,
      ));
      
      $this->validatorSchema['metodo_recibo'] = new sfValidatorChoice(array('choices' => array_keys($choices),'required' => false));
      
      $this->validatorSchema->setPostValidator(
        new sfValidatorCallback(array(
            'callback' => array($this, 'validar_detalles_pagos_callback'))
        ));
      
  }
  public function validar_detalles_pagos_callback($validator, $values)
  {
      $detalles_cotizaciones = Doctrine_Core::getTable('DetallesCotizacion')->getDetallesCotizacionPorPagosTalentos($values['pagos_talentos_id'],false);
      $importe=0;
      $total=0;
      foreach($detalles_cotizaciones as $dc){
          if($dc->getId()==$values['detalles_cotizacion_id']){
            $total=$dc->getGananciaTalento();
            foreach($dc->getDetallesPagosTalentos() as $dpt){
                if($dpt->getId()==$values['id']){
                    continue;
                }else{
                    $importe+=$dpt->getImporte();
                }
            }
          }
      }
      $importe+=$values['importe'];
      
      if ($importe>$total)
      {
        setlocale(LC_MONETARY, 'en_US');
        $sTotal=money_format('%i', $total);
        $sImporte=money_format('%i', $importe);  
        throw new sfValidatorError($validator, "El importe: $sImporte es mayor al Total por pagar: $sTotal");
      }

      return $values;
  }
}