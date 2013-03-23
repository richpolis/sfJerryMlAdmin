$(document).ready(function(){
   $("input:submit, input:button").button();
   setTimeout(function(){
        $(".notice").click();
        $(".error").click();
   },1000);
});
