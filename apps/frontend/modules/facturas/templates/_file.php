<?php $help="";?>

<table border="0" width="100%">
    <tr>
        <td>
            <div class="<?php echo "sf_contratos" ?><?php $form["file"]->hasError() and print ' errors' ?>">
                <?php echo $form["file"]->renderError() ?>
                <div>
                  <?php echo $form["file"]->renderLabel("Archivo") ?>

                  <div class="content"><?php echo $form["file"]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?></div>

                  <?php if ($help): ?>
                    <div class="help"><?php echo __($help, array(), 'messages') ?></div>
                  <?php elseif ($help = $form["file"]->renderHelp()): ?>
                    <div class="help"><?php echo $help ?></div>
                  <?php endif; ?>
                </div>
              </div>

        </td>
        <td>
            <?php if(file_exists(sfConfig::get('sf_upload_dir').'/contratos/'.$form->getObject()->getFile())):?>
                <a target="_blank" href="/uploads/contratos/<?php echo $form->getObject()->getFile() ?>" title="Descargar contrato">
                    <img src="/images/comercializacion/contrato.png" class="thumbnail-pdf" />
                </a> Contrato actual
            <?php else:?>
                &nbsp;
            <?php endif;?>
        </td>
    </tr>
</table>

