<?php if(!$eventos==null):?>
<ul class="lista-registro-evento" style="width:<?php echo count($eventos)*220?>px;">
<?php foreach($eventos as $evento):?>
<li class="dash-registro registro-evento" id="evento-id-<?php echo $evento->getId()?>">
    <a href="#" onclick="$.editarEvento('<?php echo $evento->getId()?>')">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro" />
    </a>
    <div class="titulo-dash-registro">
        <?php echo $evento->getSubject();?>
    </div>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>