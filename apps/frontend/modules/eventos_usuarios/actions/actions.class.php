<?php

/**
 * eventos_usuarios actions.
 *
 * @package    ksWdCalendarPlugin
 * @subpackage eventos_usuarios
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventos_usuariosActions extends sfActions
{
  public function preExecute(){
    parent::preExecute() ;
//var_dump(sfConfig::getAll());
    $culture = "us" ;
    $this->arrayCulture = sfConfig::get("sf_ks_wd_calendar_plugin") ;
    $this->arrayCulture = $this->arrayCulture['culture_us'];
    
    //die(var_export($this->arrayCulture , true));
    
  }

  public function executeIndex(sfWebRequest $request)
  {
    if($this->getUser()->getCalendarUsuarioId()>0){
        $this->ks_wc_events = Doctrine_Core::getTable('EventosUsuarios')
          ->createQuery('a')
          ->Where('a.user_id=?',$this->getUser()->getCalendarUsuarioId())
          ->execute();
    }else{
        $this->ks_wc_events = Doctrine_Core::getTable('EventosUsuarios')
          ->createQuery('a')
          ->execute();
    }
    if($request->hasParameter('showdate')){
        $d = new DateTime($request->getParameter('showdate'));
        $this->showdate=$d->format("Y-m-d");
    }else{
        $this->showdate=date("Y-m-d");
    }    
  }
  
  public function executeCalendarioTodos(sfWebRequest $request){
      $this->getUser()->setCalendarUsuario(0,"",0);
      $this->redirect("eventos_usuarios/index");
      $this->getUser()->setRegresarA("homepage");
  }
  
  public function executeDashboard(sfWebRequest $request){
      $eventos=Doctrine_Core::getTable('EventosUsuarios')->getProximosEventosUsuarios();
      return $this->renderPartial('eventos_usuarios/dash_eventos',array("eventos"=>$eventos));
  }
  
  public function executeList(sfWebRequest $request){
    //die('{"events":[[73773,"go to dinner","11\/16\/2010 18:21","01\/01\/1970 03:18",1,1,0,6,1,"Moore",""],[24322,"project plan review","11\/15\/2010 22:40","01\/01\/1970 03:10",1,0,0,12,1,"Moore",""],[18984,"go to dinner","11\/18\/2010 11:57","01\/01\/1970 02:06",0,1,0,10,1,"Belion",""],[54235,"remote meeting","11\/20\/2010 02:58","01\/01\/1970 02:35",1,0,0,8,1,"Belion",""],[98751,"team meeting","11\/16\/2010 06:15","01\/01\/1970 02:58",1,0,0,10,1,"Newswer",""],[80951,"go to dinner","11\/17\/2010 04:19","01\/01\/1970 02:33",0,0,0,6,1,"Belion",""],[44390,"project plan review","11\/18\/2010 01:49","01\/01\/1970 02:35",0,0,0,12,1,"Lodan",""],[84519,"go to dinner","11\/18\/2010 21:16","01\/01\/1970 02:44",0,0,0,4,1,"Lodan",""],[98796,"remote meeting","11\/20\/2010 13:40","01\/01\/1970 03:10",1,0,0,3,1,"Moore",""],[12343,"team meeting","11\/15\/2010 19:38","01\/01\/1970 03:35",1,0,0,5,1,"Moore",""],[66461,"remote meeting","11\/16\/2010 06:59","01\/01\/1970 03:53",1,0,0,11,1,"Moore",""],[27891,"annual report","11\/17\/2010 02:15","01\/01\/1970 02:56",1,0,0,3,1,"Belion",""],[85772,"team meeting","11\/18\/2010 16:58","01\/01\/1970 02:41",1,0,0,5,1,"Newswer",""],[86508,"annual report","11\/19\/2010 16:00","01\/01\/1970 03:45",1,0,0,6,1,"Bytelin",""],[92041,"project plan review","11\/19\/2010 22:21","01\/01\/1970 02:57",0,0,0,9,1,"Moore",""],[66057,"remote meeting","11\/20\/2010 22:34","01\/01\/1970 02:31",0,1,0,1,1,"Moore",""],[73918,"annual report","11\/18\/2010 16:34","01\/01\/1970 03:29",0,0,0,-1,1,"Newswer",""],[71911,"team meeting","11\/21\/2010 10:53","01\/01\/1970 02:40",1,0,0,8,1,"Bytelin",""],[54304,"project plan review","11\/15\/2010 14:27","01\/01\/1970 02:25",0,0,0,1,1,"Belion",""],[87224,"remote meeting","11\/19\/2010 13:21","01\/01\/1970 02:12",0,0,0,7,1,"Bytelin",""]],"issort":true,"start":"11\/15\/2010 00:00","end":"11\/21\/2010 23:59","error":null}');

    $start_day = (string)$request->getParameter('showdate', '');
    $type      = (string)$request->getParameter('viewtype', 'month');

    // On initialise les valeurs de retour
    $this->events = array();
    $this->issort = true;
    $this->start  = null;
    $this->end    = null;
    $this->error  = null;

    try {
      //$php_time = ksWdCalendar::js2PhpTime($start_day, sfConfig::get('app_sf_calendar_pp_culture'));
      $php_time = ksWdCalendar::js2PhpTime($start_day, $this->arrayCulture['fulldaykey']);

      // On initialise les dates de début et de fin en fonction du type
      switch ($type) {
        case 'month':
          $start_time = mktime(0, 0, 0, date('m', $php_time), 1, date('Y', $php_time));

          $end_time   = mktime(0, 0, -1, date('m', $php_time)+1, 1, date('Y', $php_time));

          break;

        case 'week':
          //suppose first day of a week is monday
          $monday  =  date('d', $php_time) - date('N', $php_time) + 1;
          $start_time = mktime(0, 0, 0, date('m', $php_time), $monday, date('Y', $php_time));
          $end_time   = mktime(0, 0, -1, date('m', $php_time), $monday + 7, date('Y', $php_time));
          break;

        case 'day':
          $start_time = mktime(0, 0, 0, date('m', $php_time), date('d', $php_time), date('Y', $php_time));
          $end_time   = mktime(0, 0, -1, date('m', $php_time), date('d', $php_time) + 1, date('Y', $php_time));
          break;

        default:
          throw new sfException('Le type du calendrier demandé n\'est pas connu');
      }


      $this->start  = ksWdCalendar::php2JsTime($start_time , $this->arrayCulture['jsdate']);
      $this->end    = ksWdCalendar::php2JsTime($end_time , $this->arrayCulture['jsdate']);

      $start_time = date("Y-m-d H:i:s" , $start_time) ;
      $end_time = date("Y-m-d H:i:s" , $end_time) ;

      $q = Doctrine_Query::create()
        ->select('a.*')
        ->from('EventosUsuarios a')
        //->where("(start_time >= \"$start_time\" AND start_time <= \"$end_time\") OR (end_time <= \"$end_time\" AND end_time >= \"$start_time\")")
        ->where("(start_time BETWEEN ? AND ?) OR (end_time BETWEEN ? AND ?)" , Array($start_time , $end_time , $start_time , $end_time ));
       
      if($this->getUser()->getCalendarUsuarioId()>0){
          $q->addWhere('a.user_id=?',$this->getUser()->getCalendarUsuarioId());
      }



      foreach($q->execute() as $event){
        //die(ksWdCalendar::php2JsTime($event->getStartTime(null) , $this->arrayCulture['jsdate'])) ;
          $textoAdicional="";
        if($this->getUser()->getCalendarUsuarioId()==0){
           if($event->getUser()!=null){
               $textoAdicional=$event->getUser()."-";
           }   
        }  
          
        $this->events[] = array(
          $event->getId(),
          $textoAdicional.$event->getSubject(),
          ksWdCalendar::php2JsTime($event->getStartTime(null) , $this->arrayCulture['jsdate']),
          ksWdCalendar::php2JsTime($event->getEndTime(null) , $this->arrayCulture['jsdate']),
          $event->getIsAllDayEvent(),
          0, // événement sur plus d'un jour
          $event->getRecurringRule(),
          $event->getColor(),
          1, // éditable
          '',
        );
      }
      //var_dump($this->events);die("a");

    } catch (Exception $e) {
      $this->error = $e->getMessage();
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ks_wc_event = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ks_wc_event);
    sfConfig::set('sf_web_debug', false) ;
    $this->setTemplate('show');    
    $this->setLayout('layout_empty');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->setLayout(false) ;
    
    if($this->getUser()->getCalendarUsuarioId()>0){
        $usuario=$this->getUser()->getCalendarUsuarioId();
        $evento_usuario=new EventosUsuarios();
        $evento_usuario->setUserId($usuario);
        $this->form = new EventosUsuariosForm($evento_usuario);
    }else{
        $this->form = new EventosUsuariosForm();
    }
    
    sfConfig::set('sf_web_debug', false) ;
    $this->setTemplate('new');    
    $this->setLayout('layout_empty');
  }

  public function executeNewAjax(sfWebRequest $request)
  {
    $this->setLayout(false) ;
    if($request->hasParameter('user_id')){
        $eu=new EventosUsuarios();
        $eu->setUserId($request->getParameter('user_id'));
        $this->form = new EventosUsuariosForm($eu);
    }else{
        $this->form = new EventosUsuariosForm();
    }
    
    if($request->isXmlHttpRequest()){
        return $this->renderPartial('eventos_usuarios/form_ajax',array('form'=>$this->form));
    }
  }
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new EventosUsuariosForm();
    $this->processForm($request, $this->form);
    
    sfConfig::set('sf_web_debug', false) ;
    $this->setLayout(false);
    $this->setTemplate('add');
    
  }
  
  public function executeCreateAjax(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new EventosUsuariosForm();
    $objecto=$this->processFormAjax($request, $this->form);
    if($request->isXmlHttpRequest()){
        if(is_null($objecto)){
            return $this->renderPartial('eventos/form_ajax',array('form'=>$this->form));
        }else{
            return $this->renderText("ok");
        }   
    }
    
    
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->setLayout(false) ;
    if( !($ks_wc_event = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))))){
        $ks_wc_event = new EventosUsuarios() ;
        $ks_wc_event->setStartTime($request->getParameter('start')) ;
        $ks_wc_event->setEndTime($request->getParameter('end')) ;
        $ks_wc_event->setSubject($request->getParameter('title')) ;
        $ks_wc_event->setDescription($request->getParameter('description')) ;
        $ks_wc_event->setIsAllDayEvent($request->getParameter('isallday')) ;
        if($this->getUser()->getCalendarUsuarioId()>0){
            $ks_wc_event->setUserId($this->getUser()->getCalendarUsuarioId());
        }
    }
    //$this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $this->form = new EventosUsuariosForm($ks_wc_event);
    
    sfConfig::set('sf_web_debug', false) ;
    $this->setTemplate('edit');    
    $this->setLayout('layout_empty');
  }

  public function executeEditAjax(sfWebRequest $request)
  {
    if($request->hasParameter('id') && $request->getParameter('id')>0){  
        $this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
        $this->form = new EventosUsuariosForm($ks_wc_event);
    }else{
        $ks_wc_event = new EventosUsuarios() ;
        $ks_wc_event->setStartTime($request->getParameter('start')) ;
        $ks_wc_event->setEndTime($request->getParameter('end')) ;
        $ks_wc_event->setSubject($request->getParameter('title')) ;
        $ks_wc_event->setDescription($request->getParameter('description')) ;
        $ks_wc_event->setIsAllDayEvent($request->getParameter('isallday')) ;
        if($this->getUser()->getCalendarUsuarioId()>0){
            $ks_wc_event->setUserId($this->getUser()->getCalendarUsuarioId());
            $this->nombreUsuario=$this->getUser()->getCalendarUsuarioName();
        }
        $this->form = new EventosUsuariosForm($ks_wc_event);
    }
    if($request->isXmlHttpRequest()){
        return $this->renderPartial('eventos_usuarios/form_ajax',array('form'=>$this->form,"nombreUsuario"=>$this->nombreUsuario));
    }
    
  }
  
  public function executeUpdate(sfWebRequest $request){
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $this->form = new EventosUsuariosForm($ks_wc_event);

    $this->processForm($request, $this->form);
    
    sfConfig::set('sf_web_debug', false) ;
    $this->setLayout(false);
    $this->setTemplate('update');
    
  }

  public function executeUpdateAjax(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($eventosUsuarios = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $this->form = new EventosUsuariosForm($eventosUsuarios);

    $objecto=$this->processFormAjax($request, $this->form);
    
    if($request->isXmlHttpRequest()){
     if(is_null($objecto)){
         return $this->renderPartial('eventos_usuarios/form_ajax',array('form'=>$this->form));
     }else{
         return $this->renderText("ok");
     }   
    }

    $this->setTemplate('edit');
    
  }
  
  
  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();

    $this->forward404Unless($ks_wc_event = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $ks_wc_event->delete();
    
    
    $this->success = true ;
    $this->message = "Event deleted" ;

    sfConfig::set('sf_web_debug', false) ;
    $this->setLayout(false);
    $this->setTemplate('delete');
    
  }


  public function executeDeleteAjax(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($eventosUsuarios = Doctrine_Core::getTable('EventosUsuarios')->find(array($request->getParameter('id'))), sprintf('Object ks_wc_event does not exist (%s).', $request->getParameter('id')));
    $eventosUsuarios->delete();
    
    if($request->isXmlHttpRequest()){
        return $this->renderText("delete");
    }
    
    $this->redirect('eventos_usuarios/index');
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $isNew = $form->getObject()->isNew() ;
    $nameForm="ks_wc_event";
    
    $arrayParams = $request->getParameter($nameForm) ;
    if($this->getUser()->getCalendarUsuarioId()>0){
        $arrayParams['user_id']=$this->getUser()->getCalendarUsuarioId();
        $arrayParams['color']=$this->getUser()->getCalendarUsuarioColor();
    }
    if(!isset($arrayParams['subject'])){
      $arrayParams['subject'] = $form->getObject()->getSubject() ;
    }

    if(!isset($arrayParams['description'])){
      $arrayParams['description'] = $form->getObject()->getDescription() ;
    }

    $form->bind($arrayParams, $request->getFiles($form->getName()));
    
    
    if ($form->isValid())
    {
      //var_dump($form->getObject()->getSubject());var_dump($form->getObject()->getStartTime());var_dump($form->getObject()->getId());
      $ks_wc_event = $form->save();
      
      /*if($this->getUser()->getCalendarUsuarioId()>0){
        $this->getUser()->addTalentos($this->getUser()->getCalendarUsuarioId());  
        $this->getUser()->addEventos($ks_wc_event->getId(),$this->getUser()->getCalendarUsuarioId());
      }*/
      
      //var_dump($ks_wc_event->getSubject());var_dump($ks_wc_event->getStartTime());var_dump($ks_wc_event->getId());exit();

      //$this->redirect('eventos_usuarios/edit?id='.$ks_wc_event->getId());
      $this->success = true ;
      $this->message = (($isNew)?"Added Event":"Event updated") ;
      $this->data = $form->getObject()->getId();
      
      return $ks_wc_event;
      
    }else {
        
        $this->success = false ;
        $this->message = $form->renderGlobalErrors() ;
        $this->data = $form->getObject()->getId();
      return null; 
        
    }
  }
  protected function processFormAjax(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()){
      $ks_wc_event = $form->save();
      return $ks_wc_event;
      //$this->redirect('eventos/edit?id='.$ks_wc_event->getId());
    }else{
      return null;  
    }
  }
}
