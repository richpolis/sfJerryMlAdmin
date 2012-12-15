<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormJQueryDateTime represents a date widget rendered by JQuery UI.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    richpolis
 * @subpackage widget
 * @author     Ricardo Alcantara Gomez <richpolis@gmail.com>
 * @version    SVN: $Id: sfWidgetFormJQueryDateTime.class.php  2012-09-23 11:11:00Z richpolis $
 */
class sfWidgetFormJQueryDateTime extends sfWidgetFormInput
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
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');
    $this->addOption('date_widget', new sfWidgetFormDate());

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
    $datetime = parent::render($name, $value, $attributes, $errors);
    
    $js = sprintf(<<<EOF
<script type="text/javascript">
  $('#%s').datetimepicker({
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        changeMonth: true,
	changeYear: true,
	addSliderAccess: true,
	sliderAccessArgs: { touchonly: false }
  });
</script>
EOF
    ,
      $this->generateId($name));
 
    return $datetime.$js;
  }
}
