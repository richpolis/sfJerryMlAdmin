
<script>
  var overlay = {
     show    : function(){
            $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
     },
     hide    : function(){
            $('#overlay,#preloader').remove();
     }
  }    
jQuery.crearCliente=function(){
     overlay.show();
     $('#dialog-form-cliente').attr('cliente-id','0');
     $.get('<?php echo url_for('@clientes_new')?>',{'ajax':true},function(data){
         $('#dialog-form-cliente').html(data).dialog('open');
         overlay.hide();
     });
}  
$(document).ready(function(){
            $( "#dialog:ui-dialog" ).dialog( "destroy" );

            $( "#dialog-form-cliente" ).dialog({
                autoOpen: false,
                height: 250,
                width: 300,
                modal: true,
                buttons: {
                    "Crear": function() {
                        var bValid = true;
                        allFields.removeClass( "ui-state-error" );
                        bValid = bValid && checkLength( razon_social, "Nombre Empresa", 3, 150 );
                        
                        if ( bValid ) {
                                overlay.show();
                                var form = $('#dialog-form-cliente form');
                                var variables=form.serialize();
                                $.post(form.attr('action'),variables,function(data){
                                    if(data=="ok"){
                                        var regresarA='<?php echo url_for($sf_user->getRegresarA())?>';
                                        location.href=regresarA;
                                        overlay.hide();
                                    }else{
                                        $('#dialog-form-cliente').html(data);
                                        overlay.hide();
                                    }
                                });
                        }
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                },
                close: function() {
                    allFields.val( "" ).removeClass( "ui-state-error" );
                }
             });
        
});

</script>
<div id="dialog-form-cliente" title="Cliente" cliente-id="">
    
</div>
