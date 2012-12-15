<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormJQueryColorSelect represents un color con el plugin jquery.colorselect.js.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    richpolis
 * @subpackage widget
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfWidgetFormJQueryColorSelect.class.php  2012-11-26 11:11:00Z richpolis $
 */
class sfWidgetFormJQueryColorSelect extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:       The image path to represent the widget (false by default)
   *  * config:      A JavaScript array that configures the JQuery date widget
   *  * culture:     The user culture
   *  * date_widget: The date widget instance to use as a "base" class
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $w=new sfWidgetFormInputHidden();
    $inputColor =  $w->render($name, $value, $attributes, $errors); //parent::render($name, $value, $attributes, $errors);
    
    $inputFinal = sprintf(<<<EOF
<div style="width: 250px; height: 30px;">
<div id="%s_calendarcolor" >

</div>
</div>
%s
<script type="text/javascript">
$(document).ready(function(){
    var cv =$("#%s").val() ;
   if(cv=="")
   {
       cv="-1";
   }
   $("#%s_calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "%s" });
});
</script>
EOF
    ,
            $this->generateId($name),
            $inputColor,
            $this->generateId($name),
            $this->generateId($name),
            $this->generateId($name));
 
    return $inputFinal;
  }
  
  public function getStylesheets(){
    return array('/ksWdCalendarPlugin/css/colorselect.css'=> 'screen');
  }
  public function getJavaScripts(){
      return array(
          '/ksWdCalendarPlugin/js/wdCalendar/Common.js',
          '/ksWdCalendarPlugin/js/wdCalendar/jquery.colorselect.js',
          );
  }
  
}
