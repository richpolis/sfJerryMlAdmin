<?php

/**
 * Laigu Feya Soft Calendar Plugin
 *
 * @author: Jordi Llonch <jordi@laigu.net>
 *
 */
class laiguFeyaSoftCalendar
{
  protected $plugin_web_dir = "/laiguFeyaSoftCalendarPlugin/";
  protected $show_language_menu;
  protected $searchURL; // 'calendarEvent/search',
  protected $showAllCalendarURL; // 'calendarType/showAll',
  protected $showOnlyCalendarURL; // 'calendarType/showOnly',
  protected $createUpdateCalendarURL; // 'calendarType/createUpdate',
  protected $deleteEventsByCalendarURL; // 'calendarEvent/deleteByCalendar',
  protected $deleteCalendarURL; // 'calendarType/delete',
  protected $loadCalendarURL; // 'calendarType/list',
  protected $loadEventURL; // "{$this->plugin_web_dir}fakeData/listEvent.json";
  protected $loadRepeatEventURL; // 'calendarEvent/loadRepeatEvents',
  protected $createEventURL; // 'calendarEvent/createEditEvent',
  protected $updateEventURL; // 'calendarEvent/createEditEvent',
  protected $deleteEventURL; // 'calendarEvent/deleteEvent',
  protected $deleteRepeatEventURL; // 'calendarEvent/deleteRepeatEvent',
  protected $changeDayURL; // 'calendarEvent/updateDayEvents',
  protected $deleteDayURL; // 'calendarEvent/deleteDayEvents',
  protected $loadSettingURL; // 'calendarSetting/list',
  protected $updateSettingURL; // 'calendarSetting/update',
  protected $createUpdateRepeatEventURL; // 'calendarEvent/createUpdateRepeatEvent',
  protected $initialLoadURL; // '{$this->plugin_web_dir}fakeData/initLoad.json',
  protected $listUserURL; // 'calendar/queryUser'

