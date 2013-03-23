<?php
/**
 * Description of EnviarCotizacionForm
 *
 * @author Richpolis Systmes <richpolis@gmail.com>
 */
class EnviarCotizacionForm extends sfForm
{
  protected static $subjects = array('email'=>'Correo', 'imagen'=>'Imagen','video'=>'Video');
  
  public function configure()
  {
    $this->setWidgets(array(
      'cliente'    => new sfWidgetFormInput(array(),array()),
      'contacto'   => new  sfWidgetFormInput(array(),array()),
      'email'      => new sfWidgetFormInput(array(),array()),
      'extras'      => new sfWidgetFormInput(array(),array()),
      'subject'    => new sfWidgetFormInput(array(),array()),
      'message'    => new sfWidgetFormTextareaTinyMCE(array('width' => 500,'height' => 400,)),
      'cotizacion_id'=> new sfWidgetFormInputHidden(array(),array()) ,  
    ));
    
    $this->widgetSchema['adicional_id'] = new sfWidgetFormDoctrineChoice(
                array(
                  'model' => 'sfGuardUser',
                  'table_method' => 'findAll',  
                  'add_empty' => true
    ));
    $this->widgetSchema->setLabels(array(
      'cliente'     => 'Cliente',
      'contacto'    => 'Contacto',
      'email'       => 'Email Contacto',
      'extras'       => 'CC',
      'subject'     => 'Asunto',
      'message'     => 'Mensaje',
      'cotizacion_id'=> 'Cotizacion Id',
      'adicional_id'=> 'Copiar a:'  
    ));
    
    $this->setValidators(array(
      'cliente'    => new sfValidatorString(array('required' => true,'min_length'=>4),array(
          'invalid' => 'Escribe la Nombre Empresa del Cliente.',
          'required'   => 'Debes escribir la Nombre Empresa del Cliente',
          'min_length' => 'El texto "%value%" es muy corto. Debe de ser minimo de %min_length% caracteres.')),
      'contacto'  => new sfValidatorString(array('required' => true,'min_length'=>4),array(
          'invalid' => 'Escribe Nombre del Contacto.',
          'required'   => 'Debes escribir el nombre del Contacto',
          'min_length' => 'El texto "%value%" es muy corto. Debe de ser minimo de %min_length% caracteres.')),
      'email'   => new sfValidatorEmail(array(), array(
          'required'   => 'Debes de escribir tu correo electronico',
          'invalid' => 'El email es incorrecto.')),
      'message' => new sfValidatorString(array('min_length' => 4), array('required' => 'El cuerpo de mensaje debe de tener contenido.')),
      'subject' => new sfValidatorString(array('required'=>false), array()),
      'extras' => new sfValidatorString(array('required'=>false), array()),
      'cotizacion_id'=> new sfValidatorInteger(array(), array()),  
      'adicional_id'=>new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'required' => false)),  
    ));
    $this->widgetSchema->setNameFormat('enviar_cotizacion[%s]');
    
    
  }
  
}
