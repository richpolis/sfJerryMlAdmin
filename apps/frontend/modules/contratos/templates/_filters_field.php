<td>
<table border="0">
  <tr>
    <td width="100px;">
      <?php echo $form[$name]->renderLabel($label) ?>
    </td>
    
    <td width="250px;">
      <?php echo $form[$name]->renderError() ?>

      <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>

      <?php if ($help || $help = $form[$name]->renderHelp()): ?>
        <div class="help"><?php echo __($help, array(), 'messages') ?></div>
      <?php endif; ?>
    </td>
  </tr>
</table>    
</td>

