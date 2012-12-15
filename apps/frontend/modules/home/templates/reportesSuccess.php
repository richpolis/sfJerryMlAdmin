<?php use_helper('I18N', 'Date','Number') ?>

<div id="sf_admin_container" style="min-height: 400px;">
  <h1><?php echo __('Reportes ', array(), 'messages') ?></h1>
  <?php if(!isset($tipo)):?>
  
  <div id="reportes-disponibles">
      <ul class="ul-reporte">
          <li class="li-reporte">
              <input type="button" id="repote-pagos-jerryml" value="Reporte de pagos JerryML por cliente"/>
          </li>
          <li class="li-reporte">
              <input type="button" id="repote-pagos-jerryml-por-concepto" value="Reporte de pagos JerryML por concepto"/>
          </li>
          <li class="li-reporte">
              <input type="button" id="repote-pagos-talentos" value="Reporte de pagos Talentos"/>
          </li>
          <li class="li-reporte">
              <input type="button" id="repote-pagos-clientes" value="Reporte de pagos Clientes"/>
          </li>
      </ul>
  </div>
  
  <div id="form-reportes">
    
  </div>
  <?php else:?>
  <div id="form-reportes">
    <?php include_partial("form", array("form"=>$form,"tipo"=>$tipo)) ?>
  </div>
  <?php endif;?>
  <div id="resultado-reportes">
      
  </div>    
      
  <div style="clear: both;"></div>
  <div id="sf_admin_footer" >
    <?php //include_partial('clientes/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
<script>
 $(document).ready(function(){
     
     $("input:button").button();
     
     $("#repote-pagos-jerryml").click(function(){
         $.crearFormulario("jerryml"); 
     });
     $("#repote-pagos-talentos").click(function(){
         $.crearFormulario("talentos"); 
     });
     $("#repote-pagos-clientes").click(function(){
         $.crearFormulario("clientes"); 
     });
     $("#repote-pagos-jerryml-por-concepto").click(function(){
         $.crearFormulario("jerryml-por-concepto"); 
     });
 });   
    
    
  var overlay = {
     show    : function(){
            $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
     },
     hide    : function(){
            $('#overlay,#preloader').remove();
     }
  }
  

jQuery.crearFormulario=function(TipoReporte){
    overlay.show();
    $('#form-reportes').attr('tiporeporte',TipoReporte);
    $.get('<?php echo url_for('home/reportes')?>',{tipo:TipoReporte},function(data){
        $("#reportes-disponibles").fadeOut("fast", function(){
            $('#form-reportes').html(data);
            overlay.hide();
        });
    });
}
jQuery.crearReporte=function(){
    overlay.show();
    var form = $('form#form-reportes-ajax');
    var variables=form.serialize();
    $.post(form.attr('action'),variables,function(data){
        $("#form-reportes").fadeOut("fast", function(){
            $('#resultado-reportes').html(data);
            overlay.hide();
        });
    });
}
</script>
