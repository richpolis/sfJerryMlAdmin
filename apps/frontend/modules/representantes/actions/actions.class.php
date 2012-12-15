<?php

require_once dirname(__FILE__).'/../lib/representantesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/representantesGeneratorHelper.class.php';

/**
 * representantes actions.
 *
 * @package    sfJerryMlAdmin
 * @subpackage representantes
 * @author     Richpolis Systems <richpolis@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class representantesActions extends autoRepresentantesActions
{
    public function executeSeleccionar(sfWebRequest $request)
    {
      $this->setPage(1);  
      if($request->hasParameter('talentos_slug')){
        $this->setFilters($this->configuration->getFilterDefaults());
        $this->filters=new TalentosFormFilter();
        $this->filters->setDefault('representante_by_talentos', $request->getParameter('talentoss_slug'));
        $this->setFilters($this->filters->getValues());
      }else{
        $this->setFilters($this->configuration->getFilterDefaults());
      }
      $this->pager = $this->getPager();
      $this->sort = $this->getSort();

      $this->setTemplate('index');
    }
    public function executeListSelect(sfWebRequest $request)
    {
      $representante = $this->getRoute()->getObject();
      $this->getUser()->setRepresentante($representante->getId());
      $ruta=$this->getUser()->getRegresarA();
      $this->redirect($ruta);
    }
    public function executeCancelarSeleccion(sfWebRequest $request)
    {
      if($this->getUser()->getModoCotizacion()){
          $this->getUser()->setModoCotizacion(false);
      }else{
          $this->getUser()->setSeleccionarRepresentante(false);
      }
      
      $ruta=$this->getUser()->getCancelarRegresarA();
      $this->redirect($ruta);
    }
}
