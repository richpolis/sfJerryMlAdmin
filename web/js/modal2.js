   /* 
    modal.js ventana modal basica
	Copyright � Jesus Li�an www.ribosomatic.com
	
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.*/
	
(function($){
	//parametros principales
	
	//var contenidoHTML = $('#div_mensaje_manager').html();
	
	

	$.fn.modalRich=function(urlForm,typeLoad){
	//function showMensajeModal(){
		// fondo transparente
                $("#preloader").show('fast');
		var bgdiv = $('<div>').attr({
					class: 'bgtransparent',
					id: 'bgtransparent'
					});

		$('body').append(bgdiv);
		
		var wscr = $(window).width();
		var hscr = $(window).height();
				
		/*$('#bgtransparent').css("width", wscr);
		$('#bgtransparent').css("height", hscr);*/

                $('#bgtransparent').animate({width:wscr ,height:20}, 'slow',null,function(){
                    $(this).animate({height:hscr},'slow',null,function(){
                        var moddiv = $('<div>').attr({
					class: 'bgmodal2',
					id: 'dialog-form'
					});

                        $('body').append(moddiv);
                        
                        $('#dialog-form').load(urlForm, {typeFile:typeLoad}, function(){
                            $("#formContacto").show('fast',function(){
                                $("#id_close_button_form").show("fast",function(){
                                    $("#preloader").hide('fast',function(){
                                        //$("#dialog-form").fadeIn('fast');
                                        $('form').find('input:first').select();
                                    });
                                });
                            }).css('opacity',1);
                        });

                        $(window).resize();
                    });
                });
		
		
		// ventana flotante
		/*var moddiv = $('<div>').attr({
					className: 'bgmodal',
					id: 'bgmodal'
					});	
		
		$('body').append(moddiv);
		$('#bgmodal').append(contenidoHTML);
		
		$(window).resize();*/
	}
})(jQuery);

$(window).resize(function(){
		// dimensiones de la ventana
                var ancho = 540;
                var alto = 350;
                
		var wscr = $(window).width();
		var hscr = $(window).height();

		// estableciendo dimensiones de background
		$('#bgtransparent').css("width", wscr);
		$('#bgtransparent').css("height", hscr);
		
		// definiendo tama�o del contenedor
		$('#dialog-form').css("width", ancho+'px');
		$('#dialog-form').css("height", alto+'px');


		
		// obtiendo tama�o de contenedor
		var wcnt = $('#dialog-form').width();
		var hcnt = $('#dialog-form').height();
		
		// obtener posicion central
		var mleft = ( wscr - wcnt ) / 2;
		var mtop = ( hscr - hcnt ) / 2;
		
		// estableciendo posicion
		$('#dialog-form').css("left", mleft+'px');
		$('#dialog-form').css("top", mtop+'px');

});
$(window).keyup(function(event){
    if (event.keyCode == 27) {
         closeMensajeModal();
    }
});

function closeModalRich(){
       $('#dialog-form').fadeOut('slow',function(){
          $(this).remove();
          $('#bgtransparent').slideUp('slow',function(){
              $(this).remove();
          });
       });
       
}
