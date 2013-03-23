<?php

require_once dirname(__FILE__).'/../lib/facturasGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/facturasGeneratorHelper.class.php';

/**
 * facturas actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage facturas
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class facturasActions extends autoFacturasActions
{
    public function executeInactivar(sfWebRequest $request){
        if($request->hasParameter('id')){
            $factura=  Doctrine_Core::getTable("Facturas")->findOneBy('id',$request->getParameter('id'));
            if(!$factura==null){
                $factura->setIsActive(false);
                $factura->save();
                if($request->isXmlHttpRequest()){
                    return $this->renderText("ok");
                }else{
                    $this->redirect("@facturas");
                }
            }
        }
    }
}
