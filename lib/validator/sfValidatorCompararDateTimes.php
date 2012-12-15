<?php

class sfValidatorCompararDateTimes extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->setMessage('invalid', 'La fecha final debe ser igual o mayor a la fecha inicial.');  
    $this->addOption('field_fecha_inicial', "fecha inicial");
    $this->addOption('field_fecha_final', "fecha final");  
    $this->addRequiredOption('fecha_inicial', date("Y-m-d H:i:s"));
    $this->addRequiredOption('fecha_final', date("Y-m-d H:i:s"));
    $this->addOption('operador_comparar', ">=");
  }
 
  protected function doClean($value)
  {
    $fechaInicial=  strtotime($this->getOption('fecha_inicial'));
    $fechaFinal=  strtotime($this->getOption('fecha_final'));
    $resp=false;
    switch($this->getOption('operador_comparar')){
        case "==":
            if($fechaFinal==$fechaInicial){
                $resp=true;
            }else{
                $mensaje="Las fechas no son iguales";
            }
            break;
        case ">=":
            if($fechaFinal>=$fechaInicial){
                $resp=true;
            }else{
                $mensaje="La {$this->getOption('field_fecha_final')} no es mayor o igual a la {$this->getOption('field_fecha_final')}";
            }
            break;
            
    }
    if(!$resp){
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }else{
        return true;
    }
  }
 
  public function isEmpty($value)
  {
    return false;
  }
}