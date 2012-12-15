<?php

/**
 * example actions.
 *
 * @package    laiguFeyaSoftCalendarPlugin
 * @subpackage example
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class calendarioActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
    $this->calendar = new laiguFeyaSoftCalendar();
    $this->calendar->setInitialLoadURL(url_for("calendario_usuarios_accion",array("accion"=>"initialLoad")));
    $this->calendar->setLoadEventURL(url_for("calendario_usuarios_accion",array("accion"=>"loadEvent")));
    $this->setLayout('simple_layout');
  }

  public function executeJsonInitial(sfWebRequest $request)
  {
    //este carga los calendarios  
    sfConfig::set('sf_web_debug', false);
    $r = new laiguFeyaSoftCalendarJson();
    return $this->renderText($r->renderJsonInitial());
  }


  public function executeJsonLoadEvent(sfWebRequest $request)
  {
    //este carga los eventos por calendario
      
    $r = new laiguFeyaSoftCalendarJson();

    $item = new laiguFeyaSoftCalendarItem();
    $item->setYmd("2012-".date("m")."-02");
    $item->setEymd("2012-".date("m")."-02");
    $item->setStartTime("00:00");
    $item->setEndTime("23:59");
    $item->setCalendarId("1");
    $item->setColor("blue");
    $item->setSubject("Test 1");
    $r->addCalItem($item);

    $item = new laiguFeyaSoftCalendarItem();
    $item->setYmd("2012-".date("m")."-06");
    $item->setEymd("2012-".date("m")."-16");
    $item->setStartTime("00:00");
    $item->setEndTime("23:59");
    $item->setCalendarId("1");
    $item->setColor("blue");
    $item->setSubject("Test 2");
    $r->addCalItem($item);

    sfConfig::set('sf_web_debug', false);
    return $this->renderText($r->renderJsonLoadEvent($request));
  }
  public function executeCalendar(sfWebRequest $request){
      
        $agent = new CalendarAgent();

        $action = $request->getParameter("accion");
        if("search" == $action){
            return $this->renderText($agent->search($_REQUEST));
        }else if("showAllCalendar" == $action){
            return $this->renderText($agent->showAllCalendar($_REQUEST));
        }else if("showOnlyCalendar" == $action){
            return $this->renderText($agent->showOnlyCalendar($_REQUEST));
        }else if("createUpdateCalendar" == $action){
            return $this->renderText($agent->createUpdateCalendar($_REQUEST));
        }else if("deleteEventsByCalendar" == $action){
            return $this->renderText($agent->deleteEventsByCalendar($_REQUEST));
        }else if("deleteCalendar" == $action){
            return $this->renderText($agent->deleteCalendar($_REQUEST));
        }else if("loadCalendar" == $action){
            return $this->renderText($agent->loadCalendar($_REQUEST));
        }else if("loadEvent" == $action){
            return $this->renderText($agent->loadEvent($_REQUEST));
        }else if("loadRepeatEvent" == $action){
            return $this->renderText($agent->loadRepeatEvent($_REQUEST));
        }else if("createEvent" == $action){
            return $this->renderText($agent->createEvent($_REQUEST));
        }else if("updateEvent" == $action){
            return $this->renderText($agent->updateEvent($_REQUEST));
        }else if("deleteEvent" == $action){
            return $this->renderText($agent->deleteEvent($_REQUEST));
        }else if("deleteRepeatEvent" == $action){
            return $this->renderText($agent->deleteRepeatEvent($_REQUEST));
        }else if("changeDay" == $action){
            return $this->renderText($agent->changeDay($_REQUEST));
        }else if("deleteDay" == $action){
            return $this->renderText($agent->deleteDay($_REQUEST));
        }else if("loadSetting" == $action){
            return $this->renderText($agent->loadSetting($_REQUEST));
        }else if("updateSetting" == $action){
            return $this->renderText($agent->updateSetting($_REQUEST));
        }else if("createUpdateRepeatEvent" == $action){
            return $this->renderText($agent->createUpdateRepeatEvent($_REQUEST));
        }else if("initialLoad" == $action){
            return $this->renderText($agent->initialLoad($_REQUEST));
        }else{
            return $this->renderText('No such action');
        }
  }
}
