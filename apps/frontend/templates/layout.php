<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_slot('adicional-css')?>
    <?php include_javascripts() ?>
    <?php $module=$sf_request->getParameterHolder()->get('module');?>
    <?php $action=$sf_request->getParameterHolder()->get('action');?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#sf_admin_bar").hide();
            $("#boton_filtros").hide();
       });
    </script>
  </head>
  <body style="padding: 0px;">
      <div id="container" height="100%" width="100%">
        <?php include_component('sfAdminDash','header'); ?>
          <?php if($action=='new' || $action=='edit'):?>
          <div id="time" style="padding: 10px; background-color: white; position: fixed; top: 200px; right: 0px;"></div> 
          <?php endif;?>
          <div id="content">
            <?php echo $sf_content ?>
          </div>
        <?php include_partial('sfAdminDash/footer'); ?>
      </div>
  </body>
</html>
