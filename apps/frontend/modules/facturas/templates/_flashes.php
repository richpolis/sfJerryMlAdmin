<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="notice"><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
  <div class="error"><?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?></div>
<?php endif; ?>

<script>
$(document).ready(function(){
    $(".notice").click(function(){
       $(this).fadeOut("slow"); 
    });
    $(".error").click(function(){
       $(this).fadeOut("slow"); 
    });
    setTimeout(function(){
    	$('.notice, .error').click();
    },3000);
    
});
</script>