<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('home/ejecutarReporte')?>" method="post">
<input type="hidden" name="tipo" value="<?php echo $tipo ?>" />
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
        <?php echo $form ?>
    </tbody>
  </table>
</form>
