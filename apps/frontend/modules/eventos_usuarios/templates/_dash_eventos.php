<?php foreach($eventos as $evento):?>
<div class="dash-registro" id="evento-id-<?php echo $evento->getId()?>">
    <a href="#" onclick="$.editarEvento('<?php echo $evento->getId()?>')">
            <img src="/images/dashboard/NEXT.png" class="image-status-dash-registro" />
    </a>
    <div class="titulo-dash-registro">
        <?php echo $evento->getSubject();?>
    </div>
</div>
<?php endforeach;?>
