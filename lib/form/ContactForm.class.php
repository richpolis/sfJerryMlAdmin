<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContactForm
 *
 * @author Richpolis
 */
class ContactForm extends sfForm
{
  protected static $subjects = array('email'=>'Correo', 'imagen'=>'Imagen','video'=>'Video');
  public function configure()
  {
    $this->setWidgets(array(
      'name'    => new sfWidgetFormInput(array(),array()),
      'email'   => new sfWidgetFormInput(array(),array()),
      'subject'   => new sfWidgetFormInput(array(),array()),
      'telefon'   => new sfWidgetFormInput(array(),array()),  
      'message' => new sfWidgetFormTextarea(array(),array())
    ));
    $this->widgetSchema->setLabels(array(
      'name'    => 'Nombre',
      'email'   => 'Email',
      'subject'   => 'Asunto',
      'telefon'   => 'Telefono',
      'message' => 'Mensaje'
    ));
    $this->setValidators(array(
      'name'    => new sfValidatorString(array('required' => true,'min_length'=>4),array(
          'invalid' => 'Escribe tu nombre.',
          'required'   => 'Debes de escribir tu nombre',
          'min_length' => 'El texto "%value%" es muy corto. Debe de ser minimo de %min_length% caracteres.')),
      'email'   => new sfValidatorEmail(array(), array(
          'required'   => 'Debes de escribir tu correo electronico',
          'invalid' => 'El email es incorrecto.')),
      'message' => new sfValidatorString(array('min_length' => 4), array('required' => 'El cuerpo de mensaje debe de tener contenido.')),
      'subject' => new sfValidatorString(array('required'=>false), array()),
      'telefon' => new sfValidatorString(array('required'=>false), array()),
    ));
    $this->widgetSchema->setNameFormat('contact[%s]');
    
    
  }
  
}
