<?php

/**
 * detpagos components.
 *
 * @package    sfJerryMlAdmin
 * @subpackage detpagos
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class detpagosComponents extends sfComponents
{
  public function executeNew(sfWebRequest $request)
  {
    $dp=new DetallesPagos();
    $dp->setUserId($this->getUser()->getGuardUser()->getId());
    $dp->setCotizacionId($request->getParameter('cotizacion_id'));
    $dp->setPagosId($request->getParameter('pago_id'));
    $this->form=new DetallesPagosForm($dp);
    
  }
}  