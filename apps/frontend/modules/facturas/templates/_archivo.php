<?php if(file_exists(sfConfig::get('sf_upload_dir').'/contratos/'.$contratos->getFile())):?>
 <a target="_blank" href="/uploads/contratos/<?php echo $contratos->getFile() ?>" title="Descargar contrato">
   <img src="/images/comercializacion/contrato.png" class="thumbnail-pdf" width="16px" height="16px" />
 </a>
<?php else:?>
  Vacio
<?php endif;?> 