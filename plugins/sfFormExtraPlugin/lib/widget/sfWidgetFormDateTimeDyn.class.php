<?php

/*
 * This file is part of the symfony package.
 * (c) Gijs Nelissen <gijs.nelissen@digitalbase.eu>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormDateTimeDyn represents a date widget rendered by JQuery UI.
 * It is based on dyndatetime : http://code.google.com/p/dyndatetime
 *
 * This widget does not need JQuery or JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 */
class sfWidgetFormDateTimeDyn extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:   The image path to represent the widget (false by default)
   *  * config:  A JavaScript array that configures the JQuery date widget
   *  * culture: The user culture
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');

    parent::configure($options, $attributes);

    if ('en' == $this->getOption('culture'))
    {
      $this->setOption('culture', 'en');
    }
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
  	$prefix = $this->generateId($name);
    

  	$html = parent::render($name, $value, $attributes, $errors);
    $html .= $this->renderTag('image', array('id' => $prefix.'_trigger', 'src' => '/images/calendar.gif'));

    $html .= "
   <script type=\"text/javascript\">
     jQuery(document).ready(function() {
	jQuery(\"#".$this->generateId($name)."\").dynDateTime({
		showsTime: true,
                timeFormat:   \"12\",
		ifFormat: \"%Y/%m/%d-%H:%M\",
		daFormat: \"%l;%M %p, %e %m,  %Y\",
		align: \"Br\",
                range:  [2011, 2100],
		electric: true,
		singleClick: false,
		button: \".next()\" //next sibling
	});
    });
;
  </script>
    ";
    
    
    return $html;
  }
  
  public function getStylesheets() {
  	return array(
  		sfConfig::get("app_dbformextraplugin_datetime_path_css") . "calendar-win2k-cold-1.css" => 'all',
  	);
  }
  
  public function getJavascripts() {
  	return array(
  	    sfConfig::get("app_dbformextraplugin_datetime_path_js") . "jquery.dynDateTime.js"  => sfConfig::get("app_dbformextraplugin_datetime_path_js") . "jquery.dynDateTime.js",
  	    sfConfig::get("app_dbformextraplugin_datetime_path_js") . "lang/calendar-es.js"  => sfConfig::get("app_dbformextraplugin_datetime_path_js") . "lang/calendar-es.js"
         );
  	
  	
  }


}
