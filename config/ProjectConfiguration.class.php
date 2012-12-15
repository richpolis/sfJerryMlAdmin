<?php

require_once dirname(__FILE__) . '/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
    static protected $zendLoaded = false;

    static public function registerZend() {
        if (self::$zendLoaded) {
            return;
        }

        set_include_path(sfConfig::get('sf_lib_dir') . '/vendor' . PATH_SEPARATOR . get_include_path());
        require_once sfConfig::get('sf_lib_dir') . '/vendor/Zend/Loader.php';
        //Zend_Loader::getInstance();
        self::$zendLoaded = true;
    }
    
    static protected $mpdfLoaded = false;

    static public function registerMPDF() {
        if (self::$mpdfLoaded) {
            return;
        }

        set_include_path(sfConfig::get('sf_lib_dir') . '/vendor' . PATH_SEPARATOR . get_include_path());
        require_once sfConfig::get('sf_lib_dir') . '/vendor/MPDF54/mpdf.php';
        //Zend_Loader::getInstance();
        self::$mpdfLoaded = true;
    }

    public function setup() {
        //$this->setWebDir($this->getRootDir().DIRECTORY_SEPARATOR.'html');
        $this->enablePlugins('sfDoctrinePlugin');
        $this->enablePlugins('sfDoctrineGuardPlugin');
        $this->enablePlugins('csDoctrineActAsSortablePlugin');
        $this->enablePlugins('sfJqueryReloadedPlugin');
        $this->enablePlugins('sfAdminDashPlugin');
        $this->enablePlugins('sfFormExtraPlugin');
        $this->enablePlugins('sfImageTransformPlugin');
        $this->enablePlugins('ksWdCalendarPlugin');
        $this->enablePlugins('laiguFeyaSoftCalendarPlugin');

  }
}
