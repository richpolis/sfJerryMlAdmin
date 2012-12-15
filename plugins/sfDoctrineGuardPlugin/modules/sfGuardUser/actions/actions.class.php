<?php

require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorHelper.class.php';

/**
 * sfGuardUser actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserActions extends autoSfGuardUserActions
{
    public function executeListCalendario(sfWebRequest $request)
    {
      $usuario = $this->getRoute()->getObject();
      $this->getUser()->setCalendarUsuario($usuario->getId(),$usuario->getNombreCompleto(),$usuario->getColor());
      if($request->hasParameter("goto")){
          $this->getUser()->setRegresarA($request->getParameter("goto"));
      }
      $this->redirect('eventos_usuarios/index');
    }
}