  public function __construct()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');  
    $this->show_language_menu = "true";
    $this->loadEventURL = "{$this->plugin_web_dir}fakeData/listEvent.json"; // fake data
    $this->initialLoadURL = "{$this->plugin_web_dir}fakeData/initLoad.json"; // fake data
    $this->searchURL= url_for("calendario_usuarios_accion",array("accion"=>"search")); // 'calendarEvent/search',
    $this->showAllCalendarURL = url_for("calendario_usuarios_accion",array("accion"=>"showAllCalendar")); // 'calendarType/showAll',
    $this->showOnlyCalendarURL = url_for("calendario_usuarios_accion",array("accion"=>"showOnlyCalendar")); // 'calendarType/showOnly',
    $this->createUpdateCalendarURL= url_for("calendario_usuarios_accion",array("accion"=>"createUpdateCalendar")); // 'calendarType/createUpdate',
    $this->deleteEventsByCalendarURL= url_for("calendario_usuarios_accion",array("accion"=>"deleteEventsByCalendar")); // 'calendarEvent/deleteByCalendar',
    $this->deleteCalendarURL= url_for("calendario_usuarios_accion",array("accion"=>"deleteCalendar")); // 'calendarType/delete',
    $this->loadCalendarURL= url_for("calendario_usuarios_accion",array("accion"=>"loadCalendar")); // 'calendarType/list',
    //$this->loadEventURL= url_for("calendario_usuarios_accion",array("accion"=>"search")); // "{$this->plugin_web_dir}fakeData/listEvent.json";
    $this->loadRepeatEventURL= url_for("calendario_usuarios_accion",array("accion"=>"loadRepeatEvent")); // 'calendarEvent/loadRepeatEvents',
    $this->createEventURL= url_for("calendario_usuarios_accion",array("accion"=>"createEvent")); // 'calendarEvent/createEditEvent',
    $this->updateEventURL= url_for("calendario_usuarios_accion",array("accion"=>"updateEvent")); // 'calendarEvent/createEditEvent',
    $this->deleteEventURL= url_for("calendario_usuarios_accion",array("accion"=>"deleteEvent")); // 'calendarEvent/deleteEvent',
    $this->deleteRepeatEventURL= url_for("calendario_usuarios_accion",array("accion"=>"deleteRepeatEvent")); // 'calendarEvent/deleteRepeatEvent',
    $this->changeDayURL= url_for("calendario_usuarios_accion",array("accion"=>"changeDay")); // 'calendarEvent/updateDayEvents',
    $this->deleteDayURL= url_for("calendario_usuarios_accion",array("accion"=>"deleteDay")); // 'calendarEvent/deleteDayEvents',
    $this->loadSettingURL= url_for("calendario_usuarios_accion",array("accion"=>"loadSetting")); // 'calendarSetting/list',
    $this->updateSettingURL= url_for("calendario_usuarios_accion",array("accion"=>"updateSetting")); // 'calendarSetting/update',
    $this->createUpdateRepeatEventURL= url_for("calendario_usuarios_accion",array("accion"=>"createUpdateRepeatEvent")); // 'calendarEvent/createUpdateRepeatEvent',
    //$this->initialLoadURL= url_for("calendario_usuarios_accion",array("accion"=>"search")); // '{$this->plugin_web_dir}fakeData/initLoad.json',
    $this->listUserURL= url_for("calendario_usuarios_accion",array("accion"=>"listUser")); // 'calendar/queryUser'
  }

  /**
   * true to show the language submenu in myCalendar, or not
   *
   * @param boolean $value
   * @return boolean
   */
  public function setShowLanguageMenu($value)
  {
    $this->show_language_menu = $value;
    return $this->show_language_menu;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setSearchURL($value)
  {
    $this->searchURL = $value;
    return $this->searchURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setShowAllCalendarURL($value)
  {
    $this->showAllCalendarURL = $value;
    return $this->showAllCalendarURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setShowOnlyCalendarURL($value)
  {
    $this->showOnlyCalendarURL = $value;
    return $this->showOnlyCalendarURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setCreateUpdateCalendarURL($value)
  {
    $this->createUpdateCalendarURL = $value;
    return $this->createUpdateCalendarURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setDeleteEventsByCalendarURL($value)
  {
    $this->deleteEventsByCalendarURL = $value;
    return $this->deleteEventsByCalendarURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setDeleteCalendarURL($value)
  {
    $this->deleteCalendarURL = $value;
    return $this->deleteCalendarURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setLoadCalendarURL($value)
  {
    $this->loadCalendarURL = $value;
    return $this->loadCalendarURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setLoadEventURL($value)
  {
    $this->loadEventURL = $value;
    return $this->loadEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setLoadRepeatEventURL($value)
  {
    $this->loadRepeatEventURL = $value;
    return $this->loadRepeatEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setCreateEventURL($value)
  {
    $this->createEventURL = $value;
    return $this->createEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setUpdateEventURL($value)
  {
    $this->updateEventURL = $value;
    return $this->updateEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setDeleteEventURL($value)
  {
    $this->deleteEventURL = $value;
    return $this->deleteEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setDeleteRepeatEventURL($value)
  {
    $this->deleteRepeatEventURL = $value;
    return $this->deleteRepeatEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setChangeDayURL($value)
  {
    $this->changeDayURL = $value;
    return $this->changeDayURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setDeleteDayURL($value)
  {
    $this->deleteDayURL = $value;
    return $this->deleteDayURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setLoadSettingURL($value)
  {
    $this->loadSettingURL = $value;
    return $this->loadSettingURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setUpdateSettingURL($value)
  {
    $this->updateSettingURL = $value;
    return $this->updateSettingURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setCreateUpdateRepeatEventURL($value)
  {
    $this->createUpdateRepeatEventURL = $value;
    return $this->createUpdateRepeatEventURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setInitialLoadURL($value)
  {
    $this->initialLoadURL = $value;
    return $this->initialLoadURL;
  }

  /**
   *
   *
   * @param string $value url
   * @return string
   */
  public function setListUserURL($value)
  {
    $this->listUserURL = $value;
    return $this->listUserURL;
  }

  /**
   * 
   */
  public function render()
  {
    $extjs_url = sfConfig::get('app_laigu_feyasoft_calendar_plugin_web_dir_extjs');

    return <<<EOD
<link rel="stylesheet" type="text/css" href="$extjs_url/resources/css/ext-all.css" />
<script type="text/javascript" src="$extjs_url/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="$extjs_url/ext-all.js"></script>

<script type="text/javascript">
Ext.ns("Ext.ux.calendar");

Ext.ux.calendar.CONST = {
    /*
     * The version number of myCalendar
     */
    VERSION:'2.0.5',
    /*
     *true to show the language submenu in myCalendar, or not
     *
     */
    SHOW_LANGUAGE_MENU: {$this->show_language_menu},

    BLANK_IMAGE_URL:'$extjs_url/resources/images/default/s.gif',
    /*
     *define the main path of myCalendar
     */
    MAIN_PATH:'{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/',
    /*
     *define the multi-language path of myCalendar
     */
    CALENDAR_LANGUAGE_PATH:'{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/multi-language/',
    /*
     *define the multi-language path of EXT
     */
    EXT_LANGUAGE_PATH:'$extjs_url/src/locale/',
    /*
     * define the some url here for datasource
     */
    searchURL:'{$this->searchURL}',
    showAllCalendarURL:'{$this->showAllCalendarURL}',
    showOnlyCalendarURL:'{$this->showOnlyCalendarURL}',
    createUpdateCalendarURL:'{$this->createUpdateCalendarURL}',
    deleteEventsByCalendarURL:'{$this->deleteEventsByCalendarURL}',
    deleteCalendarURL:'{$this->deleteCalendarURL}',
    loadCalendarURL:'{$this->loadCalendarURL}',
    loadEventURL:'{$this->loadEventURL}',
    loadRepeatEventURL:'{$this->loadRepeatEventURL}',
    createEventURL:'{$this->createEventURL}',
    updateEventURL:'{$this->updateEventURL}',
    deleteEventURL:'{$this->deleteEventURL}',
    deleteRepeatEventURL:'{$this->deleteRepeatEventURL}',
    changeDayURL:'{$this->changeDayURL}',
    deleteDayURL:'{$this->deleteDayURL}',
    loadSettingURL:'{$this->loadSettingURL}',
    updateSettingURL:'{$this->updateSettingURL}',
    createUpdateRepeatEventURL:'{$this->createUpdateRepeatEventURL}',
    initialLoadURL:'{$this->initialLoadURL}',
    listUserURL:'{$this->listUserURL}'
};
</script>

    <script type="text/javascript" src="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/common/LanManager.js"></script>
    <script type="text/javascript" src="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/common/Mask.js"></script>
    <script type="text/javascript" src="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/common/RepeatType.js"></script>

    <script type="text/javascript" src="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/DataSource.js"></script>
    <script type="text/javascript" src="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/calendar_core.js"></script>
    <script type="text/javascript" src="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/Viewer.js"></script>

    <link rel="stylesheet" type="text/css" href="{$this->plugin_web_dir}js/feyaSoft/home/program/calendar/css/calendar_core.css" />
EOD;
  }
}
