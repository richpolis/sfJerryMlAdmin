<?php

/**
 * Laigu Feya Soft Calendar Plugin
 *
 * @author: Jordi Llonch <jordi@laigu.net>
 *
 */
class laiguFeyaSoftCalendarJson
{
  protected $listItemCal = array();

  public function renderJsonInitial()
  {
    return '{
    "cs":[
      {
        "createByDblclick":false,
        "monthFormat":"m/d",
        "weekFormat":"m/d(D)",
        "class":"CalendarSettingUIModel",
        "hideInactiveRow":false,
        "dayFormat":"l Y M d",
        "fromtoFormat":"Y-m-d",
        "id":"1",
        "intervalSlot":"30",
        "readOnly":false,
        "hourFormat":"24",
        "singleDay":false,
        "startDay":"1",
        "activeEndTime":"19:00",
        "activeStartTime":"09:00",
        "language":"en",
        "initialView": 2, // month view
      }
    ],
    "owned":[
      {
        "shares":[],
        "id":"1",
        "hide":false,
        "color":"blue",
        "description":null,
        "permit":"all",
        "name":"Calendar 1",
        "class":"CalendarTypeUIModel",
        "isShared":false
      }
    ],
    "shared":[],
    "re":[]
  }';
  }

  public function renderJsonLoadEvent(sfWebRequest $request)
  {
    // data send to request by calendar
    $start = $request->getParameter("start");
    $limit = $request->getParameter("limit");
    $text =  $request->getParameter("text");

    // response
    $r = array("total" => count($this->listItemCal), "results" => $this->listItemCal);
    return json_encode($r);
  }

  public function addCalItem(laiguFeyaSoftCalendarItem $item)
  {
    $this->listItemCal[] = $item->toArray();
  }

}


class laiguFeyaSoftCalendarItem
{
  protected $id;
  protected $calendarId;
  protected $subject;
  protected $description = null;
  protected $color;
  protected $ymd;
  protected $startTime;
  protected $eymd;
  protected $endTime;
  protected $repeatType = "no";
  protected $alertFlag;
  protected $locked = false;
  protected $class = "CalendarEventUIModel";

  public function toArray()
  {
    $itemCal = array();
    $itemCal["id"]          = $this->id;
    $itemCal["calendarId"]  = $this->calendarId;
    $itemCal["subject"]     = $this->subject;
    $itemCal["description"] = $this->description;
    $itemCal["color"]       = $this->color;
    $itemCal["alertFlag"]   = $this->alertFlag;
    $itemCal["ymd"]         = $this->ymd;
    $itemCal["startTime"]   = $this->startTime;
    $itemCal["eymd"]        = $this->eymd;
    $itemCal["endTime"]     = $this->endTime;
    $itemCal["locked"]      = $this->locked;
    $itemCal["repeatType"]  = $this->repeatType;
    $itemCal["class"]       = $this->class;
    return $itemCal;
  }

  public function setId($value)
  {
    return $this->id = $value;
  }

  public function setCalendarId($value)
  {
    return $this->calendarId = $value;
  }

  public function setSubject($value)
  {
    return $this->subject = $value;
  }

  public function setDescription($value)
  {
    return $this->description = $value;
  }

  public function setColor($value)
  {
    return $this->color = $value;
  }

  public function setYmd($value)
  {
    return $this->ymd = $value;
  }

  public function setStartTime($value)
  {
    return $this->startTime = $value;
  }

  public function setEymd($value)
  {
    return $this->eymd = $value;
  }

  public function setEndTime($value)
  {
    return $this->endTime = $value;
  }

  public function setRepeatType($value)
  {
    return $this->repeatType = $value;
  }

  public function setAlertFlag($value)
  {
    return $this->alertFlag = $value;
  }

  public function setLocked($value)
  {
    return $this->locked = $value;
  }

  public function setClass($value)
  {
    return $this->class = $value;
  }

}