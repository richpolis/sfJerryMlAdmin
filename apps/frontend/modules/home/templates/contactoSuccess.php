<?php use_stylesheet('prettyPhoto.css') ?>
<?php use_stylesheet('galeria.css') ?>
<?php use_javascript('jquery.prettyPhoto.js') ?>
<?php use_javascript('sfrichpolis.js') ?>
<?php use_helper('Escaping') ?>

<h1 class="titulo-pagina-h1">
    <span class="titulo-pagina-span">
        Contacto
    </span>
</h1>
<section id="contacto-direcciones" style="padding-top: 10px;text-align: center;">
    <?php if(!$contactos==null): ?>
        <div class="contenido-publicacion">
            <?php echo $contactos->getContenido(ESC_RAW); ?>
        </div>
           <img src="/uploads/assets/<?php echo $contactos->getImagen()?>"/>
     
    <?php else: ?>
        <dl class="address">
            <dt>The Company Name Inc. 9870 St Vincent Place, Glasgow, DC 45 Fr 45.</dt>
            <dd>
                <ul>
                    <li><span>Telephone:</span>+1 800 603 6035</li>
                    <li><span>FAX:</span>+1 800 889 9898</li>
                    <li>E-mail: <a href="#">mail@demolink.org</a></li>
                </ul>
            </dd>
        </dl>
    <?php endif; ?>
    
</section>
<section id="dialog-form">                
      <?php include_partial('form_contacto', array('form'=>$form,"mensaje_ok"=>$mensaje_ok)) ?>
</section>
<div style="clear: both;"></div>
<div class="pie-pagina-contenido">
    <div class="development-phrenesis">
        <a href="http://phrenesis.net" target="_black"> Developed by  Phrenesis Creative Works</a>
    </div>
</div> 

