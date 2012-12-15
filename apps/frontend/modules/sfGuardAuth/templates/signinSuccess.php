<?php use_helper('I18N') ?>
<?php use_stylesheet('login.css') ?>
<?php //include_partial('sfAdminDash/login', array('form' => $form)); ?>
<div class="header_login" style="background-image: url(/images/login/header_background.jpg);">
    <img src="/images/login/header.jpg"/>
</div>
<div id="ctr" align="center">
    <div class="inicio_session" style="margin-top: 100px;background-color: #2A7FD9; padding: 20px; width: 250px; color: white; font-size: 20pt;">
        Inicio de Session
    </div>
  <div class="login_richpolis">
    <div class="login-form-richpolis">
      <form action="<?php echo url_for(sfAdminDash::getProperty('login_route', '@sf_guard_signin')); ?>" method="post">
        <!--img alt="Login" src="<?php echo image_path(sfAdminDash::getProperty('web_dir', '/sfAdminDashPlugin').'/images/login.gif'); ?>" /-->
        <div class="form-block" style="background-color: transparent; border: none;">
          <?php echo $form->renderGlobalErrors(); ?>
          <?php if(isset($form['_csrf_token'])): ?>
            <?php echo $form['_csrf_token']->render(); ?> 
	  <?php endif; ?>
          <div class="inputlabel">
              <?php echo $form['username']->renderLabel(__('Username', array(), 'sf_admin_dash')); ?>:
          </div>
          <div>
            <?php echo $form['username']->renderError(); ?>
            <?php echo $form['username']->render(array('class' => 'inputbox')); ?>
          </div>
          <div class="inputlabel">
              <?php echo $form['password']->renderLabel(__('Password', array(), 'sf_admin_dash')); ?>:
          </div>
          <div>
            <?php echo $form['password']->renderError(); ?>
            <?php echo $form['password']->render(array('class' => 'inputbox')); ?>
          </div>
          <div class="inputlabel">
                <?php echo $form['remember']->render(array('class' => 'inputcheck')); ?>
                <?php echo $form['remember']->renderLabel(__('Remember?', array(), 'sf_admin_dash')); ?>
          </div>
          <div align="right">
              <!--input type="submit" name="submit" class="button clr" value="<?php echo __('Login', array(), 'sf_admin_dash'); ?>" /-->
              <a class="boton_login" href="javascript:void(0);" onclick="javascript:forms[0].submit();">
                  <?php echo __('Login', array(), 'sf_admin_dash'); ?>
              </a>
          </div>
        </div>
      </form>
    </div>
    <!--div class="login-text">
      <div class="ctr"><img alt="Security" src="<?php echo image_path(sfAdminDash::getProperty('web_dir', '/sfAdminDashPlugin').'/images/login_security.png'); ?>" /></div>
      <p><?php echo __('Welcome to', array(), 'sf_admin_dash'); ?> <?php echo sfAdminDash::getProperty('site'); ?></p>
      <p><?php echo __('Use a valid username and password to gain access to the administration console.', array(), 'sf_admin_dash'); ?></p>
    </div-->

    <div class="clr"></div>
  </div>
</div>
