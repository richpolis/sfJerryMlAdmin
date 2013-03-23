<?php if(file_exists(sfConfig::get('sf_upload_dir').'/facturas/'.$facturas->getFile())):?>
 <a target="_blank" href="/uploads/facturas/<?php echo $facturas->getFile() ?>" title="Descargar factura">
   <img src="/images/comercializacion/contrato.png" class="thumbnail-pdf" width="16px" height="16px" />
 </a>
<?php else:?>
  Vacio
<?php endif;?> 