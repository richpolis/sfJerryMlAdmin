<?php
/**
* We need to make sure this plugin is backward compatible. 
* The in the original, this template was invoked by "include_partial",
* which means that now it won't work. Therefore, we set a variable in the 
* component code, and if it is not present - we call the component
*/

if (!isset($called_from_component)):
  include_component('sfAdminDash', 'header');
else:
?>


<?php 
  use_helper('I18N');
  use_helper('Escaping');
  /** @var Array of menu items */ $items = $sf_data->getRaw('items');
  /** @var Array of categories, each containing an array of menu items and settings */ $categories = $sf_data->getRaw('categories');
  /** @var string|null Link to the module (for breadcrumbs) */ $module_link = $sf_data->getRaw('module_link');
  /** @var string|null Link to the action (for breadcrumbs) */ $action_link = $sf_data->getRaw('action_link');
?> 

<?php if ($sf_user->isAuthenticated()): ?> 
    
  <div id='sf_admin_theme_header' style="background-image: url(/images/login/header_background.jpg);height: 46px;border-bottom: none;">
    <?php if(!$sf_user->hayModoActivo()):?>  
        <a style="float: left; margin-left: 10px; width: 130px;" href="#">
            <img src="/images/login/header.jpg" alt="Home" style="max-width: none;"/>
        </a>
      
        <a style="float: left; margin-left: 10px; padding-top: 10px;" href='<?php echo url_for('homepage') ?>'>
        <?php echo image_tag('/images/dashboard/dashboard.png', array('alt' => 'Dashboard')); ?>    
        </a>
        <a style="float: left; margin-left: 10px; padding-top: 10px;" href='<?php echo url_for("sfGuardUser/listCalendario?id=".$sf_user->getGuardUser()->getId()."&goto=@homepage") ?>'>
        <?php echo image_tag('/images/dashboard/micalendario.png', array('alt' => 'Mi calendario')); ?>    
        </a>
        <a style="float: left; margin-left: 10px; padding-top: 10px;" href='<?php echo url_for('calendario_usuarios') ?>'>
        <?php echo image_tag('/images/dashboard/calendariojerry.png', array('alt' => 'Calendario Jerry Ml')); ?>    
        </a>
        <a style="float: left; margin-left: 10px; padding-top: 10px;" href='<?php echo url_for('calendario_talentos') ?>'>
        <?php echo image_tag('/images/dashboard/calendariotalento.png', array('alt' => 'Calendario Talento')); ?>    
        </a>
      <div style="clear: both"></div>
    <?php else: ?> 
        <a href="#">
            <img src="/images/login/header.jpg" alt="Home" style="max-width: none;"/>
        </a>
    <?php endif;?>
  </div>

  <div id='sf_admin_menu'>
    <?php if(!$sf_user->hayModoActivo()):?>  
        <?php include_partial('sfAdminDash/menu', array('items' => $items, 'categories' => $categories)); ?>
    <?php else: ?> 
      <div class="modo-activo-seleccion">
          <?php echo $sf_user->getStatusDeModo(ESC_RAW)?>
      </div>
    <?php endif;?>  
    
    <?php if (sfAdminDash::getProperty('logout') && $sf_user->isAuthenticated()): ?>
      <div id="logout"><?php echo link_to(__('Logout', null, 'sf_admin_dash'), sfAdminDash::getProperty('logout_route', '@sf_guard_signout ')); ?> <?php echo $sf_user; ?></div>
    <?php else:?>
      <div id="logout"><a href="javascript:void(0)">Usuario Invitado</a></div>  
    <?php endif; ?>
    <div class="clear"></div>
  </div>

  <?php if (sfAdminDash::getProperty('include_path')): ?>
    <div id='sf_admin_path'>
      <strong><a href='<?php echo url_for('homepage'); ?>'><?php echo sfAdminDash::getProperty('site'); ?></a></strong> 
      <?php if ($sf_context->getModuleName() != 'sfAdminDash' && $sf_context->getActionName() != 'dashboard'): ?>
        / <?php echo null !== $module_link ? link_to($module_link_name, $module_link) : $module_link_name; ?>
        <?php if (null != $action_link): ?>
          / <?php echo link_to(__(ucfirst($action_link_name), null, 'sf_admin'), $action_link); ?>
        <?php endif ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>


<?php endif; // BC check if ?>
