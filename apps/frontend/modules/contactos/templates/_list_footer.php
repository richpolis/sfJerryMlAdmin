<script>
var overlay = {
     show    : function(){
            $('body').append('<div id="overlay"></div><div id="preloader">Enviando...</div>');
     },
     hide    : function(){
            $('#overlay,#preloader').remove();
     }
  }    
jQuery.crearContacto=function(){
    overlay.show();
    $('#dialog-form-contacto').attr('contacto-id','0');
    $.get('<?php echo url_for('@contactos_new')?>',{'ajax':true},function(data){
        $('#dialog-form-contacto').html(data).dialog('open');
        overlay.hide();
    });
}  
$(function() {
    
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
    $( "#dialog-form-contacto" ).dialog({
	autoOpen: false,
	height: 450,
	width: 350,
	modal: true,
	buttons: {
            "Crear": function() {
                var bValid = true;
                allFields.removeClass( "ui-state-error" );
        	bValid = bValid && checkLength( nombre, "Nombre", 3, 150 );
                bValid = bValid && checkLength( apellidos, "Apellidos", 3, 200 );
		bValid = bValid && checkLength( email, "email", 6, 80 );
		//bValid = bValid && checkRegexp( nombre, /^[a-z]([0-9a-z_])+$/i, "Nombre may consist of a-z, 0-9, underscores, begin with a letter." );
                //bValid = bValid && checkRegexp( apellidos, /^[a-z]([0-9a-z_])+$/i, "Apellidos may consist of a-z, 0-9, underscores, begin with a letter." );
        	// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
		bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
		
                if ( bValid ) {
			overlay.show();
                        var form = $('#dialog-form-contacto form');
                        var variables=form.serialize();
                        $.post(form.attr('action'),variables,function(data){
                            if(data=="ok"){
                                var regresarA='<?php echo url_for($sf_user->getRegresarA())?>';
                                location.href=regresarA;
                                overlay.hide();
                            }else{
                                $('#dialog-form-contacto').html(data);
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
<div id="dialog-form-contacto" title="contacto" contacto-id="">
    
</div>
